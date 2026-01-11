<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\KategoriPenilaian;
use App\Models\EvaluasiSiswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluasiController extends Controller
{
    // Helper: Detect mobile device
    private function isMobile()
    {
        $userAgent = request()->header('User-Agent');
        return preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);
    }
    
    // Helper: Get minggu ke-berapa dalam bulan (1-4)
    private function getWeekOfMonth($date)
    {
        $timestamp = is_string($date) ? strtotime($date) : $date;
        $dayOfMonth = date('j', $timestamp);
        
        // Hitung minggu ke-berapa: 1-7 = minggu 1, 8-14 = minggu 2, dst
        return ceil($dayOfMonth / 7);
    }
    
    // Helper: Cek apakah tanggal adalah hari Minggu terakhir dalam bulan
    private function isLastSundayOfMonth($date)
    {
        $carbon = \Carbon\Carbon::parse($date);
        
        // Cek apakah hari ini Minggu (0 = Sunday)
        if ($carbon->dayOfWeek !== 0) {
            return false;
        }
        
        // Cari Minggu terakhir di bulan ini
        $lastDayOfMonth = $carbon->copy()->endOfMonth();
        
        // Loop mundur dari akhir bulan sampai ketemu Minggu
        while ($lastDayOfMonth->dayOfWeek !== 0) {
            $lastDayOfMonth->subDay();
        }
        
        // Bandingkan apakah tanggal yang diberikan sama dengan Minggu terakhir
        return $carbon->isSameDay($lastDayOfMonth);
    }
    
    // Daftar siswa untuk evaluasi
    public function index()
    {
        $siswas = Siswa::where('status', 'Aktif')->get();
        
        // Kelompokkan siswa berdasarkan umur
        $siswaU12 = $siswas->filter(function($siswa) {
            return $siswa->kelompok_umur === 'U-12';
        });
        
        $siswaU7 = $siswas->filter(function($siswa) {
            return $siswa->kelompok_umur === 'U-7';
        });
        
        $view = $this->isMobile() ? 'evaluasi.index-mobile' : 'evaluasi.index';
        return view($view, compact('siswaU12', 'siswaU7'));
    }
    
    // Form input evaluasi per siswa
    public function create($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        
        // Get active kategori dengan relationship children, filter berdasarkan kelompok umur siswa
        $kategoris = KategoriPenilaian::where('aktif', true)
            ->where('kelompok_umur', $siswa->kelompok_umur)
            ->with(['children' => function($query) use ($siswa) {
                $query->where('kelompok_umur', $siswa->kelompok_umur);
            }])
            ->orderBy('urutan')
            ->get();
        
        \Log::info('Loading evaluasi form', [
            'siswa_id' => $siswaId,
            'kelompok_umur' => $siswa->kelompok_umur,
            'kategori_count' => $kategoris->count(),
            'kategoris' => $kategoris->map(function($k) {
                return ['id' => $k->id, 'nama' => $k->nama, 'parent_id' => $k->parent_id, 'kelompok_umur' => $k->kelompok_umur];
            })
        ]);
        
        // Get current week and year
        $currentWeek = date('W');
        $currentYear = date('Y');
        
        // Check if already evaluated this week
        $existingEvaluasi = EvaluasiSiswa::where('siswa_id', $siswaId)
            ->where('minggu', $currentWeek)
            ->where('tahun', $currentYear)
            ->pluck('nilai', 'kategori_penilaian_id')
            ->toArray();
        
        // If no evaluation this week, get last week's values
        if (empty($existingEvaluasi)) {
            $lastEvaluasi = EvaluasiSiswa::where('siswa_id', $siswaId)
                ->whereIn('tahun', [$currentYear, $currentYear - 1])
                ->orderBy('tahun', 'desc')
                ->orderBy('minggu', 'desc')
                ->get()
                ->pluck('nilai', 'kategori_penilaian_id')
                ->toArray();
            
            // Use last evaluation values as default
            if (!empty($lastEvaluasi)) {
                $existingEvaluasi = $lastEvaluasi;
            }
        }
        
        // Get all evaluations for charts (current and previous year)
        $evaluasi = EvaluasiSiswa::where('siswa_id', $siswaId)
            ->whereIn('tahun', [$currentYear, $currentYear - 1])
            ->with('kategori')
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->get();
        
        \Log::info('Evaluasi data loaded', [
            'siswa_id' => $siswaId,
            'total_records' => $evaluasi->count(),
            'records' => $evaluasi->map(function($e) {
                return [
                    'minggu' => $e->minggu,
                    'kategori' => $e->kategori->nama,
                    'nilai' => $e->nilai,
                    'tanggal' => $e->tanggal_evaluasi
                ];
            })
        ]);
        
        // Prepare data for line chart (weekly progress) - Multiple charts per parent
        // Get parent dan sub kategori secara eksplisit dengan relationship
        $allKategoris = KategoriPenilaian::where('aktif', true)
            ->where('kelompok_umur', $siswa->kelompok_umur)
            ->with('parent')
            ->get();
        
        $parentKategoris = $allKategoris->whereNull('parent_id');
        $subKategoris = $allKategoris->whereNotNull('parent_id');
        $weeks = $evaluasi->pluck('minggu')->unique()->sort()->values();
        $lineChartData = [];
        
        // Untuk setiap parent, siapkan data chart terpisah
        foreach ($parentKategoris as $parent) {
            $chartData = [];
            
            // Get all sub-kategori dari parent ini
            $subs = $subKategoris->where('parent_id', $parent->id);
            
            // Untuk setiap sub-kategori, buat dataset
            foreach ($subs as $sub) {
                $chartData[$sub->nama] = [];
                
                foreach ($weeks as $week) {
                    // Ambil nilai untuk sub-kategori ini di minggu ini
                    $nilai = $evaluasi->where('minggu', $week)
                        ->where('kategori_penilaian_id', $sub->id)
                        ->first();
                    
                    $chartData[$sub->nama][] = $nilai ? $nilai->nilai : null;
                }
            }
            
            $lineChartData[$parent->nama] = $chartData;
        }
        
        // Prepare data for radar chart (average of all evaluations per category)
        // Filter: hanya sub-kategori yang parent-nya TIDAK memiliki flag minggu_terakhir
        $radarChartData = [];
        
        // Calculate average untuk sub-kategori yang parent-nya bukan minggu_terakhir
        foreach ($subKategoris as $kategori) {
            // Skip jika parent memiliki flag minggu_terakhir = 1/true
            // Menggunakan loaded parent relationship untuk akurasi
            if ($kategori->parent && $kategori->parent->minggu_terakhir == 1) {
                continue;
            }
            
            if ($evaluasi->count() > 0) {
                // Get all evaluations for this category and calculate average
                // Nilai 0 (tidak hadir) TETAP dihitung dalam rata-rata
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
                // If no evaluation yet, show 0 for all categories
                $radarChartData[$kategori->nama] = 0;
            }
        }
        
        // Cek absensi hari ini untuk notifikasi
        $absensiHariIni = Absensi::where('siswa_id', $siswaId)
            ->whereDate('tanggal', date('Y-m-d'))
            ->first();
        
        // Deteksi minggu ke-berapa dalam bulan (1-4)
        $weekOfMonth = $this->getWeekOfMonth(date('Y-m-d'));
        
        // Deteksi apakah hari Minggu terakhir dalam bulan
        $isLastSunday = $this->isLastSundayOfMonth(date('Y-m-d'));
        
        $view = $this->isMobile() ? 'evaluasi.create-mobile' : 'evaluasi.create';
        return view($view, compact('siswa', 'kategoris', 'currentWeek', 'currentYear', 'existingEvaluasi', 'evaluasi', 'weeks', 'lineChartData', 'radarChartData', 'absensiHariIni', 'weekOfMonth', 'isLastSunday'));
    }
    
    // Simpan evaluasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal_evaluasi' => 'required|date',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
            'dinilai_oleh' => 'required|string|max:255',
        ]);
        
        // Cek absensi siswa pada tanggal evaluasi
        $absensi = Absensi::where('siswa_id', $validated['siswa_id'])
            ->whereDate('tanggal', $validated['tanggal_evaluasi'])
            ->first();
        
        $statusAbsensi = $absensi ? $absensi->status : 'Belum diabsen';
        
        // Jika tidak hadir (status bukan "Hadir"), set semua nilai ke 0
        $isHadir = $absensi && $absensi->status === 'Hadir';
        
        \Log::info('Menyimpan evaluasi', [
            'siswa_id' => $validated['siswa_id'],
            'tanggal' => $validated['tanggal_evaluasi'],
            'status_absensi' => $statusAbsensi,
            'is_hadir' => $isHadir
        ]);
        
        // Calculate minggu and tahun from tanggal_evaluasi
        $tanggal = \Carbon\Carbon::parse($validated['tanggal_evaluasi']);
        $minggu = $tanggal->week;
        $tahun = $tanggal->year;
        $weekOfMonth = $this->getWeekOfMonth($validated['tanggal_evaluasi']);
        $isLastSunday = $this->isLastSundayOfMonth($validated['tanggal_evaluasi']);
        
        \Log::info('Evaluasi metadata', [
            'minggu_tahun' => $minggu,
            'week_of_month' => $weekOfMonth,
            'is_last_sunday' => $isLastSunday
        ]);
        
        // Get siswa untuk cek kelompok_umur
        $siswa = Siswa::findOrFail($validated['siswa_id']);
        
        // Load kategori untuk cek flag minggu_terakhir DAN kelompok_umur
        // Jika siswa tidak hadir, load SEMUA kategori aktif untuk kelompok umurnya
        if (!$isHadir) {
            $allKategoris = KategoriPenilaian::where('aktif', true)
                ->where('kelompok_umur', $siswa->kelompok_umur)
                ->whereNotNull('parent_id') // Hanya sub-kategori
                ->with('parent')
                ->get();
        } else {
            // Jika hadir, hanya load kategori yang ada di form
            $allKategoris = KategoriPenilaian::whereIn('id', array_keys($request->nilai ?? []))
                ->with('parent')
                ->get();
        }
        
        DB::beginTransaction();
        try {
            // Delete existing evaluations for this date
            EvaluasiSiswa::where('siswa_id', $validated['siswa_id'])
                ->where('tanggal_evaluasi', $validated['tanggal_evaluasi'])
                ->delete();
            
            // Insert new evaluations
            // Jika tidak hadir, loop semua kategori yang sesuai
            $kategoriLoop = !$isHadir ? $allKategoris : collect($request->nilai ?? [])->keys();
            
            foreach ($kategoriLoop as $kategoriKey) {
                // Ambil kategori ID dan nilai
                if (!$isHadir) {
                    // Dari collection kategori
                    $kategoriId = $kategoriKey->id;
                    $nilai = null; // Akan di-set otomatis berdasarkan jenis kategori
                    $kategori = $kategoriKey;
                } else {
                    // Dari request nilai
                    $kategoriId = $kategoriKey;
                    $nilai = $request->nilai[$kategoriId];
                    $kategori = $allKategoris->firstWhere('id', $kategoriId);
                }
                
                // Skip jika kategori tidak ditemukan
                if (!$kategori) {
                    \Log::warning('Kategori tidak ditemukan', ['kategori_id' => $kategoriId]);
                    continue;
                }
                
                // Skip Mini Game (minggu_terakhir) jika bukan minggu terakhir
                if ($kategori && $kategori->parent && $kategori->parent->minggu_terakhir == 1 && !$isLastSunday) {
                    \Log::info('Skipping Mini Game category (not last Sunday)', [
                        'kategori_id' => $kategoriId,
                        'is_last_sunday' => $isLastSunday
                    ]);
                    continue;
                }
                
                // Jika siswa tidak hadir
                if (!$isHadir) {
                    // Untuk kategori minggu_terakhir (Mini Game), SKIP - tidak simpan record
                    if ($kategori->parent && $kategori->parent->minggu_terakhir == 1) {
                        \Log::info('Mini Game di-skip karena siswa tidak hadir', [
                            'kategori_id' => $kategoriId,
                            'status' => $statusAbsensi
                        ]);
                        continue; // Skip, tidak simpan record
                    }
                    // Untuk kategori biasa, set 0
                    $nilaiAkhir = 0;
                    $catatanAkhir = "Tidak hadir ({$statusAbsensi}) - Nilai otomatis 0";
                } else {
                    // Skip jika nilai null atau kosong (untuk kategori yang tidak diisi)
                    if ($nilai === null || $nilai === '') {
                        continue;
                    }
                    $nilaiAkhir = $nilai;
                    $catatanAkhir = $validated['catatan'];
                }
                
                $created = EvaluasiSiswa::create([
                    'siswa_id' => $validated['siswa_id'],
                    'kategori_penilaian_id' => $kategoriId,
                    'minggu' => $minggu,
                    'tahun' => $tahun,
                    'tanggal_evaluasi' => $validated['tanggal_evaluasi'],
                    'nilai' => $nilaiAkhir,
                    'catatan' => $catatanAkhir,
                    'dinilai_oleh' => $validated['dinilai_oleh'],
                ]);
                
                \Log::info('Evaluasi disimpan', [
                    'id' => $created->id,
                    'kategori_id' => $kategoriId,
                    'nilai_input' => $nilai,
                    'nilai_akhir' => $nilaiAkhir,
                    'is_hadir' => $isHadir
                ]);
            }
            
            DB::commit();
            
            // Return JSON for AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                $message = $isHadir 
                    ? 'Evaluasi tanggal ' . $tanggal->format('d/m/Y') . ' berhasil disimpan!'
                    : 'Evaluasi disimpan dengan nilai 0 (Status: ' . $statusAbsensi . ')';
                    
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'warning' => !$isHadir ? 'Siswa tidak hadir, semua nilai diset 0' : null
                ]);
            }
            
            return redirect()->route('evaluasi.show', $validated['siswa_id'])
                ->with('success', 'Evaluasi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan evaluasi: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menyimpan evaluasi: ' . $e->getMessage());
        }
    }
    
    // Detail progress siswa dengan grafik
    public function show($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        
        // Get evaluations for the last 12 weeks (current and previous year)
        $currentYear = date('Y');
        $evaluasi = EvaluasiSiswa::where('siswa_id', $siswaId)
            ->whereIn('tahun', [$currentYear, $currentYear - 1])
            ->with('kategori')
            ->orderBy('tahun', 'desc')
            ->orderBy('minggu', 'desc')
            ->get();
        
        // Prepare data for line chart (weekly progress) - Multiple charts per parent
        $allKategoris = KategoriPenilaian::where('aktif', true)
            ->where('kelompok_umur', $siswa->kelompok_umur)
            ->with('parent')
            ->get();
        
        $parentKategoris = $allKategoris->whereNull('parent_id');
        $subKategoris = $allKategoris->whereNotNull('parent_id');
        $weeks = $evaluasi->pluck('minggu')->unique()->sort()->values();
        $lineChartData = [];
        
        // Untuk setiap parent, siapkan data chart terpisah
        foreach ($parentKategoris as $parent) {
            $chartData = [];
            
            // Get all sub-kategori dari parent ini
            $subs = $subKategoris->where('parent_id', $parent->id);
            
            // Untuk setiap sub-kategori, buat dataset
            foreach ($subs as $sub) {
                $chartData[$sub->nama] = [];
                
                foreach ($weeks as $week) {
                    // Ambil nilai untuk sub-kategori ini di minggu ini
                    $nilai = $evaluasi->where('minggu', $week)
                        ->where('kategori_penilaian_id', $sub->id)
                        ->first();
                    
                    $chartData[$sub->nama][] = $nilai ? $nilai->nilai : null;
                }
            }
            
            $lineChartData[$parent->nama] = $chartData;
        }
        
        // Prepare data for radar chart (average of all evaluations per sub-category)
        // Filter: hanya sub-kategori yang parent-nya TIDAK memiliki flag minggu_terakhir
        $radarChartData = [];
        
        foreach ($subKategoris as $kategori) {
            // Skip jika parent memiliki flag minggu_terakhir = 1/true
            // Menggunakan loaded parent relationship untuk akurasi
            if ($kategori->parent && $kategori->parent->minggu_terakhir == 1) {
                continue;
            }
            
            if ($evaluasi->count() > 0) {
                // Get all evaluations for this category and calculate average
                // Nilai 0 (tidak hadir) MASUK dalam perhitungan rata-rata
                $nilaiKategori = $evaluasi->where('kategori_penilaian_id', $kategori->id)
                    ->filter(function($eval) {
                        // Skip jika nilai null (Mini Game saat tidak hadir)
                        return $eval->nilai !== null;
                    });
                if ($nilaiKategori->count() > 0) {
                    $radarChartData[$kategori->nama] = round($nilaiKategori->avg('nilai'), 1);
                } else {
                    $radarChartData[$kategori->nama] = 0;
                }
            } else {
                // If no evaluation yet, show 0 for all categories
                $radarChartData[$kategori->nama] = 0;
            }
        }
        
        // Prepare data for monthly average chart
        // Get evaluasi dari 12 bulan terakhir
        $monthlyData = [];
        $monthlyAverages = [];
        
        if ($evaluasi->count() > 0) {
            // Group evaluasi by year-month dari tanggal_evaluasi
            $evaluasiByMonth = $evaluasi->filter(function($item) {
                return !empty($item->tanggal_evaluasi);
            })->groupBy(function($item) {
                return date('Y-m', strtotime($item->tanggal_evaluasi));
            })->sortKeys();
            
            // Ambil 12 bulan terakhir
            $lastMonths = collect();
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $key = $date->format('Y-m');
                $lastMonths->put($key, $date->format('M Y'));
            }
            
            // Calculate average per bulan (exclude kategori dengan minggu_terakhir)
            foreach ($lastMonths as $monthKey => $monthLabel) {
                $monthlyData[$monthKey] = $monthLabel;
                
                if (isset($evaluasiByMonth[$monthKey])) {
                    $evaluasiInMonth = $evaluasiByMonth[$monthKey];
                    
                    // Filter hanya kategori yang parent-nya bukan minggu_terakhir
                    // Nilai 0 (tidak hadir) MASUK dalam perhitungan rata-rata
                    $filteredEvaluasi = $evaluasiInMonth->filter(function($eval) use ($subKategoris) {
                        $kategori = $subKategoris->firstWhere('id', $eval->kategori_penilaian_id);
                        if ($kategori && $kategori->parent) {
                            // Exclude kategori dengan flag minggu_terakhir
                            if ($kategori->parent->minggu_terakhir == 1) {
                                return false;
                            }
                            // Skip hanya nilai null (Mini Game saat tidak hadir)
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
        
        // Get statistik kehadiran siswa
        $totalLatihan = Absensi::where('siswa_id', $siswaId)->count();
        $totalHadir = Absensi::where('siswa_id', $siswaId)->where('status', 'Hadir')->count();
        $totalIzin = Absensi::where('siswa_id', $siswaId)->where('status', 'Izin')->count();
        $totalSakit = Absensi::where('siswa_id', $siswaId)->where('status', 'Sakit')->count();
        $totalAlpa = Absensi::where('siswa_id', $siswaId)->where('status', 'Alpa')->count();
        $persenKehadiran = $totalLatihan > 0 ? round(($totalHadir / $totalLatihan) * 100, 1) : 0;
        
        // Get histori ketidakhadiran (Izin, Sakit, Alpa)
        $historiTidakHadir = Absensi::where('siswa_id', $siswaId)
            ->whereIn('status', ['Izin', 'Sakit', 'Alpa'])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return view('evaluasi.show', compact('siswa', 'allKategoris', 'weeks', 'lineChartData', 'radarChartData', 'evaluasi', 'monthlyData', 'monthlyAverages', 'totalLatihan', 'totalHadir', 'totalIzin', 'totalSakit', 'totalAlpa', 'persenKehadiran', 'historiTidakHadir'));
    }
    
    // Get evaluasi by week (for edit function)
    public function getWeek($siswaId, $minggu)
    {
        $currentYear = date('Y');
        $evaluasi = EvaluasiSiswa::where('siswa_id', $siswaId)
            ->where('minggu', $minggu)
            ->where('tahun', $currentYear)
            ->get();
        
        return response()->json($evaluasi);
    }
    
    // Delete evaluasi by week
    public function delete($siswaId, $minggu)
    {
        $currentYear = date('Y');
        EvaluasiSiswa::where('siswa_id', $siswaId)
            ->where('minggu', $minggu)
            ->where('tahun', $currentYear)
            ->delete();
        
        return redirect()->back()->with('success', 'Evaluasi minggu ' . $minggu . ' berhasil dihapus!');
    }
    
    // Batch evaluation - Form
    public function batchIndex()
    {
        return view('evaluasi.batch');
    }
    
    // Get siswa yang hadir pada tanggal tertentu
    public function getSiswaByTanggal(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $kelompokUmur = $request->input('kelompok_umur'); // Parameter filter kelompok umur
        $currentYear = date('Y');
        
        // Calculate minggu from tanggal
        $mingguFromDate = date('W', strtotime($tanggal));
        $tahunFromDate = date('Y', strtotime($tanggal));
        
        // Check apakah tanggal ini sudah ada evaluasi
        $existingEvaluasi = EvaluasiSiswa::whereDate('tanggal_evaluasi', $tanggal)
            ->count();
        
        $sudahDinilai = $existingEvaluasi > 0;
        
        // Get semua siswa yang ada absensi pada tanggal tersebut
        $absensis = Absensi::where('tanggal', $tanggal)
            ->with('siswa')
            ->get();
        
        $siswa = $absensis->map(function($absensi) use ($currentYear) {
            // Get nilai evaluasi terakhir untuk siswa ini
            $lastEvaluasi = EvaluasiSiswa::where('siswa_id', $absensi->siswa_id)
                ->whereIn('tahun', [$currentYear, $currentYear - 1])
                ->orderBy('tahun', 'desc')
                ->orderBy('minggu', 'desc')
                ->get()
                ->pluck('nilai', 'kategori_penilaian_id')
                ->toArray();
            
            return [
                'id' => $absensi->siswa->id,
                'nama' => $absensi->siswa->nama,
                'kelompok_umur' => $absensi->siswa->kelompok_umur, // This is an accessor
                'status_absensi' => $absensi->status,
                'last_nilai' => $lastEvaluasi
            ];
        });
        
        // Filter berdasarkan kelompok_umur jika ada (filter di PHP level karena kelompok_umur adalah accessor)
        if ($kelompokUmur) {
            $siswa = $siswa->filter(function($s) use ($kelompokUmur) {
                return $s['kelompok_umur'] === $kelompokUmur;
            })->values(); // Re-index array
        }
        
        // Deteksi kelompok umur mayoritas dari siswa yang hadir (atau gunakan filter jika ada)
        $kelompokUmurMajority = $kelompokUmur ?: ($siswa->pluck('kelompok_umur')->countBy()->sortDesc()->keys()->first() ?? 'U-12');
        
        // Get all active kategori dengan children berdasarkan kelompok umur mayoritas
        $allKategoris = KategoriPenilaian::where('aktif', true)
            ->where('kelompok_umur', $kelompokUmurMajority)
            ->with(['children' => function($query) use ($kelompokUmurMajority) {
                $query->where('kelompok_umur', $kelompokUmurMajority);
            }])
            ->orderBy('urutan')
            ->get();
        
        // Deteksi minggu ke-berapa dalam bulan
        $weekOfMonth = $this->getWeekOfMonth($tanggal);
        
        // Deteksi apakah hari Minggu terakhir
        $isLastSunday = $this->isLastSundayOfMonth($tanggal);
        
        // Filter hanya sub-kategori (yang memiliki parent_id)
        $kategoris = $allKategoris->where('parent_id', '!=', null);
        
        // Untuk response, include struktur parent untuk display
        $kategoriStructure = [];
        $parents = $allKategoris->where('parent_id', null);
        foreach ($parents as $parent) {
            // Skip kategori minggu terakhir jika bukan hari Minggu terakhir
            if ($parent->minggu_terakhir && !$isLastSunday) {
                continue;
            }
            
            $kategoriStructure[] = [
                'id' => $parent->id,
                'nama' => $parent->nama,
                'is_parent' => true,
                'minggu_terakhir' => $parent->minggu_terakhir,
                'children' => $parent->children->map(function($child) {
                    return [
                        'id' => $child->id,
                        'nama' => $child->nama,
                        'is_parent' => false
                    ];
                })->toArray()
            ];
        }
        
        return response()->json([
            'siswa' => $siswa,
            'kategoris' => $kategoris, // Sub-kategori untuk input
            'kategori_structure' => $kategoriStructure, // Struktur hierarki untuk display
            'sudah_dinilai' => $sudahDinilai,
            'jumlah_evaluasi' => $existingEvaluasi,
            'tanggal' => $tanggal,
            'week_of_month' => $weekOfMonth,
            'is_last_sunday' => $isLastSunday
        ]);
    }
    
    // Store batch evaluation
    public function batchStore(Request $request)
    {
        try {
            \Log::info('Batch evaluation started', [
                'request_data' => $request->all()
            ]);
            
            $request->validate([
                'tanggal_evaluasi' => 'required|date',
                'minggu' => 'required|integer|min:1|max:53',
                'siswa_ids' => 'required|array',
                'nilai' => 'required|array'
            ]);
            
            $tanggalEvaluasi = $request->input('tanggal_evaluasi');
            $minggu = $request->input('minggu');
            $tahun = date('Y', strtotime($tanggalEvaluasi));
            $siswaIds = $request->input('siswa_ids');
            $nilaiData = $request->input('nilai');
            
            // Cek apakah tanggal evaluasi adalah minggu terakhir bulan
            $isLastSunday = $this->isLastSundayOfMonth($tanggalEvaluasi);
            
            \Log::info('Batch evaluation processing', [
                'tanggal' => $tanggalEvaluasi,
                'minggu' => $minggu,
                'tahun' => $tahun,
                'siswa_count' => count($siswaIds),
                'is_last_sunday' => $isLastSunday
            ]);
            
            DB::beginTransaction();
            
            try {
                $totalSaved = 0;
                $skippedCount = 0;
                
                foreach ($siswaIds as $siswaId) {
                    // Get siswa untuk cek kelompok_umur
                    $siswa = Siswa::find($siswaId);
                    if (!$siswa) {
                        \Log::warning('Siswa tidak ditemukan', ['siswa_id' => $siswaId]);
                        continue;
                    }
                    
                    // Cek absensi siswa pada tanggal evaluasi
                    $absensi = Absensi::where('siswa_id', $siswaId)
                        ->whereDate('tanggal', $tanggalEvaluasi)
                        ->first();
                    
                    $statusAbsensi = $absensi ? $absensi->status : 'Belum diabsen';
                    $isHadir = $absensi && $absensi->status === 'Hadir';
                    
                    // Load all kategori untuk batch ini dengan parent relationship
                    // Jika siswa tidak hadir dan tidak ada data nilai, load semua kategori aktif
                    if (!$isHadir && !isset($nilaiData[$siswaId])) {
                        // Load semua kategori aktif untuk kelompok umur siswa
                        $allKategoris = KategoriPenilaian::where('aktif', true)
                            ->where('kelompok_umur', $siswa->kelompok_umur)
                            ->whereNotNull('parent_id') // Hanya sub-kategori
                            ->with('parent')
                            ->get();
                        
                        // Buat nilaiData dummy agar loop di bawah berjalan
                        $nilaiData[$siswaId] = [];
                        foreach ($allKategoris as $kat) {
                            $nilaiData[$siswaId][$kat->id] = null; // Will be set to 0 or null later
                        }
                    } else if (!isset($nilaiData[$siswaId])) {
                        // Jika hadir tapi tidak ada data nilai, skip siswa ini
                        continue;
                    } else {
                        // Load kategori yang ada di form
                        $kategoriIds = array_keys($nilaiData[$siswaId]);
                        $allKategoris = KategoriPenilaian::whereIn('id', $kategoriIds)
                            ->with('parent')
                            ->get();
                    }
                    
                    foreach ($nilaiData[$siswaId] as $kategoriId => $nilai) {
                        $kategori = $allKategoris->firstWhere('id', $kategoriId);
                        
                        // Skip jika kategori tidak ditemukan
                        if (!$kategori) {
                            \Log::warning('Kategori tidak ditemukan', ['kategori_id' => $kategoriId]);
                            $skippedCount++;
                            continue;
                        }
                        
                        // PENTING: Skip jika kelompok_umur kategori tidak sesuai dengan siswa
                        if ($kategori->kelompok_umur !== $siswa->kelompok_umur) {
                            \Log::warning('Skip kategori - kelompok umur tidak sesuai', [
                                'siswa_id' => $siswaId,
                                'kategori_id' => $kategoriId,
                                'kategori_kelompok' => $kategori->kelompok_umur,
                                'siswa_kelompok' => $siswa->kelompok_umur
                            ]);
                            $skippedCount++;
                            continue;
                        }
                        
                        // Cek parent kategori juga harus sesuai kelompok umur
                        if ($kategori->parent && $kategori->parent->kelompok_umur !== $siswa->kelompok_umur) {
                            \Log::warning('Skip kategori - parent kelompok umur tidak sesuai', [
                                'siswa_id' => $siswaId,
                                'kategori_id' => $kategoriId,
                                'parent_kelompok' => $kategori->parent->kelompok_umur,
                                'siswa_kelompok' => $siswa->kelompok_umur
                            ]);
                            $skippedCount++;
                            continue;
                        }
                        
                        // Skip Mini Game (minggu_terakhir) jika bukan minggu terakhir
                        if ($kategori->parent && $kategori->parent->minggu_terakhir == 1 && !$isLastSunday) {
                            \Log::info('Skipping Mini Game category (not last Sunday)', [
                                'siswa_id' => $siswaId,
                                'kategori_id' => $kategoriId,
                                'is_last_sunday' => $isLastSunday
                            ]);
                            $skippedCount++;
                            continue;
                        }
                        
                        // Tentukan nilai akhir berdasarkan status kehadiran
                        $nilaiAkhir = $nilai;
                        $catatanAkhir = null;
                        
                        if (!$isHadir) {
                            // Untuk kategori minggu_terakhir (Mini Game), SKIP - tidak simpan record
                            if ($kategori->parent && $kategori->parent->minggu_terakhir == 1) {
                                \Log::info('Mini Game di-skip karena siswa tidak hadir', [
                                    'siswa_id' => $siswaId,
                                    'kategori_id' => $kategoriId,
                                    'status' => $statusAbsensi
                                ]);
                                $skippedCount++;
                                continue; // Skip, tidak simpan record
                            }
                            // Untuk kategori biasa, gunakan 0 (dari form atau set manual)
                            $nilaiAkhir = 0;
                            $catatanAkhir = "Tidak hadir ({$statusAbsensi}) - Nilai otomatis 0";
                        } else {
                            // Jika hadir, skip jika nilai kosong string (bukan 0)
                            // 0 adalah nilai valid yang harus disimpan
                            if ($nilai === null || $nilai === '') {
                                continue;
                            }
                        }
                        
                        // Check if evaluation already exists
                        $existing = EvaluasiSiswa::where('siswa_id', $siswaId)
                            ->where('kategori_penilaian_id', $kategoriId)
                            ->where('minggu', $minggu)
                            ->where('tahun', $tahun)
                            ->first();
                        
                        if ($existing) {
                            // Update existing
                            $existing->update([
                                'nilai' => $nilaiAkhir,
                                'tanggal_evaluasi' => $tanggalEvaluasi,
                                'catatan' => $catatanAkhir,
                                'dinilai_oleh' => auth()->user()->name ?? 'System'
                            ]);
                            \Log::info('Evaluasi updated', [
                                'id' => $existing->id,
                                'siswa_id' => $siswaId,
                                'kategori_id' => $kategoriId,
                                'nilai' => $nilaiAkhir
                            ]);
                        } else {
                            // Create new
                            $created = EvaluasiSiswa::create([
                                'siswa_id' => $siswaId,
                                'kategori_penilaian_id' => $kategoriId,
                                'minggu' => $minggu,
                                'tahun' => $tahun,
                                'nilai' => $nilaiAkhir,
                                'tanggal_evaluasi' => $tanggalEvaluasi,
                                'catatan' => $catatanAkhir,
                                'dinilai_oleh' => auth()->user()->name ?? 'System'
                            ]);
                            \Log::info('Evaluasi created', [
                                'id' => $created->id,
                                'siswa_id' => $siswaId,
                                'kategori_id' => $kategoriId,
                                'nilai' => $nilaiAkhir
                            ]);
                        }
                        
                        $totalSaved++;
                    }
                }
                
                DB::commit();
                
                \Log::info('Batch evaluation success', [
                    'total_saved' => $totalSaved,
                    'skipped' => $skippedCount,
                    'siswa_count' => count($siswaIds)
                ]);
                
                $message = "Berhasil menyimpan evaluasi untuk " . count($siswaIds) . " siswa! (Total: {$totalSaved} data tersimpan)";
                if ($skippedCount > 0) {
                    $message .= " ({$skippedCount} data di-skip karena tidak sesuai kriteria)";
                }
                
                return redirect()->route('evaluasi.batch')
                    ->with('success', $message);
                    
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Batch evaluation DB error: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan saat menyimpan evaluasi: ' . $e->getMessage())
                    ->withInput();
            }
            
        } catch (\Exception $e) {
            \Log::error('Batch evaluation fatal error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())
                ->withInput();
        }
    }
}
