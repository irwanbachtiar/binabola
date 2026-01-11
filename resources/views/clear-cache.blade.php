<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Clear Cache & Service Worker</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">🔧 Clear Cache & Service Worker</h3>
                    </div>
                    <div class="card-body">
                        <p class="lead">Halaman ini akan membersihkan cache browser dan service worker PWA.</p>
                        
                        <div id="status" class="alert alert-info">
                            <strong>Status:</strong> Menunggu...
                        </div>

                        <div class="d-grid gap-2">
                            <button onclick="clearAll()" class="btn btn-danger btn-lg">
                                <i class="bi bi-trash"></i> Clear Semua Cache
                            </button>
                            <button onclick="unregisterSW()" class="btn btn-warning btn-lg">
                                <i class="bi bi-power"></i> Unregister Service Worker
                            </button>
                            <button onclick="hardReload()" class="btn btn-primary btn-lg">
                                <i class="bi bi-arrow-clockwise"></i> Hard Reload
                            </button>
                            <a href="/" class="btn btn-success btn-lg">
                                <i class="bi bi-house"></i> Kembali ke Home
                            </a>
                        </div>

                        <hr class="my-4">

                        <h5>Info Service Worker:</h5>
                        <div id="swInfo" class="alert alert-secondary">
                            <pre id="swStatus">Loading...</pre>
                        </div>

                        <h5>Manual Steps:</h5>
                        <ol>
                            <li>Tekan <kbd>Ctrl + Shift + Delete</kbd> untuk membuka Clear Browsing Data</li>
                            <li>Pilih "Cached images and files"</li>
                            <li>Klik "Clear data"</li>
                            <li>Tekan <kbd>Ctrl + Shift + R</kbd> atau <kbd>Ctrl + F5</kbd> untuk hard reload</li>
                            <li>Atau buka DevTools (F12) → Application → Clear Storage → Clear site data</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStatus(msg, type = 'info') {
            const statusDiv = document.getElementById('status');
            statusDiv.className = `alert alert-${type}`;
            statusDiv.innerHTML = `<strong>Status:</strong> ${msg}`;
        }

        async function clearAll() {
            updateStatus('Membersihkan cache...', 'warning');
            
            try {
                // Clear all caches
                if ('caches' in window) {
                    const names = await caches.keys();
                    await Promise.all(names.map(name => caches.delete(name)));
                    updateStatus(`✓ Berhasil menghapus ${names.length} cache`, 'success');
                }
                
                // Clear local storage
                localStorage.clear();
                sessionStorage.clear();
                
                setTimeout(() => {
                    updateStatus('✓ Cache berhasil dibersihkan! Silakan reload halaman.', 'success');
                }, 500);
            } catch (e) {
                updateStatus('❌ Error: ' + e.message, 'danger');
            }
        }

        async function unregisterSW() {
            updateStatus('Unregistering service worker...', 'warning');
            
            try {
                if ('serviceWorker' in navigator) {
                    const registrations = await navigator.serviceWorker.getRegistrations();
                    
                    for (let registration of registrations) {
                        await registration.unregister();
                    }
                    
                    updateStatus(`✓ ${registrations.length} service worker berhasil di-unregister!`, 'success');
                    
                    setTimeout(() => {
                        location.reload(true);
                    }, 2000);
                } else {
                    updateStatus('Service Worker tidak didukung browser ini', 'warning');
                }
            } catch (e) {
                updateStatus('❌ Error: ' + e.message, 'danger');
            }
        }

        function hardReload() {
            updateStatus('Melakukan hard reload...', 'warning');
            setTimeout(() => {
                location.reload(true);
            }, 500);
        }

        // Check SW status
        async function checkSWStatus() {
            const swInfo = document.getElementById('swStatus');
            
            if ('serviceWorker' in navigator) {
                try {
                    const registrations = await navigator.serviceWorker.getRegistrations();
                    
                    if (registrations.length === 0) {
                        swInfo.textContent = '✓ Tidak ada service worker terdaftar';
                    } else {
                        let info = `Total: ${registrations.length} service worker\n\n`;
                        
                        registrations.forEach((reg, i) => {
                            info += `SW ${i + 1}:\n`;
                            info += `  Scope: ${reg.scope}\n`;
                            info += `  State: ${reg.active ? reg.active.state : 'no active worker'}\n\n`;
                        });
                        
                        swInfo.textContent = info;
                    }
                    
                    // Check caches
                    const cacheNames = await caches.keys();
                    swInfo.textContent += `\nTotal Cache: ${cacheNames.length}\n`;
                    cacheNames.forEach(name => {
                        swInfo.textContent += `  - ${name}\n`;
                    });
                } catch (e) {
                    swInfo.textContent = 'Error: ' + e.message;
                }
            } else {
                swInfo.textContent = 'Service Worker tidak didukung';
            }
        }

        // Run check on load
        checkSWStatus();
        
        // Auto check every 2 seconds
        setInterval(checkSWStatus, 2000);
    </script>
</body>
</html>
