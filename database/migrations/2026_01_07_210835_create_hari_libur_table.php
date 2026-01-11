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
        Schema::create('hari_libur', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('keterangan');
            $table->enum('jenis', ['Nasional', 'Keagamaan', 'Sekolah', 'Lainnya'])->default('Lainnya');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hari_libur');
    }
};
