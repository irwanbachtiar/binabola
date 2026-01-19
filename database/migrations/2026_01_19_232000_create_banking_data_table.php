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
        Schema::create('banking_data', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->comment('Tanggal entry data');
            $table->decimal('osl_kca', 15, 2)->default(0)->comment('OSL KCA');
            $table->decimal('osl_mikro', 15, 2)->default(0)->comment('OSL Mikro');
            $table->decimal('osl_emas', 15, 2)->default(0)->comment('OSL EMAS');
            $table->decimal('gte', 15, 2)->default(0)->comment('GTE');
            $table->integer('nasabah_baru')->default(0)->comment('Nasabah Baru (jumlah)');
            $table->integer('nasabah_baru_agen')->default(0)->comment('Nasabah Baru Agen');
            $table->integer('nasabah_existing')->default(0)->comment('Nasabah existing (jumlah)');
            $table->integer('nasabah_tabungan_emas')->default(0)->comment('Nasabah tabungan emas');
            $table->decimal('deposito', 10, 3)->default(0)->comment('Deposito (gramasi)');
            $table->decimal('tabungan_emas', 10, 3)->default(0)->comment('Tabungan Emas (gramasi)');
            $table->decimal('g24', 10, 3)->default(0)->comment('G24 (gram)');
            $table->integer('nasabah_tring')->default(0)->comment('Nasabah Tring');
            $table->decimal('osl_tring', 15, 2)->default(0)->comment('OSL Tring');
            $table->integer('frekuensi_trx_tring')->default(0)->comment('Frekuensi trx tring');
            $table->decimal('disbursement_bri', 15, 2)->default(0)->comment('Disbursement BRI');
            $table->decimal('osl_sinergi_holding', 15, 2)->default(0)->comment('OSL sinergi holding');
            $table->decimal('te_sinergi_holding', 10, 3)->default(0)->comment('TE Sinergi Holding (gramasi)');
            $table->unsignedBigInteger('user_id')->comment('User yang input data');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banking_data');
    }
};
