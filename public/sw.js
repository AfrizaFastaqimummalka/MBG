const CACHE_NAME = 'mbg-v1';

self.addEventListener('install', event => {
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.map(key => caches.delete(key)))
        )
    );
});

self.addEventListener('fetch', event => {
    // Hanya cache assets statis, bukan halaman auth
    if (event.request.destination === 'style' ||
        event.request.destination === 'script' ||
        event.request.destination === 'image') {
        event.respondWith(
            caches.open(CACHE_NAME).then(cache =>
                cache.match(event.request).then(response =>
                    response || fetch(event.request).then(res => {
                        cache.put(event.request, res.clone());
                        return res;
                    })
                )
            )
        );
    }
});