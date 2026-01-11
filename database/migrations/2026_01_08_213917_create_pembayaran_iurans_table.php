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
        Schema::create('pembayaran_iurans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('paket_iuran_id')->nullable()->constrained('paket_iurans')->onDelete('set null');
            $table->integer('periode_bulan'); // 1-12
            $table->integer('periode_tahun'); // 2025, 2026, dst
            $table->date('tanggal_bayar');
            $table->decimal('nominal', 10, 2);
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'qris', 'lainnya'])->default('cash');
            $table->string('bukti_pembayaran')->nullable(); // path file upload
            $table->enum('status', ['pending', 'lunas', 'expired'])->default('lunas');
            $table->text('catatan')->nullable();
            $table->string('created_by')->nullable(); // Nama admin/user yang input
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index(['siswa_id', 'periode_tahun', 'periode_bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_iurans');
    }
};
