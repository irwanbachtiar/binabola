<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#667eea">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    
    <title>@yield('title', 'BinaBola')</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}">
    
    <style>
        body {
            background: #f5f7fa;
            padding-bottom: 70px; /* Space for bottom nav */
            overflow-x: hidden;
        }
        
        /* Top Header */
        .mobile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .mobile-header h5 {
            margin: 0;
            font-weight: bold;
        }
        
        /* Content Area */
        .mobile-content {
            margin-top: 70px;
            padding: 15px;
            padding-bottom: 20px;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 15px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
        }
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            padding: 10px 0;
        }
        
        .bottom-nav .nav-link {
            text-align: center;
            color: #6c757d;
            padding: 5px;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .bottom-nav .nav-link.active {
            color: #667eea;
        }
        
        .bottom-nav .nav-link i {
            font-size: 24px;
            display: block;
            margin-bottom: 3px;
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        
        /* Form Controls */
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        /* Siswa Card */
        .siswa-card {
            display: flex;
            align-items: center;
            padding: 15px;
            background: white;
            border-radius: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s;
        }
        
        .siswa-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .siswa-card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        
        .siswa-card .placeholder-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        
        .siswa-info h6 {
            margin: 0;
            font-weight: 600;
        }
        
        .siswa-info small {
            color: #6c757d;
        }
        
        /* Range Slider */
        .range-slider {
            width: 100%;
            margin: 15px 0;
        }
        
        .range-value {
            display: inline-block;
            padding: 5px 15px;
            background: #667eea;
            color: white;
            border-radius: 20px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        /* Loading */
        .loading {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
        
        @media (max-width: 576px) {
            .mobile-content {
                padding: 10px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5>@yield('page-title', 'BinaBola')</h5>
            <div>
                @auth
                    <span class="me-3">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="mobile-content">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="row g-0">
            <div class="col">
                @auth
                    @if(auth()->user()->role === 'orangtua')
                        <a href="{{ route('orangtua.dashboard') }}" class="nav-link {{ request()->is('orangtua/dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i>
                            <div>Home</div>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i>
                            <div>Home</div>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="nav-link">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <div>Login</div>
                    </a>
                @endauth
            </div>
            <div class="col">
                <a href="{{ route('evaluasi.index') }}" class="nav-link {{ request()->routeIs('evaluasi.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check"></i>
                    <div>Evaluasi</div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('absensi.index') }}" class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <div>Absensi</div>
                </a>
            </div>
            @auth
                @if(auth()->user()->role !== 'orangtua')
                <div class="col">
                    <a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <div>Siswa</div>
                    </a>
                </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="loading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('Service Worker registered'))
                .catch(err => console.log('Service Worker registration failed', err));
        }
        
        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 3000);
    </script>
    
    @stack('scripts')
</body>
</html>
