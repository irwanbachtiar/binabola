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
        Schema::create('kategori_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Fisik, Disiplin, Teknik
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0); // Untuk sorting
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
        
        // Seed data awal
        DB::table('kategori_penilaians')->insert([
            ['nama' => 'Fisik', 'deskripsi' => 'Penilaian kemampuan fisik siswa', 'urutan' => 1, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Disiplin', 'deskripsi' => 'Penilaian kedisiplinan dan attitude siswa', 'urutan' => 2, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Teknik', 'deskripsi' => 'Penilaian kemampuan teknik bermain sepak bola', 'urutan' => 3, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_penilaians');
    }
};
