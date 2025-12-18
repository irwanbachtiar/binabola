<?php

use App\Http\Controllers\SiswaController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;

// Route Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Route Resource untuk Siswa
Route::resource('siswa', SiswaController::class);

// Route untuk Evaluasi/Penilaian
Route::get('/evaluasi', [EvaluasiController::class, 'index'])->name('evaluasi.index');
Route::get('/evaluasi/create/{siswa}', [EvaluasiController::class, 'create'])->name('evaluasi.create');
Route::post('/evaluasi', [EvaluasiController::class, 'store'])->name('evaluasi.store');
Route::get('/evaluasi/get-week/{siswa}/{minggu}', [EvaluasiController::class, 'getWeek'])->name('evaluasi.getWeek');
Route::delete('/evaluasi/{siswa}/{minggu}', [EvaluasiController::class, 'delete'])->name('evaluasi.delete');
Route::get('/evaluasi/{siswa}', [EvaluasiController::class, 'show'])->name('evaluasi.show');

// Route untuk Absensi
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
Route::get('/absensi/history', [AbsensiController::class, 'history'])->name('absensi.history');
Route::get('/absensi/statistik', [AbsensiController::class, 'statistik'])->name('absensi.statistik');