<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nonaktifkan foreign key check sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data evaluasi lama (karena kategori akan berubah total)
        DB::table('evaluasi_siswas')->truncate();
        
        // Hapus semua data kategori lama
        DB::table('kategori_penilaians')->truncate();
        
        // Aktifkan kembali foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Data hierarki baru
        $hierarchy = [
            'Teknik' => [
                'Passing',
                'Shooting',
                'Controlling',
                'Dribbling',
                'Off The Ball',
                'Power',
                'Speed & Refleks'
            ],
            'Etika' => [
                'Discipline',
                'Sportivitas',
                'Integritas & Etika'
            ],
            'Mini Game' => [
                'Kerja Sama Tim',
                'Individual'
            ]
        ];
        
        $urutanParent = 1;
        
        // Insert kategori parent dan children
        foreach ($hierarchy as $parentName => $children) {
            // Insert parent
            $parentId = DB::table('kategori_penilaians')->insertGetId([
                'parent_id' => null,
                'nama' => $parentName,
                'deskripsi' => "Kategori {$parentName}",
                'urutan' => $urutanParent++,
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Insert children
            $urutanChild = 1;
            foreach ($children as $childName) {
                DB::table('kategori_penilaians')->insert([
                    'parent_id' => $parentId,
                    'nama' => $childName,
                    'deskripsi' => "Sub-kategori {$childName}",
                    'urutan' => $urutanChild++,
                    'aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback, data sudah dihapus
    }
};
