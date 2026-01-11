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
            $table->integer('bobot')->default(0)->after('deskripsi'); // Bobot penilaian (total harus 100)
        });
        
        // Update data awal dengan bobot
        DB::table('kategori_penilaians')->where('nama', 'Fisik')->update(['bobot' => 30]);
        DB::table('kategori_penilaians')->where('nama', 'Disiplin')->update(['bobot' => 30]);
        DB::table('kategori_penilaians')->where('nama', 'Teknik')->update(['bobot' => 40]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_penilaians', function (Blueprint $table) {
            $table->dropColumn('bobot');
        });
    }
};
