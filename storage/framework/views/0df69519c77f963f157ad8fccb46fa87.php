<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#667eea">
    <meta name="description" content="Aplikasi manajemen siswa sekolah sepak bola">
    
    <!-- Prevent Browser Cache for Dynamic Pages -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="BinaBola">
    <meta name="mobile-web-app-capable" content="yes">
    
    <title><?php echo $__env->yieldContent('title', 'Sekolah Sepak Bola'); ?></title>
    <link href="<?php echo e(asset('vendor/bootstrap/bootstrap.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/bootstrap-icons/bootstrap-icons.css')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/pwa.css']); ?>
    <style>
        .navbar-brand {
            font-weight: bold;
            color: #2c3e50 !important;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            transition: all 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .sidebar .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            margin-left: 10px;
        }
        .sidebar .dropdown-item {
            color: #667eea;
            padding: 8px 20px;
        }
        .sidebar .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
        .sidebar .dropdown-item.active {
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
            font-weight: 600;
        }
        .card {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        /* Standalone mode indicator */
        .standalone-badge {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            z-index: 9995;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            animation: slideInRight 0.5s ease;
        }
        @keyframes slideInRight {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Standalone Mode Badge -->
    <div class="standalone-badge" id="standalone-badge">
        <i class="bi bi-phone"></i> Mode Aplikasi
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="px-3 mb-4 text-center">
                        <img src="<?php echo e(asset('images/logobinabola.jpeg')); ?>" alt="Logo BinaBola" style="width: 80px; height: 80px; object-fit: contain; border-radius: 10px; background: white; padding: 5px; margin-bottom: 10px;">
                        
                    </div>
                    <ul class="nav flex-column">
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->role === 'orangtua'): ?>
                                <!-- Menu untuk Orangtua -->
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(request()->is('orangtua/dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('orangtua.dashboard')); ?>">
                                        <i class="bi bi-house-door"></i> Dashboard
                                    </a>
                                </li>
                            <?php else: ?>
                                <!-- Menu untuk Admin/Pelatih -->
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(request()->is('dashboard') || request()->is('/') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                                        <i class="bi bi-house-door"></i> Dashboard
                                    </a>
                                </li>
                                
                                <!-- Menu Master -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle <?php echo e(request()->is('siswa*') || request()->is('admin/users*') || request()->is('kategori-penilaian*') || request()->is('paket-iuran*') || request()->is('hari-libur*') ? 'active' : ''); ?>" 
                                       href="#" id="masterDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-database"></i> Master
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="masterDropdown">
                                        <li><a class="dropdown-item <?php echo e(request()->is('siswa*') ? 'active' : ''); ?>" href="<?php echo e(route('siswa.index')); ?>">
                                            <i class="bi bi-people"></i> Data Siswa
                                        </a></li>
                                        <?php if(auth()->user()->role === 'admin'): ?>
                                            <li><a class="dropdown-item <?php echo e(request()->is('admin/users*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.users.index')); ?>">
                                                <i class="bi bi-person-badge"></i> User Orangtua
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item <?php echo e(request()->is('kategori-penilaian*') ? 'active' : ''); ?>" href="<?php echo e(route('kategori-penilaian.index')); ?>">
                                                <i class="bi bi-bookmarks"></i> Kategori Penilaian
                                            </a></li>
                                            <li><a class="dropdown-item <?php echo e(request()->is('paket-iuran*') ? 'active' : ''); ?>" href="<?php echo e(route('paket-iuran.index')); ?>">
                                                <i class="bi bi-box-seam"></i> Paket Iuran
                                            </a></li>
                                            <li><a class="dropdown-item <?php echo e(request()->is('hari-libur*') ? 'active' : ''); ?>" href="<?php echo e(route('hari-libur.index')); ?>">
                                                <i class="bi bi-calendar-x"></i> Hari Libur
                                            </a></li>
                                        <?php endif; ?>
                                    </ul>
                                </li>

                                <!-- Menu Evaluasi -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle <?php echo e(request()->is('evaluasi*') ? 'active' : ''); ?>" 
                                       href="#" id="evaluasiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-graph-up"></i> Evaluasi
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="evaluasiDropdown">
                                        <li><a class="dropdown-item <?php echo e(request()->is('evaluasi') && !request()->is('evaluasi-batch') ? 'active' : ''); ?>" href="<?php echo e(route('evaluasi.index')); ?>">
                                            <i class="bi bi-eye"></i> Lihat Progress
                                        </a></li>
                                        <li><a class="dropdown-item <?php echo e(request()->is('evaluasi-batch') ? 'active' : ''); ?>" href="<?php echo e(route('evaluasi.batch')); ?>">
                                            <i class="bi bi-clipboard-check"></i> Penilaian Batch
                                        </a></li>
                                    </ul>
                                </li>

                                <!-- Menu Absensi -->
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(request()->is('absensi*') ? 'active' : ''); ?>" href="<?php echo e(route('absensi.index')); ?>">
                                        <i class="bi bi-calendar-check"></i> Absensi
                                    </a>
                                </li>

                                <!-- Menu Pembayaran Iuran -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle <?php echo e(request()->is('pembayaran*') ? 'active' : ''); ?>" 
                                       href="#" id="pembayaranDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-cash-coin"></i> Pembayaran Iuran
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="pembayaranDropdown">
                                        <li><a class="dropdown-item <?php echo e(request()->routeIs('pembayaran.index') ? 'active' : ''); ?>" href="<?php echo e(route('pembayaran.index')); ?>">
                                            <i class="bi bi-speedometer2"></i> Dashboard
                                        </a></li>
                                        <li><a class="dropdown-item <?php echo e(request()->routeIs('pembayaran.create') ? 'active' : ''); ?>" href="<?php echo e(route('pembayaran.create')); ?>">
                                            <i class="bi bi-plus-circle"></i> Input Pembayaran
                                        </a></li>
                                        <li><a class="dropdown-item <?php echo e(request()->routeIs('pembayaran.monitoring') ? 'active' : ''); ?>" href="<?php echo e(route('pembayaran.monitoring')); ?>">
                                            <i class="bi bi-calendar-check"></i> Monitoring Iuran
                                        </a></li>
                                        <li><a class="dropdown-item <?php echo e(request()->routeIs('pembayaran.laporan') ? 'active' : ''); ?>" href="<?php echo e(route('pembayaran.laporan')); ?>">
                                            <i class="bi bi-file-text"></i> Laporan Pembayaran
                                        </a></li>
                                    </ul>
                                </li>

                                <!-- Menu Laporan -->
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(request()->is('laporan*') ? 'active' : ''); ?>" href="<?php echo e(route('laporan.index')); ?>">
                                        <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(route('login')); ?>">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="d-flex align-items-center gap-2">
                            <?php if(auth()->guard()->check()): ?>
                                <span class="badge bg-primary">
                                    <i class="bi bi-person-circle"></i> <?php echo e(ucfirst(auth()->user()->role)); ?> - <?php echo e(auth()->user()->name); ?>

                                </span>
                                <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Logout">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Alert Messages -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-x-circle"></i> <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Content -->
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <!-- PWA Install Banner -->
    <div id="pwa-install-banner" style="display: none; position: fixed; bottom: 0; left: 0; right: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; z-index: 9998; box-shadow: 0 -2px 10px rgba(0,0,0,0.2);">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="bi bi-download" style="font-size: 24px;"></i>
                </div>
                <div class="col">
                    <strong>Install Aplikasi BinaBola</strong>
                    <p class="mb-0 small">Akses lebih cepat dan bisa digunakan offline</p>
                </div>
                <div class="col-auto">
                    <button onclick="installPWA()" class="btn btn-light btn-sm me-2">
                        <i class="bi bi-download"></i> Install
                    </button>
                    <button onclick="dismissPWABanner()" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- PWA Update Banner -->
    <div id="pwa-update-banner" style="display: none; position: fixed; top: 70px; left: 50%; transform: translateX(-50%); background: #28a745; color: white; padding: 12px 20px; z-index: 9998; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-arrow-repeat" style="font-size: 20px;"></i>
            <span><strong>Update tersedia!</strong> Versi baru aplikasi siap digunakan.</span>
            <button onclick="updatePWA()" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-clockwise"></i> Update
            </button>
        </div>
    </div>

    <!-- Offline Banner -->
    

    <!-- Pull to Refresh Indicator -->
    <div id="pull-refresh-indicator" style="display: none; position: fixed; top: 0; left: 50%; transform: translateX(-50%); background: rgba(102, 126, 234, 0.9); color: white; padding: 10px 20px; z-index: 9999; border-radius: 0 0 8px 8px;">
        <i class="bi bi-arrow-clockwise spin"></i> Release to refresh...
    </div>

    <script src="<?php echo e(asset('vendor/bootstrap/bootstrap.bundle.min.js')); ?>"></script>
    
    <!-- PWA Scripts -->
    <script src="<?php echo e(asset('build/assets/pwa-DnoDEGtx.js')); ?>" defer></script>
    
    <script>
        // PWA Install Handler (Inline for immediate availability)
        let deferredPrompt;

        // Listen for beforeinstallprompt event
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Show install banner
            const installBanner = document.getElementById('pwa-install-banner');
            if (installBanner && !sessionStorage.getItem('pwa-install-dismissed')) {
                setTimeout(() => {
                    installBanner.style.display = 'block';
                }, 5000);
            }
        });

        // Install PWA function
        function installPWA() {
            if (!deferredPrompt) {
                alert('Browser tidak mendukung instalasi PWA atau aplikasi sudah terinstall');
                return;
            }

            deferredPrompt.prompt();
            
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('✅ User installed PWA');
                    dismissPWABanner();
                } else {
                    console.log('❌ User dismissed install prompt');
                }
                deferredPrompt = null;
            });
        }

        // Dismiss install banner
        function dismissPWABanner() {
            const installBanner = document.getElementById('pwa-install-banner');
            if (installBanner) {
                installBanner.style.display = 'none';
                sessionStorage.setItem('pwa-install-dismissed', 'true');
            }
        }

        // Update PWA function
        function updatePWA() {
            navigator.serviceWorker.getRegistration().then(registration => {
                if (registration && registration.waiting) {
                    registration.waiting.postMessage({ type: 'SKIP_WAITING' });
                }
            });
        }

        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('✅ Service Worker registered:', registration.scope);
                        
                        // Check for updates
                        registration.addEventListener('updatefound', () => {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    const updateBanner = document.getElementById('pwa-update-banner');
                                    if (updateBanner) {
                                        updateBanner.style.display = 'block';
                                    }
                                }
                            });
                        });
                    })
                    .catch(err => console.error('❌ Service Worker registration failed:', err));
            });

            // Handle controller change
            let refreshing = false;
            navigator.serviceWorker.addEventListener('controllerchange', () => {
                if (!refreshing) {
                    refreshing = true;
                    window.location.reload();
                }
            });
        }

        // Check online/offline status
        

        // App installed event
        window.addEventListener('appinstalled', () => {
            console.log('✅ PWA installed successfully!');
            dismissPWABanner();
            
            // Set flag untuk show welcome screen
            localStorage.setItem('pwa-just-installed', 'true');
            
            // Show success message
            alert('✅ Aplikasi berhasil diinstall! Buka dari home screen untuk pengalaman terbaik.');
        });

        // Check if running in standalone mode (app telah diinstall)
        function isStandalone() {
            return window.matchMedia('(display-mode: standalone)').matches || 
                   window.navigator.standalone === true;
        }

        // Check if first launch after install
        function checkFirstLaunch() {
            if (isStandalone()) {
                // Show standalone badge
                const badge = document.getElementById('standalone-badge');
                if (badge) {
                    badge.style.display = 'block';
                    // Auto hide after 5 seconds
                    setTimeout(() => {
                        badge.style.display = 'none';
                    }, 5000);
                }

                // Check if first launch after install
                const justInstalled = localStorage.getItem('pwa-just-installed');
                const welcomeSeen = localStorage.getItem('pwa-welcome-seen');
                
                if (justInstalled === 'true' && welcomeSeen !== 'true') {
                    // Redirect to welcome page only if not already there
                    if (!window.location.pathname.includes('/welcome-pwa')) {
                        localStorage.removeItem('pwa-just-installed');
                        window.location.href = '/welcome-pwa';
                    }
                }
            }
        }

        // Run check on load
        window.addEventListener('load', checkFirstLaunch);
        
        // Initialize Bootstrap Dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            // Enable all dropdown toggles
            const dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
            dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\project ai\bina bola\binabola\resources\views/layouts/app.blade.php ENDPATH**/ ?>