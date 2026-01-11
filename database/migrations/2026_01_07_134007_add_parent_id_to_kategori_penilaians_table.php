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
        Schema::table('kategori_penilaians', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('kategori_penilaians')->onDelete('cascade');
        });
        
        // Konversi data lama: buat sub-kategori dari kategori yang ada
        // Misal: Fisik (parent) -> Kecepatan, Stamina, dll (children)
        
        // Ambil kategori lama
        $kategoris = DB::table('kategori_penilaians')->whereNull('parent_id')->get();
        
        $subKategoriData = [
            'Fisik' => ['Kecepatan', 'Stamina', 'Kekuatan', 'Kelincahan'],
            'Disiplin' => ['Kehadiran', 'Attitude', 'Kerjasama', 'Mendengarkan Instruksi'],
            'Teknik' => ['Passing', 'Dribbling', 'Shooting', 'Heading', 'Control'],
        ];
        
        foreach ($kategoris as $kategori) {
            if (isset($subKategoriData[$kategori->nama])) {
                $urutan = 1;
                foreach ($subKategoriData[$kategori->nama] as $subNama) {
                    DB::table('kategori_penilaians')->insert([
                        'parent_id' => $kategori->id,
                        'nama' => $subNama,
                        'deskripsi' => "Sub-kategori {$subNama} dari {$kategori->nama}",
                        'urutan' => $urutan++,
                        'aktif' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_penilaians', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
