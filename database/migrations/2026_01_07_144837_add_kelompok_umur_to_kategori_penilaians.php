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
            $table->enum('kelompok_umur', ['U-7', 'U-12'])->after('parent_id')->default('U-12');
        });
        
        // Update semua kategori yang sudah ada menjadi U-12
        DB::table('kategori_penilaians')->update(['kelompok_umur' => 'U-12']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_penilaians', function (Blueprint $table) {
            $table->dropColumn('kelompok_umur');
        });
    }
};
