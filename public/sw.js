const CACHE_NAME = 'binabola-v6';
const urlsToCache = [
  '/vendor/bootstrap/bootstrap.min.css',
  '/vendor/bootstrap/bootstrap.bundle.min.js',
  '/vendor/bootstrap-icons/bootstrap-icons.css',
  '/icons/icon-192.png',
  '/icons/icon-512.png'
];

// Install Service Worker
self.addEventListener('install', event => {
  console.log('[SW] Installing Service Worker v6...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('[SW] Cache opened');
        return cache.addAll(urlsToCache).catch(err => {
          console.error('[SW] Cache addAll failed:', err);
          // Continue anyway even if some files fail
          return Promise.resolve();
        });
      })
      .then(() => {
        console.log('[SW] Install complete');
        return self.skipWaiting();
      })
      .catch(err => {
        console.error('[SW] Install failed:', err);
      })
  );
});

// Activate - Clean up old caches
self.addEventListener('activate', event => {
  console.log('[SW] Activating Service Worker v6...');
  event.waitUntil(
    caches.keys()
      .then(cacheNames => {
        console.log('[SW] Current caches:', cacheNames);
        return Promise.all(
          cacheNames.map(cacheName => {
            if (cacheName !== CACHE_NAME) {
              console.log('[SW] Deleting old cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => {
        console.log('[SW] Activation complete, claiming clients');
        return self.clients.claim();
      })
      .catch(err => {
        console.error('[SW] Activation failed:', err);
      })
  );
});

// Listen for skip waiting message
self.addEventListener('message', event => {
  console.log('[SW] Message received:', event.data);
  if (event.data && event.data.type === 'SKIP_WAITING') {
    console.log('[SW] Skipping waiting...');
    self.skipWaiting();
  }
});

// Fetch - Network First for dynamic content, Cache Only for static assets
self.addEventListener('fetch', event => {
  // Skip caching for POST, PUT, DELETE requests
  if (event.request.method !== 'GET') {
    event.respondWith(fetch(event.request));
    return;
  }

  const url = new URL(event.request.url);
  
  // Never cache evaluasi pages
  if (url.pathname.includes('/evaluasi/') || url.pathname.includes('/api/')) {
    event.respondWith(
      fetch(event.request, { cache: 'no-store' })
        .catch(() => caches.match('/offline.html'))
    );
    return;
  }
  
  // ONLY cache static assets (css, js, fonts, images)
  const isStaticAsset = 
    url.pathname.startsWith('/vendor/') ||
    url.pathname.startsWith('/icons/') ||
    url.pathname.startsWith('/build/') ||
    url.pathname.match(/\.(css|js|woff2?|ttf|eot|svg|png|jpg|jpeg|gif|ico)$/);

  if (isStaticAsset) {
    // Cache-first for static assets
    event.respondWith(
      caches.match(event.request)
        .then(response => {
          if (response) {
            return response;
          }
        
          return fetch(event.request).then(response => {
            // Check if valid response
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }
          
            // Clone response
            const responseToCache = response.clone();
          
            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(event.request, responseToCache);
              });
          
            return response;
          });
        })
        .catch(() => {
          // Return offline page if available
          return caches.match('/offline.html');
        })
    );
  } else {
    // Network-first for dynamic content (HTML pages)
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // Don't cache, just return
          return response;
        })
        .catch(() => {
          // If offline, show offline page
          return caches.match('/offline.html');
        })
    );
  }
});
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  // Take control of all pages immediately
  return self.clients.claim();
});
