<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Siswa;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mapping posisi lama ke posisi baru
        $posisiMapping = [
            'Bek Kiri' => 'Belakang',
            'Bek Tengah' => 'Belakang',
            'Gelandang Bertahan' => 'Tengah',
        ];
        
        // Posisi yang akan dihapus
        $posisiToRemove = [
            'Bek Kanan',
            'Gelandang Tengah',
            'Gelandang Serang',
            'Sayap Kiri',
            'Sayap Kanan'
        ];
        
        // Update semua data siswa
        $siswas = Siswa::all();
        
        foreach ($siswas as $siswa) {
            if ($siswa->minat_posisi && is_array($siswa->minat_posisi)) {
                $newPosisi = [];
                
                foreach ($siswa->minat_posisi as $posisi) {
                    // Jika posisi ada di mapping, ganti dengan yang baru
                    if (isset($posisiMapping[$posisi])) {
                        $newPosisi[] = $posisiMapping[$posisi];
                    }
                    // Jika posisi tidak ada di list yang dihapus, pertahankan
                    elseif (!in_array($posisi, $posisiToRemove)) {
                        $newPosisi[] = $posisi;
                    }
                    // Posisi yang di $posisiToRemove akan diabaikan (dihapus)
                }
                
                // Hapus duplikat
                $newPosisi = array_unique($newPosisi);
                
                // Update data siswa
                $siswa->minat_posisi = array_values($newPosisi);
                $siswa->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena data sudah berubah
        // Rollback manual jika diperlukan
    }
};
