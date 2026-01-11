<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\KategoriPenilaian;
use App\Models\EvaluasiSiswa;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    // Halaman utama absensi - input absensi hari ini
    public function index()
    {
        $today = date('Y-m-d');
        $siswas = Siswa::where('status', 'Aktif')->orderBy('nama')->get();
        
        // Get existing absensi for today
        $absensiHariIni = Absensi::where('tanggal', $today)
            ->pluck('status', 'siswa_id')
            ->toArray();
        
        // Get active holidays untuk validasi client-side
        $hariLibur = HariLibur::where('aktif', true)
            ->pluck('keterangan', 'tanggal')
            ->toArray();
        
        return view('absensi.index', compact('siswas', 'today', 'absensiHariIni', 'hariLibur'));
    }
    
    // Simpan absensi (batch untuk semua siswa sekaligus)
    public function store(Request $request)
    {
        // Force JSON response for AJAX
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $validated = $request->validate([
                    'tanggal' => 'required|date',
                    'sesi' => 'required|in:Pagi,Sore,Full Day',
                    'absensi' => 'required|array|min:1',
                    'absensi.*' => 'required|in:Hadir,Izin,Sakit,Alpa',
                ]);
                
                // Validasi: Cek apakah tanggal adalah hari libur
                if (HariLibur::isHoliday($validated['tanggal'])) {
                    $holiday = HariLibur::where('tanggal', $validated['tanggal'])
                        ->where('aktif', true)
                        ->first();
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat melakukan absensi pada hari libur: ' . $holiday->keterangan
                    ], 422);
                }
                
                DB::beginTransaction();
                
                // Delete existing absensi for this date and sesi
                Absensi::where('tanggal', $validated['tanggal'])
                    ->where('sesi', $validated['sesi'])
                    ->delete();
                
                // Insert new absensi
                $dataAbsensi = [];
                foreach ($request->absensi as $siswaId => $status) {
                    $dataAbsensi[] = [
                        'siswa_id' => $siswaId,
                        'tanggal' => $validated['tanggal'],
                        'sesi' => $validated['sesi'],
                        'status' => $status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                Absensi::insert($dataAbsensi);
                
                // Note: Evaluasi tidak dibuat otomatis di sini.
                // Evaluasi hanya dibuat melalui halaman input evaluasi.
                // Logic pengecekan status absensi ada di EvaluasiController::store()
                // yang akan otomatis set nilai 0 untuk siswa tidak hadir.
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Absensi berhasil disimpan!'
                ]);
                
            } catch (\Illuminate\Validation\ValidationException $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan absensi: ' . $e->getMessage()
                ], 500);
            }
        }
        
        // Non-AJAX fallback
        try {
            $validated = $request->validate([
                'tanggal' => 'required|date',
                'sesi' => 'required|in:Pagi,Sore,Full Day',
                'absensi' => 'required|array|min:1',
                'absensi.*' => 'required|in:Hadir,Izin,Sakit,Alpa',
            ]);
            
            // Validasi: Cek apakah tanggal adalah hari libur
            if (HariLibur::isHoliday($validated['tanggal'])) {
                $holiday = HariLibur::where('tanggal', $validated['tanggal'])
                    ->where('aktif', true)
                    ->first();
                
                return back()->with('error', 'Tidak dapat melakukan absensi pada hari libur: ' . $holiday->keterangan);
            }
            
            DB::beginTransaction();
            
            // Delete existing absensi for this date and sesi
            Absensi::where('tanggal', $validated['tanggal'])
                ->where('sesi', $validated['sesi'])
                ->delete();
            
            // Insert new absensi
            $dataAbsensi = [];
            foreach ($request->absensi as $siswaId => $status) {
                $dataAbsensi[] = [
                    'siswa_id' => $siswaId,
                    'tanggal' => $validated['tanggal'],
                    'sesi' => $validated['sesi'],
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            Absensi::insert($dataAbsensi);
            
            DB::commit();
            return redirect()->route('absensi.index')->with('success', 'Absensi berhasil disimpan!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }
    
    // Riwayat absensi dengan filter
    public function history(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggalAkhir = $request->input('tanggal_akhir', date('Y-m-d'));
        
        $absensis = Absensi::with('siswa')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
            ->orderBy('tanggal', 'desc')
            ->orderBy('siswa_id')
            ->get()
            ->groupBy('tanggal');
        
        return view('absensi.history', compact('absensis', 'tanggalMulai', 'tanggalAkhir'));
    }
    
    // Statistik kehadiran per siswa
    public function statistik(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        $siswas = Siswa::where('status', 'Aktif')
            ->withCount([
                'absensis as total_hadir' => function($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal', $bulan)
                      ->whereYear('tanggal', $tahun)
                      ->where('status', 'Hadir');
                },
                'absensis as total_izin' => function($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal', $bulan)
                      ->whereYear('tanggal', $tahun)
                      ->where('status', 'Izin');
                },
                'absensis as total_sakit' => function($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal', $bulan)
                      ->whereYear('tanggal', $tahun)
                      ->where('status', 'Sakit');
                },
                'absensis as total_alpa' => function($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal', $bulan)
                      ->whereYear('tanggal', $tahun)
                      ->where('status', 'Alpa');
                }
            ])
            ->get();
        
        return view('absensi.statistik', compact('siswas', 'bulan', 'tahun'));
    }
}
