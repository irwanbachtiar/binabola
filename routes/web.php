<?php

use App\Http\Controllers\SiswaController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrangtuaController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\BankingDataController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', function () {
    return redirect()->route('login')->with('error', 'Silakan gunakan tombol logout yang tersedia.');
});
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Default route - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// PWA Welcome page
Route::get('/welcome-pwa', function () {
    return view('welcome-pwa');
})->name('welcome.pwa');

// Landing page (public) - disabled for production
/*
Route::get('/landing', function () {
    return view('landing');
})->name('landing');
*/

// Clear cache helper page
Route::get('/clear-cache-page', function () {
    return view('clear-cache');
})->name('clear.cache.page');

// API: Check absensi for specific date
Route::get('/api/check-absensi', function (Illuminate\Http\Request $request) {
    $siswaId = $request->query('siswa_id');
    $tanggal = $request->query('tanggal');
    
    $absensi = App\Models\Absensi::where('siswa_id', $siswaId)
        ->whereDate('tanggal', $tanggal)
        ->first();
    
    return response()->json([
        'status' => $absensi ? $absensi->status : 'Belum diabsen',
        'exists' => $absensi ? true : false
    ]);
})->middleware('auth');

// Route Dashboard (protected)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Route Resource untuk Siswa (hanya admin/pelatih)
Route::middleware(['auth'])->group(function () {
    Route::resource('siswa', SiswaController::class);
    
    // Route untuk Evaluasi/Penilaian
    Route::get('/evaluasi', [EvaluasiController::class, 'index'])->name('evaluasi.index');
    Route::get('/evaluasi/create/{siswa}', [EvaluasiController::class, 'create'])->name('evaluasi.create');
    Route::post('/evaluasi', [EvaluasiController::class, 'store'])->name('evaluasi.store');
    Route::get('/evaluasi/get-week/{siswa}/{minggu}', [EvaluasiController::class, 'getWeek'])->name('evaluasi.getWeek');
    Route::delete('/evaluasi/{siswa}/{minggu}', [EvaluasiController::class, 'delete'])->name('evaluasi.delete');
    Route::get('/evaluasi/{siswa}', [EvaluasiController::class, 'show'])->name('evaluasi.show');
    
    // Batch Evaluation Routes
    Route::get('/evaluasi-batch', [EvaluasiController::class, 'batchIndex'])->name('evaluasi.batch');
    Route::get('/evaluasi/batch/siswa', [EvaluasiController::class, 'getSiswaByTanggal'])->name('evaluasi.batch.siswa');
    Route::post('/evaluasi-batch', [EvaluasiController::class, 'batchStore'])->name('evaluasi.batch.store');
    
    // Route untuk Absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/history', [AbsensiController::class, 'history'])->name('absensi.history');
    Route::get('/absensi/statistik', [AbsensiController::class, 'statistik'])->name('absensi.statistik');
    
    // Route untuk Laporan
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/mingguan', [App\Http\Controllers\LaporanController::class, 'mingguan'])->name('laporan.mingguan');
    Route::get('/laporan/bulanan', [App\Http\Controllers\LaporanController::class, 'bulanan'])->name('laporan.bulanan');
    Route::get('/laporan/triwulan', [App\Http\Controllers\LaporanController::class, 'triwulan'])->name('laporan.triwulan');
    Route::get('/laporan/tahunan', [App\Http\Controllers\LaporanController::class, 'tahunan'])->name('laporan.tahunan');
    Route::get('/laporan/siswa/{id}', [App\Http\Controllers\LaporanController::class, 'siswaDetail'])->name('laporan.siswa.detail');
    Route::get('/laporan/statistik-bulanan/pdf', [App\Http\Controllers\LaporanController::class, 'statistikBulananPdf'])->name('laporan.statistik.bulanan.pdf');
    
    // Route untuk Pembayaran Iuran
    Route::get('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/create', [App\Http\Controllers\PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/siswa/{id}', [App\Http\Controllers\PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran/laporan', [App\Http\Controllers\PembayaranController::class, 'laporan'])->name('pembayaran.laporan');
    Route::get('/pembayaran/monitoring', [App\Http\Controllers\PembayaranController::class, 'monitoring'])->name('pembayaran.monitoring');
    
    // Route untuk Master Paket Iuran
    Route::get('/paket-iuran', [App\Http\Controllers\PaketIuranController::class, 'index'])->name('paket-iuran.index');
    Route::get('/paket-iuran/create', [App\Http\Controllers\PaketIuranController::class, 'create'])->name('paket-iuran.create');
    Route::post('/paket-iuran', [App\Http\Controllers\PaketIuranController::class, 'store'])->name('paket-iuran.store');
    Route::get('/paket-iuran/{id}/edit', [App\Http\Controllers\PaketIuranController::class, 'edit'])->name('paket-iuran.edit');
    Route::put('/paket-iuran/{id}', [App\Http\Controllers\PaketIuranController::class, 'update'])->name('paket-iuran.update');
    Route::delete('/paket-iuran/{id}', [App\Http\Controllers\PaketIuranController::class, 'destroy'])->name('paket-iuran.destroy');
    
    // Route untuk Kategori Penilaian (hanya admin)
    Route::middleware('role:admin')->group(function () {
        Route::resource('kategori-penilaian', App\Http\Controllers\KategoriPenilaianController::class);
        Route::resource('hari-libur', App\Http\Controllers\HariLiburController::class);
    });
    
    // Route untuk Banking Data
    Route::get('/banking-data', [BankingDataController::class, 'index'])->name('banking-data.index');
    Route::get('/banking-data/create', [BankingDataController::class, 'create'])->name('banking-data.create');
    Route::post('/banking-data', [BankingDataController::class, 'store'])->name('banking-data.store');
    Route::get('/banking-data/{id}/edit', [BankingDataController::class, 'edit'])->name('banking-data.edit');
    Route::put('/banking-data/{id}', [BankingDataController::class, 'update'])->name('banking-data.update');
    Route::delete('/banking-data/{id}', [BankingDataController::class, 'destroy'])->name('banking-data.destroy');
});

// Routes untuk Orangtua (role: orangtua)
Route::middleware(['auth', 'role:orangtua'])->prefix('orangtua')->name('orangtua.')->group(function () {
    Route::get('/dashboard', [OrangtuaController::class, 'dashboard'])->name('dashboard');
    Route::get('/siswa/{id}/progress', [OrangtuaController::class, 'showSiswaEvaluasi'])->name('siswa.progress');
    Route::get('/siswa/{id}', [OrangtuaController::class, 'showSiswa'])->name('siswa.show');
    Route::get('/siswa/{id}/absensi', [OrangtuaController::class, 'showSiswaAbsensi'])->name('siswa.absensi');
    Route::get('/siswa/{id}/evaluasi', [OrangtuaController::class, 'showSiswaEvaluasi'])->name('siswa.evaluasi');
    
    // Pembayaran routes
    Route::get('/siswa/{id}/pembayaran', [OrangtuaController::class, 'showSiswaPembayaran'])->name('siswa.pembayaran');
    Route::get('/siswa/{id}/pelunasan', [OrangtuaController::class, 'formPelunasan'])->name('siswa.pelunasan');
    Route::post('/siswa/{id}/pelunasan', [OrangtuaController::class, 'submitPelunasan'])->name('siswa.pelunasan.submit');
});

// Routes untuk Admin - User Management (role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserManagementController::class);
});