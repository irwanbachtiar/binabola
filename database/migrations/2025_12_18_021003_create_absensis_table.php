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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpa'])->default('Hadir');
            $table->enum('sesi', ['Pagi', 'Sore', 'Full Day'])->default('Pagi');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['tanggal', 'siswa_id']);
            $table->unique(['siswa_id', 'tanggal', 'sesi']); // Tidak boleh duplikat absensi per hari per sesi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
