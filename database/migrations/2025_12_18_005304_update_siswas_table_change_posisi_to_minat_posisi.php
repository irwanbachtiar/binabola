<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah kolom minat_posisi baru
        Schema::table('siswas', function (Blueprint $table) {
            $table->json('minat_posisi')->nullable()->after('tanggal_lahir');
        });
        
        // Migrasi data lama dari posisi ke minat_posisi
        DB::table('siswas')->get()->each(function ($siswa) {
            if (isset($siswa->posisi) && $siswa->posisi) {
                DB::table('siswas')
                    ->where('id', $siswa->id)
                    ->update(['minat_posisi' => json_encode([$siswa->posisi])]);
            }
        });
        
        // Hapus kolom posisi lama setelah migrasi data
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('posisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan kolom posisi
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('posisi')->nullable()->after('tanggal_lahir');
        });
        
        // Migrasi data kembali (ambil posisi pertama dari array)
        DB::table('siswas')->get()->each(function ($siswa) {
            if (isset($siswa->minat_posisi) && $siswa->minat_posisi) {
                $posisi = json_decode($siswa->minat_posisi, true);
                DB::table('siswas')
                    ->where('id', $siswa->id)
                    ->update(['posisi' => $posisi[0] ?? null]);
            }
        });
        
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('minat_posisi');
        });
    }
};
