<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswasTable extends Migration
{
    public function up()
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();                              // ID siswa (primary key)
            $table->string('nama');                   // Nama siswa
            $table->date('tanggal_lahir');            // Tanggal lahir siswa
            $table->string('posisi');                 // Posisi bermain (misalnya striker, kiper)
            $table->timestamps();                     // Timestamps created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswas'); // Drop tabel siswa jika migrasi di-rollback
    }
}