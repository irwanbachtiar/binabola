<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#667eea">
    <meta name="description" content="Aplikasi manajemen evaluasi siswa sekolah sepak bola">
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    
    <title>Login - BinaBola</title>
    
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            margin: 20px;
        }
        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            display: block;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="{{ asset('images/logobinabola.jpeg') }}" alt="BinaBola" class="logo" style="border-radius: 15px; object-fit: cover;">
        <h3 class="text-center mb-1">Bina Bola</h3>
        <p class="text-center text-muted mb-4 small">Pelindo Group Makassar</p>
        <p class="text-center text-muted mb-4">Login</p>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        </form>

        

        <!-- Install PWA prompt -->
        <div id="installPrompt" class="alert alert-info mt-3" style="display: none;">
            <i class="bi bi-phone"></i> Install aplikasi untuk akses lebih cepat
            <button id="installBtn" class="btn btn-sm btn-info mt-2 w-100">Install App</button>
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    
    <!-- PWA Install Script -->
    <script>
        let deferredPrompt;
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            document.getElementById('installPrompt').style.display = 'block';
        });

        document.getElementById('installBtn')?.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response: ${outcome}`);
                deferredPrompt = null;
                document.getElementById('installPrompt').style.display = 'none';
            }
        });

        // Register Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('Service Worker registered', reg))
                .catch(err => console.log('Service Worker registration failed', err));
        }
    </script>
</body>
</html>
