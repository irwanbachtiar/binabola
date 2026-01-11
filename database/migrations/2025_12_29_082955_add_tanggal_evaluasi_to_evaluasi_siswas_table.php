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
        Schema::table('evaluasi_siswas', function (Blueprint $table) {
            $table->date('tanggal_evaluasi')->nullable()->after('minggu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluasi_siswas', function (Blueprint $table) {
            $table->dropColumn('tanggal_evaluasi');
        });
    }
};
