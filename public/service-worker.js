self.addEventListener('install', function () {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', function () {
    // Keep server-rendered business data fresh. The service worker exists for PWA install support.
});
