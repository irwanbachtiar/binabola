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
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('nama');
            $table->string('telepon', 20)->nullable()->after('minat_posisi');
            $table->string('email')->nullable()->after('telepon');
            $table->text('alamat')->nullable()->after('email');
            $table->integer('tinggi_badan')->nullable()->comment('dalam cm')->after('alamat');
            $table->integer('berat_badan')->nullable()->comment('dalam kg')->after('tinggi_badan');
            $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif')->after('berat_badan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['foto', 'telepon', 'email', 'alamat', 'tinggi_badan', 'berat_badan', 'status']);
        });
    }
};
