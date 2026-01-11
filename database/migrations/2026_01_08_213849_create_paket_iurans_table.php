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
        Schema::create('paket_iurans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket'); // Contoh: "Paket U-7 Reguler", "Paket U-12 Premium"
            $table->enum('kelompok_umur', ['U-7', 'U-12']); // Sesuai dengan kelompok umur siswa
            $table->decimal('nominal', 10, 2); // Harga paket
            $table->integer('durasi_bulan')->default(1); // Berapa bulan (1 = bulanan, 3 = triwulan, dst)
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->boolean('aktif')->default(true); // Status paket aktif/tidak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_iurans');
    }
};
