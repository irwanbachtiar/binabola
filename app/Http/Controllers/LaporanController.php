<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\EvaluasiSiswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    // Laporan Detail Per Siswa
    public function siswaDetail($id, Request $request)
    {
        $siswa = Siswa::with(['evaluasiSiswas.kategori'])->findOrFail($id);
        
        // Hitung umur
        $umur = Carbon::parse($siswa->tanggal_lahir)->age;
        
        // Statistik Kehadiran (30 hari terakhir)
        $tanggalMulai = Carbon::now()->subDays(30);
        $absensiData = Absensi::where('siswa_id', $id)
            ->where('tanggal', '>=', $tanggalMulai)
            ->orderBy('tanggal')
            ->get();
        
        $statistikKehadiran = [
            'hadir' => $absensiData->where('status', 'Hadir')->count(),
            'izin' => $absensiData->where('status', 'Izin')->count(),
            'sakit' => $absensiData->where('status', 'Sakit')->count(),
            'alpa' => $absensiData->where('status', 'Alpa')->count(),
            'total' => $absensiData->count(),
        ];
        
        if ($statistikKehadiran['total'] > 0) {
            $statistikKehadiran['persentase_hadir'] = round(($statistikKehadiran['hadir'] / $statistikKehadiran['total']) * 100, 1);
        } else {
            $statistikKehadiran['persentase_hadir'] = 0;
        }
        
        // Chart Line - Tren Kehadiran per Minggu (4 minggu terakhir)
        $lineChartData = $this->getKehadiranTrendData($id);
        
        // Evaluasi Data
        $evaluasiData = EvaluasiSiswa::where('siswa_id', $id)
            ->with('kategori')
            ->orderBy('minggu', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Chart Radar - Rata-rata per Kategori
        $radarChartData = $this->getRadarChartData($id);
        
        // Statistik Evaluasi - Filter nilai null untuk perhitungan rata-rata
        $evaluasiValid = $evaluasiData->filter(function($eval) {
            return $eval->nilai !== null;
        });
        
        $statistikEvaluasi = [
            'total_evaluasi' => $evaluasiValid->count(),
            'rata_rata_keseluruhan' => $evaluasiValid->avg('nilai') ? round($evaluasiValid->avg('nilai'), 1) : 0,
            'nilai_tertinggi' => $evaluasiValid->max('nilai') ?? 0,
            'nilai_terendah' => $evaluasiValid->min('nilai') ?? 0,
        ];
        
        // Per Kategori
        $perKategori = $evaluasiData->groupBy('kategori_id')->map(function($items) {
            // Filter nilai null
            $nilaiValid = $items->filter(function($eval) {
                return $eval->nilai !== null;
            });
            
            return [
                'nama' => $items->first()->kategori->nama,
                'rata_rata' => $nilaiValid->isNotEmpty() ? round($nilaiValid->avg('nilai'), 1) : 0,
                'total' => $nilaiValid->count(),
            ];
        })->values();
        
        // Best & Worst Category
        if ($perKategori->isNotEmpty()) {
            $statistikEvaluasi['kategori_terbaik'] = $perKategori->sortByDesc('rata_rata')->first();
            $statistikEvaluasi['kategori_terlemah'] = $perKategori->sortBy('rata_rata')->first();
        }
        
        // Chart Line - Progress Nilai per Minggu
        $progressChartData = $this->getProgressNilaiData($id);
        
        $data = compact(
            'siswa', 
            'umur',
            'absensiData',
            'statistikKehadiran',
            'lineChartData',
            'evaluasiData',
            'radarChartData',
            'statistikEvaluasi',
            'perKategori',
            'progressChartData'
        );
        
        // Jika request PDF
        if ($request->has('pdf')) {
            return $this->generatePDF($data);
        }
        
        return view('laporan.siswa-detail', $data);
    }
    
    private function getKehadiranTrendData($siswaId)
    {
        $data = [];
        $labels = [];
        
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $hadir = Absensi::where('siswa_id', $siswaId)
                ->where('status', 'Hadir')
                ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
                ->count();
            
            $labels[] = 'Minggu ' . (4 - $i);
            $data[] = $hadir;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    private function getRadarChartData($siswaId)
    {
        $kategoris = \App\Models\KategoriPenilaian::where('aktif', true)
            ->orderBy('urutan')
            ->get();
        
        $labels = [];
        $data = [];
        
        foreach ($kategoris as $kategori) {
            // Get all evaluasi for this category
            $evaluasiKategori = EvaluasiSiswa::where('siswa_id', $siswaId)
                ->where('kategori_penilaian_id', $kategori->id)
                ->get();
            
            // Filter: Hanya nilai yang tidak null (nilai 0 karena tidak hadir tetap masuk)
            $nilaiValid = $evaluasiKategori->filter(function($eval) {
                return $eval->nilai !== null;
            });
            
            $avgNilai = $nilaiValid->isNotEmpty() ? round($nilaiValid->avg('nilai'), 1) : 0;
            
            $labels[] = $kategori->nama;
            $data[] = $avgNilai;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    private function getProgressNilaiData($siswaId)
    {
        $evaluasi = EvaluasiSiswa::where('siswa_id', $siswaId)
            ->selectRaw('minggu, AVG(nilai) as avg_nilai')
            ->groupBy('minggu')
            ->orderBy('minggu')
            ->get();
        
        return [
            'labels' => $evaluasi->pluck('minggu')->map(fn($w) => 'Minggu ' . $w)->toArray(),
            'data' => $evaluasi->pluck('avg_nilai')->map(fn($v) => round($v, 1))->toArray()
        ];
    }
    
    private function generatePDF($data)
    {
        $pdf = Pdf::loadView('laporan.siswa-detail-pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'Laporan_' . str_replace(' ', '_', $data['siswa']->nama) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    // Laporan Mingguan (1 bulan)
    // Laporan Summary Bulanan
    public function bulanan(Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));
        $tanggal = Carbon::parse($bulan . '-01');
        
        // Get all active students yang sudah terdaftar sampai bulan ini
        $siswas = Siswa::where('status', 'Aktif')
            ->where(function($query) use ($tanggal) {
                $query->whereNull('tanggal_masuk')
                      ->orWhereDate('tanggal_masuk', '<=', $tanggal->endOfMonth());
            })
            ->orderBy('nama')
            ->get();
        
        // Get all kategori penilaian (parents and children)
        $allKategoris = \App\Models\KategoriPenilaian::orderBy('parent_id')
            ->orderBy('nama')
            ->get();
        
        // Get parent kategoris for U-7 (include minggu_terakhir/mini game)
        $parentKategorisU7 = $allKategoris->whereNull('parent_id')
            ->where('kelompok_umur', 'U-7');
        
        // Get parent kategoris for U-12 (include minggu_terakhir/mini game)
        $parentKategorisU12 = $allKategoris->whereNull('parent_id')
            ->where('kelompok_umur', 'U-12');
        
        // Group laporan data by kelompok_umur
        $laporanDataU7 = [];
        $laporanDataU12 = [];
        
        foreach ($siswas as $siswa) {
            $siswaData = [
                'siswa' => $siswa,
                'nilai_kategori' => [],
                'total_rata' => 0,
            ];
            
            $totalNilai = 0;
            $countKategori = 0;
            
            // Get parent kategoris based on siswa's kelompok_umur
            $relevantParents = $siswa->kelompok_umur === 'U-7' ? $parentKategorisU7 : $parentKategorisU12;
            
            // Get child kategori IDs for relevant parents (semua kategori untuk tampilan)
            $relevantChildIds = [];
            $miniGameChildIds = [];
            foreach ($relevantParents as $parent) {
                $childIds = $allKategoris->where('parent_id', $parent->id)->pluck('id')->toArray();
                $relevantChildIds = array_merge($relevantChildIds, $childIds);
                
                // Track Mini Game category IDs
                if ($parent->minggu_terakhir == 1) {
                    $miniGameChildIds = array_merge($miniGameChildIds, $childIds);
                }
            }
            
            // Process each child kategori (semua kategori untuk tampilan)
            foreach ($relevantChildIds as $childId) {
                // Calculate average for this child category in the selected month
                // Nilai 0 (tidak hadir) TETAP dihitung dalam rata-rata
                $avgNilai = EvaluasiSiswa::where('siswa_id', $siswa->id)
                    ->where('kategori_penilaian_id', $childId)
                    ->whereYear('tanggal_evaluasi', $tanggal->year)
                    ->whereMonth('tanggal_evaluasi', $tanggal->month)
                    ->whereNotNull('nilai')
                    ->avg('nilai');
                
                $siswaData['nilai_kategori'][$childId] = $avgNilai !== null ? round($avgNilai, 1) : '-';
                
                // Logic perhitungan rata-rata total:
                // - Kategori biasa: semua nilai (termasuk 0) masuk perhitungan
                // - Mini Game: hanya nilai > 0 yang masuk perhitungan, nilai 0 atau null di-skip
                if ($avgNilai !== null) {
                    $isMiniGame = in_array($childId, $miniGameChildIds);
                    
                    if ($isMiniGame) {
                        // Mini Game: hanya masuk perhitungan jika nilai > 0
                        if ($avgNilai > 0) {
                            $totalNilai += $avgNilai;
                            $countKategori++;
                        }
                    } else {
                        // Kategori biasa: semua nilai (termasuk 0) masuk perhitungan
                        $totalNilai += $avgNilai;
                        $countKategori++;
                    }
                }
            }
            
            // Calculate total average
            $siswaData['total_rata'] = $countKategori > 0 ? round($totalNilai / $countKategori, 1) : 0;
            
            // Hanya masukkan siswa yang sudah ada data evaluasi di bulan ini
            if ($countKategori > 0) {
                // Group by kelompok_umur
                if ($siswa->kelompok_umur === 'U-7') {
                    $laporanDataU7[] = $siswaData;
                } elseif ($siswa->kelompok_umur === 'U-12') {
                    $laporanDataU12[] = $siswaData;
                }
            }
        }
        
        // Sort by total_rata descending for each group
        usort($laporanDataU7, function($a, $b) {
            return $b['total_rata'] <=> $a['total_rata'];
        });
        
        usort($laporanDataU12, function($a, $b) {
            return $b['total_rata'] <=> $a['total_rata'];
        });
        
        return view('laporan.bulanan', compact('bulan', 'tanggal', 'allKategoris', 'parentKategorisU7', 'parentKategorisU12', 'laporanDataU7', 'laporanDataU12'));
    }

    public function mingguan(Request $request)
    {
        $bulan = $request->input('bulan', date('Y-m'));
        $tanggal = Carbon::parse($bulan . '-01');
        
        $totalSiswa = Siswa::where('status', 'Aktif')->count();
        
        $evaluasiPerMinggu = EvaluasiSiswa::whereYear('tanggal_evaluasi', $tanggal->year)
            ->whereMonth('tanggal_evaluasi', $tanggal->month)
            ->with('siswa', 'kategori')
            ->get()
            ->groupBy('minggu')
            ->map(function($items) {
                // Filter nilai null
                $nilaiValid = $items->filter(function($eval) {
                    return $eval->nilai !== null;
                });
                
                return [
                    'jumlah_siswa' => $items->pluck('siswa_id')->unique()->count(),
                    'rata_rata_nilai' => $nilaiValid->isNotEmpty() ? round($nilaiValid->avg('nilai'), 1) : 0,
                    'nilai_tertinggi' => $nilaiValid->max('nilai') ?? 0,
                    'nilai_terendah' => $nilaiValid->min('nilai') ?? 0,
                    'total_evaluasi' => $nilaiValid->count(),
                ];
            });
        
        $absensi = Absensi::whereYear('tanggal', $tanggal->year)
            ->whereMonth('tanggal', $tanggal->month)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        
        $topSiswa = Siswa::where('status', 'Aktif')
            ->withAvg(['evaluasiSiswas as avg_nilai' => function($q) use ($tanggal) {
                $q->whereYear('tanggal_evaluasi', $tanggal->year)
                  ->whereMonth('tanggal_evaluasi', $tanggal->month);
            }], 'nilai')
            ->orderByDesc('avg_nilai')
            ->limit(5)
            ->get();
        
        return view('laporan.mingguan', compact('bulan', 'tanggal', 'totalSiswa', 'evaluasiPerMinggu', 'absensi', 'topSiswa'));
    }

    // Laporan 3 Bulanan
    public function triwulan(Request $request)
    {
        $triwulan = $request->input('triwulan', ceil(date('n') / 3));
        $tahun = $request->input('tahun', date('Y'));
        
        $bulanAwal = ($triwulan - 1) * 3 + 1;
        $bulanAkhir = $bulanAwal + 2;
        
        $tanggalAwal = Carbon::create($tahun, $bulanAwal, 1);
        $tanggalAkhir = Carbon::create($tahun, $bulanAkhir, 1)->endOfMonth();
        
        $totalSiswa = Siswa::where('status', 'Aktif')->count();
        
        $evaluasiPerBulan = EvaluasiSiswa::whereBetween('tanggal_evaluasi', [$tanggalAwal, $tanggalAkhir])
            ->with('siswa', 'kategori')
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tanggal_evaluasi)->format('Y-m');
            })
            ->map(function($items) {
                // Filter nilai null
                $nilaiValid = $items->filter(function($eval) {
                    return $eval->nilai !== null;
                });
                
                return [
                    'jumlah_siswa' => $items->pluck('siswa_id')->unique()->count(),
                    'rata_rata_nilai' => $nilaiValid->isNotEmpty() ? round($nilaiValid->avg('nilai'), 1) : 0,
                    'nilai_tertinggi' => $nilaiValid->max('nilai') ?? 0,
                    'nilai_terendah' => $nilaiValid->min('nilai') ?? 0,
                    'total_evaluasi' => $nilaiValid->count(),
                ];
            });
        
        $absensi = Absensi::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        
        $topSiswa = Siswa::where('status', 'Aktif')
            ->withAvg(['evaluasiSiswas as avg_nilai' => function($q) use ($tanggalAwal, $tanggalAkhir) {
                $q->whereBetween('tanggal_evaluasi', [$tanggalAwal, $tanggalAkhir]);
            }], 'nilai')
            ->orderByDesc('avg_nilai')
            ->limit(10)
            ->get();
        
        return view('laporan.triwulan', compact('triwulan', 'tahun', 'tanggalAwal', 'tanggalAkhir', 'totalSiswa', 'evaluasiPerBulan', 'absensi', 'topSiswa'));
    }

    // Laporan Tahunan
    public function tahunan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        
        $totalSiswa = Siswa::where('status', 'Aktif')->count();
        
        $evaluasiPerBulan = EvaluasiSiswa::whereYear('tanggal_evaluasi', $tahun)
            ->with('siswa', 'kategori')
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tanggal_evaluasi)->format('Y-m');
            })
            ->map(function($items) {
                return [
                    'jumlah_siswa' => $items->pluck('siswa_id')->unique()->count(),
                    'rata_rata_nilai' => round($items->avg('nilai'), 1),
                    'nilai_tertinggi' => $items->max('nilai'),
                    'nilai_terendah' => $items->min('nilai'),
                    'total_evaluasi' => $items->count(),
                ];
            });
        
        $absensi = Absensi::whereYear('tanggal', $tahun)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        
        $topSiswa = Siswa::where('status', 'Aktif')
            ->withAvg(['evaluasiSiswas as avg_nilai' => function($q) use ($tahun) {
                $q->whereYear('tanggal_evaluasi', $tahun);
            }], 'nilai')
            ->orderByDesc('avg_nilai')
            ->limit(10)
            ->get();
        
        $statistikKategori = EvaluasiSiswa::whereYear('tanggal_evaluasi', $tahun)
            ->with('kategori')
            ->get()
            ->groupBy('kategori_penilaian_id')
            ->map(function($items) {
                // Filter nilai null
                $nilaiValid = $items->filter(function($eval) {
                    return $eval->nilai !== null;
                });
                
                return [
                    'nama' => $items->first()->kategori->nama ?? '-',
                    'rata_rata' => $nilaiValid->isNotEmpty() ? round($nilaiValid->avg('nilai'), 1) : 0,
                    'tertinggi' => $nilaiValid->max('nilai') ?? 0,
                    'terendah' => $nilaiValid->min('nilai') ?? 0,
                ];
            });
        
        return view('laporan.tahunan', compact('tahun', 'totalSiswa', 'evaluasiPerBulan', 'absensi', 'topSiswa', 'statistikKategori'));
    }

    // Laporan Statistik Bulanan PDF
    public function statistikBulananPdf(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));
        $tanggalMulai = Carbon::parse($bulan . '-01')->startOfMonth();
        $tanggalAkhir = Carbon::parse($bulan . '-01')->endOfMonth();
        
        // Ambil semua siswa
        $siswas = Siswa::orderBy('nama')->get()->map(function($siswa) use ($tanggalMulai, $tanggalAkhir) {
            // Kehadiran siswa
            $absensi = Absensi::where('siswa_id', $siswa->id)
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
                ->get();
            
            $hadir = $absensi->where('status', 'Hadir')->count();
            $izin = $absensi->where('status', 'Izin')->count();
            $sakit = $absensi->where('status', 'Sakit')->count();
            $alpa = $absensi->where('status', 'Alpa')->count();
            $total = $absensi->count();
            
            $persentaseHadir = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
            
            // Evaluasi siswa - Filter nilai null
            $evaluasi = EvaluasiSiswa::where('siswa_id', $siswa->id)
                ->whereBetween('tanggal_evaluasi', [$tanggalMulai, $tanggalAkhir])
                ->get();
            
            $evaluasiValid = $evaluasi->filter(function($eval) {
                return $eval->nilai !== null;
            });
            
            $totalEvaluasi = $evaluasiValid->count();
            $rataRataNilai = $totalEvaluasi > 0 ? round($evaluasiValid->avg('nilai'), 1) : 0;
            
            return (object)[
                'nama' => $siswa->nama,
                'umur' => $siswa->umur,
                'kelompok_umur' => $siswa->kelompok_umur,
                'kehadiran' => [
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpa' => $alpa,
                    'total' => $total,
                    'persentase' => $persentaseHadir,
                ],
                'evaluasi' => [
                    'total' => $totalEvaluasi,
                    'rata_rata' => $rataRataNilai,
                ],
            ];
        });
        
        // Summary data
        $totalSiswaAktif = Siswa::where('status', 'Aktif')->count();
        $totalEvaluasi = EvaluasiSiswa::whereBetween('tanggal_evaluasi', [$tanggalMulai, $tanggalAkhir])->count();
        
        $rataRataKehadiran = $siswas->where('kehadiran.total', '>', 0)->avg('kehadiran.persentase');
        $rataRataKehadiran = round($rataRataKehadiran, 1);
        
        $rataRataNilai = $siswas->where('evaluasi.total', '>', 0)->avg('evaluasi.rata_rata');
        $rataRataNilai = round($rataRataNilai, 1);
        
        $bulanText = Carbon::parse($bulan)->locale('id')->isoFormat('MMMM YYYY');
        
        $pdf = Pdf::loadView('laporan.statistik-bulanan-pdf', compact(
            'siswas',
            'bulan',
            'totalSiswaAktif',
            'totalEvaluasi',
            'rataRataKehadiran',
            'rataRataNilai'
        ));
        
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('Laporan-Statistik-Siswa-' . $bulan . '.pdf');
    }}