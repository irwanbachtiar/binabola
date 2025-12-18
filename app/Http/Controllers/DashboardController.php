<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\EvaluasiSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Siswa
        $totalSiswa = Siswa::count();
        $siswaAktif = Siswa::where('status', 'Aktif')->count();
        $siswaNonAktif = Siswa::where('status', 'Non-Aktif')->count();
        
        // Absensi Bulan Ini
        $bulanIni = date('Y-m');
        $absensiStats = Absensi::whereYear('tanggal', date('Y'))
            ->whereMonth('tanggal', date('m'))
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        $totalHadir = $absensiStats['Hadir'] ?? 0;
        $totalIzin = $absensiStats['Izin'] ?? 0;
        $totalSakit = $absensiStats['Sakit'] ?? 0;
        $totalAlpa = $absensiStats['Alpa'] ?? 0;
        $totalAbsensi = $totalHadir + $totalIzin + $totalSakit + $totalAlpa;
        $persentaseKehadiran = $totalAbsensi > 0 ? round(($totalHadir / $totalAbsensi) * 100, 1) : 0;
        
        // Evaluasi Bulan Ini
        $totalEvaluasi = EvaluasiSiswa::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->select('siswa_id', 'minggu')
            ->groupBy('siswa_id', 'minggu')
            ->get()
            ->count();
        
        $rataRataNilai = EvaluasiSiswa::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->avg('nilai');
        $rataRataNilai = round($rataRataNilai ?? 0, 1);
        
        // Chart Data - Kehadiran 7 Hari Terakhir
        $kehadiranChart = Absensi::where('tanggal', '>=', now()->subDays(7))
            ->select('tanggal', 'status', DB::raw('count(*) as total'))
            ->groupBy('tanggal', 'status')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');
        
        $chartLabels = [];
        $chartHadir = [];
        $chartTidakHadir = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d/m');
            
            $hadir = 0;
            $tidakHadir = 0;
            
            if (isset($kehadiranChart[$tanggal])) {
                foreach ($kehadiranChart[$tanggal] as $data) {
                    if ($data->status == 'Hadir') {
                        $hadir = $data->total;
                    } else {
                        $tidakHadir += $data->total;
                    }
                }
            }
            
            $chartHadir[] = $hadir;
            $chartTidakHadir[] = $tidakHadir;
        }
        
        // Recent Activities - Evaluasi Terbaru
        $evaluasiTerbaru = EvaluasiSiswa::with('siswa', 'kategori')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Siswa dengan Kehadiran Terbaik Bulan Ini
        $siswaTopKehadiran = Siswa::where('status', 'Aktif')
            ->withCount([
                'absensis as hadir_count' => function($q) {
                    $q->whereYear('tanggal', date('Y'))
                      ->whereMonth('tanggal', date('m'))
                      ->where('status', 'Hadir');
                }
            ])
            ->orderBy('hadir_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('welcome', compact(
            'totalSiswa', 'siswaAktif', 'siswaNonAktif',
            'totalHadir', 'totalIzin', 'totalSakit', 'totalAlpa', 'persentaseKehadiran',
            'totalEvaluasi', 'rataRataNilai',
            'chartLabels', 'chartHadir', 'chartTidakHadir',
            'evaluasiTerbaru', 'siswaTopKehadiran'
        ));
    }
}
