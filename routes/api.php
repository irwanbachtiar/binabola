<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Siswa;

Route::get('/siswa/{id}', function($id) {
    $siswa = Siswa::findOrFail($id);
    return response()->json([
        'id' => $siswa->id,
        'nama' => $siswa->nama,
        'paket_iuran_id' => $siswa->paket_iuran_id,
        'kelompok_umur' => $siswa->kelompok_umur,
    ]);
});
