<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PembayaranIuran;
use App\Models\TagihanSiswa;
use App\Models\Siswa;
use App\Models\PaketIuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    // Dashboard pembayaran
    public function index()
    {
        $bulanIni = date('n');
        $tahunIni = date('Y');
        
        // Statistik
        $totalPembayaranBulanIni = PembayaranIuran::periode($bulanIni, $tahunIni)
            ->status('lunas')
            ->sum('nominal');
        
        $jumlahSiswaBayar = PembayaranIuran::periode($bulanIni, $tahunIni)
            ->status('lunas')
            ->distinct('siswa_id')
            ->count('siswa_id');
        
        $totalSiswaAktif = Siswa::where('status', 'Aktif')->count();
        
        $siswaBelumBayar = $totalSiswaAktif - $jumlahSiswaBayar;
        
        // Daftar tagihan belum bayar
        $tagihanBelumBayar = TagihanSiswa::with('siswa', 'paket')
            ->belumBayar()
            ->orderBy('jatuh_tempo', 'asc')
            ->paginate(10);
        
        // Pembayaran terakhir
        $pembayaranTerakhir = PembayaranIuran::with('siswa', 'paket')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('pembayaran.index', compact(
            'totalPembayaranBulanIni',
            'jumlahSiswaBayar',
            'siswaBelumBayar',
            'totalSiswaAktif',
            'tagihanBelumBayar',
            'pembayaranTerakhir'
        ));
    }
    
    // Form input pembayaran
    public function create(Request $request)
    {
        $siswaId = $request->input('siswa_id');
        $siswa = $siswaId ? Siswa::with('paketIuran')->findOrFail($siswaId) : null;
        
        $siswas = Siswa::where('status', 'Aktif')->orderBy('nama')->get();
        $pakets = PaketIuran::aktif()->orderBy('kelompok_umur')->orderBy('nama_paket')->get();
        
        // Jika ada siswa dipilih, ambil tagihan belum bayar
        $tagihanBelumBayar = null;
        if ($siswa) {
            $tagihanBelumBayar = TagihanSiswa::where('siswa_id', $siswa->id)
                ->belumBayar()
                ->orderBy('periode_tahun', 'asc')
                ->orderBy('periode_bulan', 'asc')
                ->get();
        }
        
        return view('pembayaran.create', compact('siswas', 'pakets', 'siswa', 'tagihanBelumBayar'));
    }
    
    // Simpan pembayaran
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'paket_iuran_id' => 'nullable|exists:paket_iurans,id',
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer|min:2020',
            'tanggal_bayar' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer,qris,lainnya',
            'bukti_pembayaran' => 'nullable|image|max:2048',
            'catatan' => 'nullable|string',
        ]);
        
        // Validasi: Cek apakah iuran bulan ini sudah terbayar
        $sudahBayar = PembayaranIuran::where('siswa_id', $validated['siswa_id'])
            ->where('periode_bulan', $validated['periode_bulan'])
            ->where('periode_tahun', $validated['periode_tahun'])
            ->where('status', 'lunas')
            ->exists();
        
        if ($sudahBayar) {
            $namaBulan = \DateTime::createFromFormat('!m', $validated['periode_bulan'])->format('F');
            return redirect()->back()
                ->withInput()
                ->withErrors(['periode_bulan' => "Iuran bulan {$namaBulan} {$validated['periode_tahun']} untuk siswa ini sudah terbayar!"]);
        }
        
        // Upload bukti pembayaran jika ada
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = 'bukti_' . time() . '_' . $validated['siswa_id'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/uploads/pembayaran', $filename);
            $validated['bukti_pembayaran'] = 'storage/uploads/pembayaran/' . $filename;
        }
        
        $validated['status'] = 'lunas';
        $validated['created_by'] = auth()->check() ? auth()->user()->name : 'Admin';
        
        // Simpan pembayaran
        $pembayaran = PembayaranIuran::create($validated);
        
        // Update tagihan jika ada
        $tagihan = TagihanSiswa::where('siswa_id', $validated['siswa_id'])
            ->where('periode_bulan', $validated['periode_bulan'])
            ->where('periode_tahun', $validated['periode_tahun'])
            ->first();
        
        if ($tagihan) {
            $tagihan->update(['status' => 'lunas']);
        }
        
        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil disimpan!');
    }
    
    // Detail pembayaran siswa
    public function show($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        
        $pembayarans = PembayaranIuran::where('siswa_id', $siswaId)
            ->with('paket')
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->paginate(12);
        
        $tagihans = TagihanSiswa::where('siswa_id', $siswaId)
            ->with('paket')
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->paginate(12);
        
        $totalBayar = PembayaranIuran::where('siswa_id', $siswaId)
            ->where('status', 'lunas')
            ->sum('nominal');
        
        $tunggakan = TagihanSiswa::where('siswa_id', $siswaId)
            ->belumBayar()
            ->sum('nominal');
        
        return view('pembayaran.show', compact('siswa', 'pembayarans', 'tagihans', 'totalBayar', 'tunggakan'));
    }
    
    // Laporan pembayaran
    public function laporan(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));
        
        $pembayarans = PembayaranIuran::with('siswa', 'paket')
            ->periode($bulan, $tahun)
            ->orderBy('tanggal_bayar', 'desc')
            ->get();
        
        $totalPemasukan = $pembayarans->sum('nominal');
        $jumlahTransaksi = $pembayarans->count();
        
        // Group by metode pembayaran
        $byMetode = $pembayarans->groupBy('metode_pembayaran')->map(function($items) {
            return [
                'jumlah' => $items->count(),
                'total' => $items->sum('nominal')
            ];
        });
        
        return view('pembayaran.laporan', compact('pembayarans', 'totalPemasukan', 'jumlahTransaksi', 'byMetode', 'bulan', 'tahun'));
    }
    
    // Monitoring iuran per siswa (12 bulan)
    public function monitoring(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        // Ambil semua siswa aktif
        $siswas = Siswa::where('status', 'Aktif')
            ->orderBy('tanggal_lahir', 'desc')
            ->orderBy('nama')
            ->get();
        
        // Ambil data pembayaran untuk tahun yang dipilih
        $pembayarans = PembayaranIuran::where('periode_tahun', $tahun)
            ->where('status', 'lunas')
            ->get()
            ->groupBy('siswa_id');
        
        // Buat array status untuk setiap siswa per bulan
        $monitoringData = [];
        foreach ($siswas as $siswa) {
            $statusBulan = [];
            
            // Tentukan bulan dan tahun masuk siswa
            $tanggalMasuk = $siswa->tanggal_masuk ?: $siswa->created_at;
            $bulanMasuk = $tanggalMasuk->month;
            $tahunMasuk = $tanggalMasuk->year;
            
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                // Jika bulan ini sebelum siswa masuk di tahun yang dipilih, tampilkan kosong
                if ($tahun == $tahunMasuk && $bulan < $bulanMasuk) {
                    $statusBulan[$bulan] = null; // Belum bergabung
                } elseif ($tahun < $tahunMasuk) {
                    $statusBulan[$bulan] = null; // Belum bergabung
                } else {
                    $statusBulan[$bulan] = '-';
                    
                    // Cek apakah ada pembayaran untuk bulan ini
                    if (isset($pembayarans[$siswa->id])) {
                        $bayar = $pembayarans[$siswa->id]->firstWhere('periode_bulan', $bulan);
                        if ($bayar) {
                            $statusBulan[$bulan] = 'Lunas';
                        }
                    }
                }
            }
            
            $monitoringData[] = [
                'siswa' => $siswa,
                'status_bulan' => $statusBulan,
            ];
        }
        
        return view('pembayaran.monitoring', compact('monitoringData', 'tahun'));
    }
}

