<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\EvaluasiSiswa;
use App\Models\KategoriPenilaian;
use Carbon\Carbon;

class CleanupMiniGameSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== Cleaning up Mini Game evaluations ===\n";
        
        // Get all Mini Game categories (with minggu_terakhir flag)
        $miniGameCategories = KategoriPenilaian::with('parent')
            ->get()
            ->filter(function($kategori) {
                return $kategori->parent && $kategori->parent->minggu_terakhir == 1;
            })
            ->pluck('id')
            ->toArray();
        
        if (empty($miniGameCategories)) {
            echo "No Mini Game categories found.\n";
            return;
        }
        
        echo "Found " . count($miniGameCategories) . " Mini Game sub-categories\n";
        
        // Get all evaluations for Mini Game categories
        $evaluasiMiniGame = EvaluasiSiswa::whereIn('kategori_penilaian_id', $miniGameCategories)
            ->whereNotNull('tanggal_evaluasi')
            ->get();
        
        echo "Found " . $evaluasiMiniGame->count() . " Mini Game evaluations\n";
        
        $deletedCount = 0;
        
        foreach ($evaluasiMiniGame as $evaluasi) {
            $tanggal = Carbon::parse($evaluasi->tanggal_evaluasi);
            
            // Check if it's the last Sunday of the month
            $isLastSunday = $this->isLastSundayOfMonth($tanggal);
            
            // If NOT last Sunday, delete this evaluation
            if (!$isLastSunday) {
                echo "Deleting: Siswa #{$evaluasi->siswa_id}, Kategori #{$evaluasi->kategori_penilaian_id}, Tanggal: {$evaluasi->tanggal_evaluasi}\n";
                $evaluasi->delete();
                $deletedCount++;
            }
        }
        
        echo "\n=== Cleanup Complete ===\n";
        echo "Total deleted: {$deletedCount} evaluations\n";
        echo "Remaining: " . ($evaluasiMiniGame->count() - $deletedCount) . " evaluations (last Sunday only)\n";
    }
    
    /**
     * Check if a given date is the last Sunday of its month
     */
    private function isLastSundayOfMonth($date): bool
    {
        $carbon = Carbon::parse($date);
        
        // Check if it's Sunday
        if ($carbon->dayOfWeek !== Carbon::SUNDAY) {
            return false;
        }
        
        // Get the last day of the month
        $lastDayOfMonth = $carbon->copy()->endOfMonth();
        
        // Find the last Sunday of the month
        $lastSunday = $lastDayOfMonth->copy();
        while ($lastSunday->dayOfWeek !== Carbon::SUNDAY) {
            $lastSunday->subDay();
        }
        
        // Compare dates
        return $carbon->isSameDay($lastSunday);
    }
}
