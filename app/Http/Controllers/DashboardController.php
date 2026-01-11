<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\EvaluasiSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Helper: Detect mobile device
    private function isMobile()
    {
        $userAgent = request()->header('User-Agent');
        return preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);
    }
    
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
        
        // Chart Data - Kehadiran per Minggu (4 minggu terakhir)
        $weeks = 4;
        $chartLabels = [];
        $chartHadir = [];
        $chartTidakHadir = [];

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $startOfWeek = now()->startOfWeek()->subWeeks($i)->copy();
            $endOfWeek = $startOfWeek->copy()->endOfWeek();

            $label = $startOfWeek->format('d/m') . ' - ' . $endOfWeek->format('d/m');
            $chartLabels[] = $label;

            $data = Absensi::whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->keyBy('status');

            $hadir = isset($data['Hadir']) ? $data['Hadir']->total : 0;
            $tidakHadir = 0;
            foreach ($data as $status => $d) {
                if ($status != 'Hadir') {
                    $tidakHadir += $d->total;
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
        
        // TOP SISWA - penilaian tertinggi bulan ini (rata-rata nilai)
        $siswaTopPenilaian = Siswa::where('status', 'Aktif')
            ->withAvg(['evaluasiSiswas as avg_nilai' => function($q) {
                $q->whereYear('created_at', date('Y'))
                  ->whereMonth('created_at', date('m'));
            }], 'nilai')
            ->orderByDesc('avg_nilai')
            ->limit(5)
            ->get();
        
        $view = $this->isMobile() ? 'dashboard-mobile' : 'welcome';
        return view($view, compact(
            'totalSiswa', 'siswaAktif', 'siswaNonAktif',
            'totalHadir', 'totalIzin', 'totalSakit', 'totalAlpa', 'persentaseKehadiran',
            'totalEvaluasi', 'rataRataNilai',
            'chartLabels', 'chartHadir', 'chartTidakHadir',
            'evaluasiTerbaru', 'siswaTopPenilaian'
        ));
    }
}
