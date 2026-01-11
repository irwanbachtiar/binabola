// PWA Install Prompt Handler
let deferredPrompt;
let installButton;

// Check if app is already installed
function isAppInstalled() {
    return window.matchMedia('(display-mode: standalone)').matches || 
           window.navigator.standalone === true;
}

// Initialize PWA features
document.addEventListener('DOMContentLoaded', function() {
    // Hide install button if already installed
    if (isAppInstalled()) {
        const installBanner = document.getElementById('pwa-install-banner');
        if (installBanner) {
            installBanner.style.display = 'none';
        }
        return;
    }

    // Show install banner after 5 seconds if not installed
    setTimeout(() => {
        const installBanner = document.getElementById('pwa-install-banner');
        if (installBanner && !sessionStorage.getItem('pwa-install-dismissed')) {
            installBanner.style.display = 'block';
        }
    }, 5000);
});

// Listen for the beforeinstallprompt event
window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent Chrome 67 and earlier from automatically showing the prompt
    e.preventDefault();
    // Stash the event so it can be triggered later
    deferredPrompt = e;
    
    // Show install button/banner
    const installBanner = document.getElementById('pwa-install-banner');
    if (installBanner && !sessionStorage.getItem('pwa-install-dismissed')) {
        installBanner.style.display = 'block';
    }
});

// Handle install button click
function installPWA() {
    if (!deferredPrompt) {
        alert('Aplikasi sudah terinstall atau browser tidak mendukung instalasi PWA');
        return;
    }

    // Show the install prompt
    deferredPrompt.prompt();
    
    // Wait for the user to respond to the prompt
    deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
            console.log('User accepted the install prompt');
            hidePWAInstallBanner();
        } else {
            console.log('User dismissed the install prompt');
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

// Hide banner permanently after install
function hidePWAInstallBanner() {
    const installBanner = document.getElementById('pwa-install-banner');
    if (installBanner) {
        installBanner.style.display = 'none';
        localStorage.setItem('pwa-installed', 'true');
    }
}

// Listen for app installed event
window.addEventListener('appinstalled', () => {
    console.log('PWA was installed');
    hidePWAInstallBanner();
});

// Service Worker Registration with Update Detection
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
        .then(registration => {
            console.log('Service Worker registered successfully:', registration.scope);
            
            // Check for updates every hour
            setInterval(() => {
                registration.update();
            }, 3600000);

            // Handle service worker updates
            registration.addEventListener('updatefound', () => {
                const newWorker = registration.installing;
                
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        // New service worker available
                        showUpdateNotification();
                    }
                });
            });
        })
        .catch(error => {
            console.log('Service Worker registration failed:', error);
        });

    // Handle service worker controller change
    let refreshing = false;
    navigator.serviceWorker.addEventListener('controllerchange', () => {
        if (!refreshing) {
            refreshing = true;
            window.location.reload();
        }
    });
}

// Show update notification
function showUpdateNotification() {
    const updateBanner = document.getElementById('pwa-update-banner');
    if (updateBanner) {
        updateBanner.style.display = 'block';
    } else {
        if (confirm('Versi baru aplikasi tersedia. Refresh sekarang?')) {
            window.location.reload();
        }
    }
}

// Update PWA
function updatePWA() {
    navigator.serviceWorker.getRegistration().then(registration => {
        if (registration && registration.waiting) {
            registration.waiting.postMessage({ type: 'SKIP_WAITING' });
        }
    });
}

// Check online/offline status
/* window.addEventListener('online', () => {
    const offlineBanner = document.getElementById('offline-banner');
    if (offlineBanner) {
        offlineBanner.style.display = 'none';
    }
    
    // Show toast notification
    showToast('Koneksi internet tersambung', 'success');
});

window.addEventListener('offline', () => {
    const offlineBanner = document.getElementById('offline-banner');
    if (offlineBanner) {
        offlineBanner.style.display = 'block';
    }
    
    // Show toast notification
    showToast('Tidak ada koneksi internet', 'warning');
}); */

// Toast notification helper
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Pull to refresh (for mobile)
let startY = 0;
let isPulling = false;

document.addEventListener('touchstart', (e) => {
    if (window.scrollY === 0) {
        startY = e.touches[0].pageY;
        isPulling = true;
    }
}, { passive: true });

document.addEventListener('touchmove', (e) => {
    if (!isPulling) return;
    
    const currentY = e.touches[0].pageY;
    const distance = currentY - startY;
    
    if (distance > 100) {
        // Show loading indicator
        const refreshIndicator = document.getElementById('pull-refresh-indicator');
        if (refreshIndicator) {
            refreshIndicator.style.display = 'block';
        }
    }
}, { passive: true });

document.addEventListener('touchend', (e) => {
    if (!isPulling) return;
    
    const endY = e.changedTouches[0].pageY;
    const distance = endY - startY;
    
    if (distance > 100) {
        // Trigger refresh
        window.location.reload();
    }
    
    isPulling = false;
    startY = 0;
    
    const refreshIndicator = document.getElementById('pull-refresh-indicator');
    if (refreshIndicator) {
        refreshIndicator.style.display = 'none';
    }
});
