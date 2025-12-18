<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\KategoriPenilaian;
use App\Models\EvaluasiSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluasiController extends Controller
{
    // Daftar siswa untuk evaluasi
    public function index()
    {
        $siswas = Siswa::where('status', 'Aktif')->get();
        return view('evaluasi.index', compact('siswas'));
    }
    
    // Form input evaluasi per siswa
    public function create($siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        $kategoris = KategoriPenilaian::where('aktif', true)->orderBy('urutan')->get();
        
        // Get current week and year
        $currentWeek = date('W');
        $currentYear = date('Y');
        
        // Check if already evaluated this week
        $existingEvaluasi = EvaluasiSiswa::where('siswa_id', $siswaId)
            ->where('minggu', $currentWeek)
            ->where('tahun', $currentYear)
            ->pluck('nilai', 'kategori_penilaian_id')
            ->toArray();
        
        // Get all evaluations for charts
        $evaluasi = EvaluasiSiswa::where('siswa_id', $siswaId)
            ->where('tahun', $currentYear)
            ->with('kategori')
            ->orderBy('minggu', 'asc')
            ->get();
        
        // Prepare data for line chart (weekly progress)
        $weeks = $evaluasi->pluck('minggu')->unique()->sort()->values();
        $lineChartData = [];
        foreach ($kategoris as $kategori) {
            $lineChartData[$kategori->nama] = [];
            foreach ($weeks as $week) {
                $nilai = $evaluasi->where('minggu', $week)
                    ->where('kategori_penilaian_id', $kategori->id)
                    ->first();
                $lineChartData[$kategori->nama][] = $nilai ? $nilai->nilai : null;
            }
        }
        
        // Prepare data for radar chart (latest week average)
        $latestWeek = $weeks->last() ?? date('W');
        $radarChartData = [];
        foreach ($kategoris as $kategori) {
            $nilai = $evaluasi->where('minggu', $latestWeek)
                ->where('kategori_penilaian_id', $kategori->id)
                ->first();
            $radarChartData[$kategori->nama] = $nilai ? $nilai->nilai : 0;
        }
        
        return view('evaluasi.create', compact('siswa', 'kategoris', 'currentWeek', 'currentYear', 'existingEvaluasi', 'evaluasi', 'weeks', 'lineChartData', 'radarChartData'));
    }
    
    // Simpan evaluasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'minggu' => 'required|integer',
            'tahun' => 'required|integer',
            'nilai.*' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
            'dinilai_oleh' => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            // Delete existing evaluations for this week
            EvaluasiSiswa::where('siswa_id', $validated['siswa_id'])
                ->where('minggu', $validated['minggu'])
                ->where('tahun', $validated['tahun'])
                ->delete();
            
            // Insert new evaluations
            foreach ($request->nilai as $kategoriId => $nilai) {
                EvaluasiSiswa::create([
                    'siswa_id' => $validated['siswa_id'],
                    'kategori_penilaian_id' => $kategoriId,
                    'minggu' => $validated['minggu'],
                    'tahun' => $validated['tahun'],
                    'nilai' => $nilai,
                    'catatan' => $validated['catatan'],
                    'dinilai_oleh' => $validated['dinilai_oleh'],
                ]);
            }
            
            DB::commit();
            
            // Return JSON for AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Evaluasi minggu ' . $validated['minggu'] . ' berhasil disimpan!'
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
        $kategoris = KategoriPenilaian::where('aktif', true)->orderBy('urutan')->get();
        
        // Get evaluations for the last 12 weeks
        $currentYear = date('Y');
        $evaluasi = EvaluasiSiswa::where('siswa_id', $siswaId)
            ->where('tahun', $currentYear)
            ->with('kategori')
            ->orderBy('minggu', 'asc')
            ->get();
        
        // Prepare data for line chart (weekly progress)
        $weeks = $evaluasi->pluck('minggu')->unique()->sort()->values();
        $lineChartData = [];
        foreach ($kategoris as $kategori) {
            $lineChartData[$kategori->nama] = [];
            foreach ($weeks as $week) {
                $nilai = $evaluasi->where('minggu', $week)
                    ->where('kategori_penilaian_id', $kategori->id)
                    ->first();
                $lineChartData[$kategori->nama][] = $nilai ? $nilai->nilai : null;
            }
        }
        
        // Prepare data for radar chart (latest week average)
        $latestWeek = $weeks->last() ?? date('W');
        $radarChartData = [];
        foreach ($kategoris as $kategori) {
            $nilai = $evaluasi->where('minggu', $latestWeek)
                ->where('kategori_penilaian_id', $kategori->id)
                ->first();
            $radarChartData[$kategori->nama] = $nilai ? $nilai->nilai : 0;
        }
        
        return view('evaluasi.show', compact('siswa', 'kategoris', 'weeks', 'lineChartData', 'radarChartData', 'evaluasi'));
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
}
