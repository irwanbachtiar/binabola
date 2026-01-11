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
        Schema::create('tagihan_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('paket_iuran_id')->nullable()->constrained('paket_iurans')->onDelete('set null');
            $table->integer('periode_bulan'); // 1-12
            $table->integer('periode_tahun'); // 2025, 2026, dst
            $table->decimal('nominal', 10, 2);
            $table->date('jatuh_tempo');
            $table->enum('status', ['belum_bayar', 'lunas', 'terlambat'])->default('belum_bayar');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Unique constraint agar tidak duplicate tagihan per siswa per bulan
            $table->unique(['siswa_id', 'periode_tahun', 'periode_bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_siswas');
    }
};
