@extends('layouts.app')

@section('title', 'Selamat Datang - BinaBola')

@section('content')
<div class="container py-5">
    <!-- Welcome Header -->
    <div class="text-center mb-5">
        <div class="mb-4">
            <img src="{{ asset('icons/icon-192.png') }}" alt="BinaBola" width="120" height="120" style="border-radius: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
        </div>
        <h1 class="display-4 fw-bold mb-3">
            <i class="bi bi-check-circle-fill text-success"></i> Selamat!
        </h1>
        <p class="lead text-muted">Aplikasi BinaBola berhasil diinstall di perangkat Anda</p>
    </div>

    <!-- Features Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-lightning-charge-fill text-primary" style="font-size: 48px;"></i>
                    </div>
                    <h5 class="card-title">Akses Lebih Cepat</h5>
                    <p class="card-text text-muted">
                        Buka aplikasi langsung dari home screen tanpa membuka browser
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-wifi-off text-warning" style="font-size: 48px;"></i>
                    </div>
                    <h5 class="card-title">Bekerja Offline</h5>
                    <p class="card-text text-muted">
                        Akses halaman yang sudah dibuka bahkan tanpa koneksi internet
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-bell-fill text-info" style="font-size: 48px;"></i>
                    </div>
                    <h5 class="card-title">Update Otomatis</h5>
                    <p class="card-text text-muted">
                        Aplikasi akan otomatis update ke versi terbaru saat tersedia
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- How to Use Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="mb-4"><i class="bi bi-question-circle"></i> Cara Menggunakan</h4>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6 class="text-primary"><i class="bi bi-phone"></i> Di Mobile</h6>
                    <ol class="ps-3">
                        <li>Cari icon <strong>BinaBola</strong> di home screen</li>
                        <li>Tap icon untuk membuka aplikasi</li>
                        <li>Aplikasi akan terbuka fullscreen seperti app native</li>
                    </ol>
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="text-primary"><i class="bi bi-laptop"></i> Di Desktop</h6>
                    <ol class="ps-3">
                        <li>Cari <strong>BinaBola</strong> di Start Menu/Applications</li>
                        <li>Atau klik icon di desktop/taskbar</li>
                        <li>Aplikasi akan terbuka di window terpisah</li>
                    </ol>
                </div>
            </div>

            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle"></i> 
                <strong>Tips:</strong> Aplikasi akan otomatis sync data terbaru saat online
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="mb-4"><i class="bi bi-lightning-charge"></i> Quick Actions</h4>
            
            <div class="row g-3">
                @auth
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'pelatih')
                    <div class="col-md-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-house-door d-block mb-2" style="font-size: 32px;"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('absensi.index') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-calendar-check d-block mb-2" style="font-size: 32px;"></i>
                            Input Absensi
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('evaluasi.index') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-clipboard-data d-block mb-2" style="font-size: 32px;"></i>
                            Penilaian
                        </a>
                    </div>
                    @elseif(auth()->user()->role === 'orangtua')
                    <div class="col-md-4">
                        <a href="{{ route('orangtua.dashboard') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-house-door d-block mb-2" style="font-size: 32px;"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('orangtua.absensi') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-calendar-check d-block mb-2" style="font-size: 32px;"></i>
                            Absensi Siswa
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('orangtua.evaluasi') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-clipboard-data d-block mb-2" style="font-size: 32px;"></i>
                            Evaluasi Siswa
                        </a>
                    </div>
                    @endif
                @else
                    <div class="col-md-12">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Login untuk Memulai
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Uninstall Info -->
    <div class="card border-0 bg-light">
        <div class="card-body p-4">
            <h6 class="mb-3"><i class="bi bi-trash"></i> Cara Uninstall</h6>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Mobile:</strong>
                    <p class="mb-0 text-muted small">Long-press icon → Uninstall/Remove</p>
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Desktop Chrome:</strong>
                    <p class="mb-0 text-muted small">chrome://apps → Right-click → Remove</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Continue Button -->
    <div class="text-center mt-5">
        @auth
            @if(auth()->user()->role === 'orangtua')
                <a href="{{ route('orangtua.dashboard') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-arrow-right-circle"></i> Mulai Menggunakan Aplikasi
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-arrow-right-circle"></i> Mulai Menggunakan Aplikasi
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-arrow-right-circle"></i> Login untuk Melanjutkan
            </a>
        @endauth
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-4px);
    }
</style>

<script>
    // Set flag bahwa user sudah melihat welcome screen
    localStorage.setItem('pwa-welcome-seen', 'true');
</script>
@endsection
