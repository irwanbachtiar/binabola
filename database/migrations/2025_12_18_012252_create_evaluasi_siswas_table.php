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
        Schema::create('evaluasi_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('kategori_penilaian_id')->constrained('kategori_penilaians')->onDelete('cascade');
            $table->integer('minggu'); // Minggu ke berapa (1, 2, 3, dst)
            $table->integer('tahun'); // Tahun evaluasi
            $table->decimal('nilai', 5, 2); // Nilai 0-100
            $table->text('catatan')->nullable(); // Catatan dari pelatih
            $table->string('dinilai_oleh')->nullable(); // Nama pelatih yang menilai
            $table->timestamps();
            
            // Index untuk query lebih cepat
            $table->index(['siswa_id', 'minggu', 'tahun']);
            $table->index(['kategori_penilaian_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_siswas');
    }
};
