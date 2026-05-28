const CACHE_NAME = "jurnal-mengajar-v2";
const OFFLINE_URL = "/offline.html";

// Assets to cache on install (only truly static files)
const STATIC_ASSETS = [
    "/offline.html",
    "/manifest.json",
    "/favicon.ico",
    "/icons/icon-192x192.png",
    "/icons/icon-512x512.png",
];

// Install event - cache static assets
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches
            .open(CACHE_NAME)
            .then((cache) => {
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                self.skipWaiting();
            }),
    );
});

// Activate event - clean up old caches
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames
                        .filter((cacheName) => cacheName !== CACHE_NAME)
                        .map((cacheName) => caches.delete(cacheName)),
                );
            })
            .then(() => {
                self.clients.claim();
            }),
    );
});

// Fetch event - serve from cache, fall back to network
self.addEventListener("fetch", (event) => {
    // Skip non-GET requests
    if (event.request.method !== "GET") {
        return;
    }

    // Skip cross-origin requests
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }

    // Skip API requests - always fetch from network
    if (
        event.request.url.includes("/api/") ||
        event.request.url.includes("/livewire/") ||
        event.request.url.includes("/sanctum/")
    ) {
        return;
    }

    event.respondWith(
        // For navigation requests (HTML pages), use network-first strategy
        event.request.mode === "navigate"
            ? fetch(event.request)
                  .then((response) => {
                      return response;
                  })
                  .catch(() => {
                      return caches.match(OFFLINE_URL);
                  })
            : // For other requests (CSS, JS, images), use cache-first strategy
              caches.match(event.request).then((response) => {
                  if (response) {
                      return response;
                  }

                  return fetch(event.request)
                      .then((response) => {
                          if (
                              !response ||
                              response.status !== 200 ||
                              response.type !== "basic"
                          ) {
                              return response;
                          }

                          const responseToCache = response.clone();

                          if (
                              event.request.url.match(
                                  /\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot)$/,
                              )
                          ) {
                              caches.open(CACHE_NAME).then((cache) => {
                                  cache.put(event.request, responseToCache);
                              });
                          }

                          return response;
                      })
                      .catch(() => {
                          return new Response("Offline", {
                              status: 503,
                              statusText: "Service Unavailable",
                          });
                      });
              }),
    );
});

// Handle background sync for offline form submissions
self.addEventListener("sync", (event) => {
    if (event.tag === "sync-attendance") {
        event.waitUntil(syncAttendance());
    }
});

// Sync attendance data when back online
async function syncAttendance() {
    try {
        const db = await openDB();
        const pendingRecords = await getAllPendingRecords(db);

        for (const record of pendingRecords) {
            try {
                const response = await fetch(record.url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": record.csrfToken,
                    },
                    body: JSON.stringify(record.data),
                });

                if (response.ok) {
                    await deleteRecord(db, record.id);
                }
            } catch (error) {
                console.error("Failed to sync record:", error);
            }
        }
    } catch (error) {
        console.error("Sync failed:", error);
    }
}

// IndexedDB helpers for offline data storage
function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open("JurnalMengajarDB", 1);

        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains("pendingSync")) {
                db.createObjectStore("pendingSync", {
                    keyPath: "id",
                    autoIncrement: true,
                });
            }
        };
    });
}

function getAllPendingRecords(db) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(["pendingSync"], "readonly");
        const store = transaction.objectStore("pendingSync");
        const request = store.getAll();

        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);
    });
}

function deleteRecord(db, id) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(["pendingSync"], "readwrite");
        const store = transaction.objectStore("pendingSync");
        const request = store.delete(id);

        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve();
    });
}

// Push notification handler
self.addEventListener("push", (event) => {
    if (!event.data) {
        return;
    }

    const data = event.data.json();
    const options = {
        body: data.body || "Ada notifikasi baru",
        icon: "/icons/icon-192x192.png",
        badge: "/icons/icon-72x72.png",
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: data.primaryKey || 1,
            url: data.url || "/",
        },
        actions: [
            {
                action: "open",
                title: "Buka",
            },
            {
                action: "close",
                title: "Tutup",
            },
        ],
    };

    event.waitUntil(
        self.registration.showNotification(
            data.title || "Jurnal Mengajar",
            options,
        ),
    );
});

// Notification click handler
self.addEventListener("notificationclick", (event) => {
    event.notification.close();

    if (event.action === "close") {
        return;
    }

    const urlToOpen = event.notification.data?.url || "/";

    event.waitUntil(
        clients
            .matchAll({ type: "window", includeUncontrolled: true })
            .then((clientList) => {
                // Check if there's already a window/tab open
                for (const client of clientList) {
                    if (client.url === urlToOpen && "focus" in client) {
                        return client.focus();
                    }
                }
                // If not, open a new window
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            }),
    );
});
