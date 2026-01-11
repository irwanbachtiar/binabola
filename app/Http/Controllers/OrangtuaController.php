<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Siswa;
use App\Models\EvaluasiSiswa;
use App\Models\Absensi;
use App\Models\PembayaranIuran;
use App\Models\TagihanSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class OrangtuaController extends Controller
{
    /**
     * Dashboard orangtua - show list of connected students
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get all connected students with basic statistics
        $siswas = $user->siswas()->with(['evaluasiSiswas', 'absensis'])->get();
        
        if ($siswas->isEmpty()) {
            return view('orangtua.no-siswa');
        }
        
        // Calculate statistics for each siswa
        $tanggalMulai = \Carbon\Carbon::now()->subDays(30);
        
        foreach ($siswas as $siswa) {
            // Kehadiran 30 hari terakhir
            $totalAbsensi = $siswa->absensis()->where('tanggal', '>=', $tanggalMulai)->count();
            $siswa->hadir_count = $siswa->absensis()->where('tanggal', '>=', $tanggalMulai)->where('status', 'Hadir')->count();
            $siswa->persentase_hadir = $totalAbsensi > 0 ? round(($siswa->hadir_count / $totalAbsensi) * 100, 1) : 0;
            
            // Evaluasi terakhir
            $siswa->total_evaluasi = $siswa->evaluasiSiswas()->count();
            $siswa->rata_rata_evaluasi = $siswa->evaluasiSiswas()->avg('nilai') ? round($siswa->evaluasiSiswas()->avg('nilai'), 1) : 0;
            
            // Latest evaluation date
            $latestEval = $siswa->evaluasiSiswas()->latest('tanggal_evaluasi')->first();
            $siswa->latest_eval_date = $latestEval ? $latestEval->tanggal_evaluasi : null;
            
            // Status pembayaran bulan ini
            $bulanIni = date('n');
            $tahunIni = date('Y');
            $siswa->sudah_bayar_bulan_ini = PembayaranIuran::where('siswa_id', $siswa->id)
                ->where('periode_bulan', $bulanIni)
                ->where('periode_tahun', $tahunIni)
                ->where('status', 'lunas')
                ->exists();
            
            // Jumlah tunggakan
            $siswa->jumlah_tunggakan = TagihanSiswa::where('siswa_id', $siswa->id)
                ->belumBayar()
                ->count();
        }
        
        return view('orangtua.dashboard', compact('siswas', 'user'));
    }
    
    /**
     * Show detailed student progress and evaluation (same as admin view)
     */
    public function showSiswaEvaluasi($id)
    {
        $user = auth()->user();
        $siswa = $user->siswas()->findOrFail($id);
        
        // Reuse the logic from EvaluasiController
        $currentYear = date('Y');
        $evaluasi = EvaluasiSiswa::where('siswa_id', $id)
            ->whereIn('tahun', [$currentYear, $currentYear - 1])
            ->with('kategori')
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->get();
        
        // Get all kategoris for this student
        $allKategoris = \App\Models\KategoriPenilaian::where('aktif', true)
            ->where('kelompok_umur', $siswa->kelompok_umur)
            ->with('parent')
            ->get();
        
        $parentKategoris = $allKategoris->whereNull('parent_id');
        $subKategoris = $allKategoris->whereNotNull('parent_id');
        $weeks = $evaluasi->pluck('minggu')->unique()->sort()->values();
        $lineChartData = [];
        
        // Prepare line chart data per parent category
        foreach ($parentKategoris as $parent) {
            $chartData = [];
            $subs = $subKategoris->where('parent_id', $parent->id);
            
            foreach ($subs as $sub) {
                $chartData[$sub->nama] = [];
                
                foreach ($weeks as $week) {
                    $nilai = $evaluasi->where('minggu', $week)
                        ->where('kategori_penilaian_id', $sub->id)
                        ->first();
                    
                    $chartData[$sub->nama][] = $nilai ? $nilai->nilai : null;
                }
            }
            
            $lineChartData[$parent->nama] = $chartData;
        }
        
        // Prepare radar chart data
        $radarChartData = [];
        
        foreach ($subKategoris as $kategori) {
            if ($kategori->parent && $kategori->parent->minggu_terakhir == 1) {
                continue;
            }
            
            if ($evaluasi->count() > 0) {
                $nilaiKategori = $evaluasi->where('kategori_penilaian_id', $kategori->id)
                    ->filter(function($eval) {
                        return $eval->nilai !== null;
                    });
                if ($nilaiKategori->count() > 0) {
                    $radarChartData[$kategori->nama] = round($nilaiKategori->avg('nilai'), 1);
                } else {
                    $radarChartData[$kategori->nama] = 0;
                }
            } else {
                $radarChartData[$kategori->nama] = 0;
            }
        }
        
        // Monthly average data
        $monthlyData = [];
        $monthlyAverages = [];
        
        if ($evaluasi->count() > 0) {
            $evaluasiByMonth = $evaluasi->filter(function($item) {
                return !empty($item->tanggal_evaluasi);
            })->groupBy(function($item) {
                return date('Y-m', strtotime($item->tanggal_evaluasi));
            })->sortKeys();
            
            $lastMonths = collect();
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $key = $date->format('Y-m');
                $lastMonths->put($key, $date->format('M Y'));
            }
            
            foreach ($lastMonths as $monthKey => $monthLabel) {
                $monthlyData[$monthKey] = $monthLabel;
                
                if (isset($evaluasiByMonth[$monthKey])) {
                    $evaluasiInMonth = $evaluasiByMonth[$monthKey];
                    
                    $filteredEvaluasi = $evaluasiInMonth->filter(function($eval) use ($subKategoris) {
                        $kategori = $subKategoris->firstWhere('id', $eval->kategori_penilaian_id);
                        if ($kategori && $kategori->parent) {
                            if ($kategori->parent->minggu_terakhir == 1) {
                                return false;
                            }
                            return $eval->nilai !== null;
                        }
                        return false;
                    });
                    
                    if ($filteredEvaluasi->count() > 0) {
                        $monthlyAverages[$monthKey] = round($filteredEvaluasi->avg('nilai'), 1);
                    } else {
                        $monthlyAverages[$monthKey] = null;
                    }
                } else {
                    $monthlyAverages[$monthKey] = null;
                }
            }
        }
        
        // Get attendance statistics
        $totalLatihan = Absensi::where('siswa_id', $id)->count();
        $totalHadir = Absensi::where('siswa_id', $id)->where('status', 'Hadir')->count();
        $totalIzin = Absensi::where('siswa_id', $id)->where('status', 'Izin')->count();
        $totalSakit = Absensi::where('siswa_id', $id)->where('status', 'Sakit')->count();
        $totalAlpa = Absensi::where('siswa_id', $id)->where('status', 'Alpa')->count();
        $persenKehadiran = $totalLatihan > 0 ? round(($totalHadir / $totalLatihan) * 100, 1) : 0;
        
        $historiTidakHadir = Absensi::where('siswa_id', $id)
            ->whereIn('status', ['Izin', 'Sakit', 'Alpa'])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return view('evaluasi.show', compact('siswa', 'allKategoris', 'weeks', 'lineChartData', 'radarChartData', 'evaluasi', 'monthlyData', 'monthlyAverages', 'totalLatihan', 'totalHadir', 'totalIzin', 'totalSakit', 'totalAlpa', 'persenKehadiran', 'historiTidakHadir'));
    }

    /**
     * Detail siswa untuk orangtua
     */
    public function showSiswa($id)
    {
        $user = auth()->user();
        $siswa = $user->siswas()->with(['absensis', 'evaluasiSiswas'])->findOrFail($id);
        
        return view('orangtua.siswa-detail', compact('siswa'));
    }

    /**
     * Show absensi siswa
     */
    public function showSiswaAbsensi($id)
    {
        $user = auth()->user();
        $siswa = $user->siswas()->findOrFail($id);
        $absensis = $siswa->absensis()->latest('tanggal')->paginate(20);
        
        // Calculate statistics
        $startDate = now()->subDays(30);
        $stats = [
            'total' => $siswa->absensis()->where('tanggal', '>=', $startDate)->count(),
            'hadir' => $siswa->absensis()->where('tanggal', '>=', $startDate)->where('status', 'Hadir')->count(),
            'izin' => $siswa->absensis()->where('tanggal', '>=', $startDate)->where('status', 'Izin')->count(),
            'sakit' => $siswa->absensis()->where('tanggal', '>=', $startDate)->where('status', 'Sakit')->count(),
            'alpa' => $siswa->absensis()->where('tanggal', '>=', $startDate)->where('status', 'Alpa')->count(),
        ];
        $stats['persentase_hadir'] = $stats['total'] > 0 ? round(($stats['hadir'] / $stats['total']) * 100, 1) : 0;
        
        return view('orangtua.absensi', compact('siswa', 'absensis', 'stats'));
    }

    /**
     * Monitoring pembayaran siswa
     */
    public function showSiswaPembayaran($id)
    {
        $user = auth()->user();
        $siswa = $user->siswas()->with('paketIuran')->findOrFail($id);
        
        $tahunIni = date('Y');
        $bulanIni = date('n');
        
        // Tagihan belum bayar - grouped by paket
        $tagihanBelumBayar = TagihanSiswa::where('siswa_id', $id)
            ->with('paket')
            ->belumBayar()
            ->orderBy('periode_tahun', 'asc')
            ->orderBy('periode_bulan', 'asc')
            ->get()
            ->groupBy('paket_iuran_id');
        
        // History pembayaran - grouped by paket
        $historyPembayaran = PembayaranIuran::where('siswa_id', $id)
            ->with('paket')
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->limit(12)
            ->get()
            ->groupBy('paket_iuran_id');
        
        // Statistik per paket iuran
        $statistikPerPaket = [];
        $allPaketIds = collect($tagihanBelumBayar->keys())
            ->merge($historyPembayaran->keys())
            ->unique();
        
        foreach ($allPaketIds as $paketId) {
            $paket = \App\Models\PaketIuran::find($paketId);
            if (!$paket) continue;
            
            $statistikPerPaket[$paketId] = [
                'nama' => $paket->nama_paket,
                'nominal_bulanan' => $paket->nominal,
                'total_bayar_tahun_ini' => PembayaranIuran::where('siswa_id', $id)
                    ->where('paket_iuran_id', $paketId)
                    ->where('periode_tahun', $tahunIni)
                    ->where('status', 'lunas')
                    ->sum('nominal'),
                'jumlah_bulan_bayar' => PembayaranIuran::where('siswa_id', $id)
                    ->where('paket_iuran_id', $paketId)
                    ->where('periode_tahun', $tahunIni)
                    ->where('status', 'lunas')
                    ->count(),
                'jumlah_tunggakan' => TagihanSiswa::where('siswa_id', $id)
                    ->where('paket_iuran_id', $paketId)
                    ->belumBayar()
                    ->count(),
            ];
        }
        
        // Statistik pembayaran tahun ini (total semua paket)
        $totalBayarTahunIni = PembayaranIuran::where('siswa_id', $id)
            ->where('periode_tahun', $tahunIni)
            ->where('status', 'lunas')
            ->sum('nominal');
        
        $jumlahBulanBayar = PembayaranIuran::where('siswa_id', $id)
            ->where('periode_tahun', $tahunIni)
            ->where('status', 'lunas')
            ->count();
        
        // Cek status pembayaran bulan ini
        $statusBulanIni = PembayaranIuran::where('siswa_id', $id)
            ->where('periode_bulan', $bulanIni)
            ->where('periode_tahun', $tahunIni)
            ->where('status', 'lunas')
            ->exists();
        
        return view('orangtua.pembayaran', compact(
            'siswa',
            'tagihanBelumBayar',
            'historyPembayaran',
            'statistikPerPaket',
            'totalBayarTahunIni',
            'jumlahBulanBayar',
            'statusBulanIni',
            'bulanIni',
            'tahunIni'
        ));
    }

    /**
     * Form pelunasan iuran
     */
    public function formPelunasan($id)
    {
        $user = auth()->user();
        $siswa = $user->siswas()->with('paketIuran')->findOrFail($id);
        
        // Ambil tagihan belum bayar
        $tagihanBelumBayar = TagihanSiswa::where('siswa_id', $id)
            ->belumBayar()
            ->orderBy('periode_tahun', 'asc')
            ->orderBy('periode_bulan', 'asc')
            ->get();
        
        return view('orangtua.form-pelunasan', compact('siswa', 'tagihanBelumBayar'));
    }

    /**
     * Submit pelunasan iuran
     */
    public function submitPelunasan(Request $request, $id)
    {
        $user = auth()->user();
        $siswa = $user->siswas()->findOrFail($id);
        
        $validated = $request->validate([
            'tagihan_id' => 'required|exists:tagihan_siswas,id',
            'tanggal_bayar' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer,qris,lainnya',
            'bukti_pembayaran' => 'nullable|image|max:2048',
            'catatan' => 'nullable|string',
        ]);
        
        // Ambil data tagihan
        $tagihan = TagihanSiswa::findOrFail($validated['tagihan_id']);
        
        // Validasi tagihan milik siswa yang benar
        if ($tagihan->siswa_id != $id) {
            return back()->with('error', 'Tagihan tidak valid');
        }
        
        // Handle upload bukti pembayaran
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = 'pembayaran_' . $siswa->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('pembayaran', $filename, 'public');
        }
        
        // Simpan pembayaran dengan status pending (menunggu verifikasi admin)
        $pembayaran = PembayaranIuran::create([
            'siswa_id' => $id,
            'paket_iuran_id' => $tagihan->paket_iuran_id,
            'periode_bulan' => $tagihan->periode_bulan,
            'periode_tahun' => $tagihan->periode_tahun,
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'nominal' => $validated['nominal'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'bukti_pembayaran' => $buktiPath,
            'status' => 'pending', // Menunggu verifikasi admin
            'catatan' => $validated['catatan'],
            'created_by' => $user->id,
        ]);
        
        return redirect()
            ->route('orangtua.siswa.pembayaran', $id)
            ->with('success', 'Pembayaran berhasil diajukan dan menunggu verifikasi admin');
    }
}
