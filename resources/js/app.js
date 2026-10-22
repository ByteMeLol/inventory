//
const databaseName = 'inventory-offline-scans';
const storeName = 'scans';

function openScanDatabase() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(databaseName, 1);
        request.onupgradeneeded = () => request.result.createObjectStore(storeName, { autoIncrement: true });
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

async function queueScan(code) {
    const database = await openScanDatabase();
    await new Promise((resolve, reject) => {
        const transaction = database.transaction(storeName, 'readwrite');
        transaction.objectStore(storeName).add({ code, scannedAt: new Date().toISOString() });
        transaction.oncomplete = resolve;
        transaction.onerror = () => reject(transaction.error);
    });
}

async function flushScans() {
    if (! navigator.onLine) {
        return;
    }

    const database = await openScanDatabase();
    const scans = await new Promise((resolve, reject) => {
        const transaction = database.transaction(storeName, 'readonly');
        const request = transaction.objectStore(storeName).getAll();
        const keysRequest = transaction.objectStore(storeName).getAllKeys();
        request.onsuccess = () => {
            keysRequest.onsuccess = () => resolve(request.result.map((scan, index) => ({ ...scan, key: keysRequest.result[index] })));
        };
        request.onerror = () => reject(request.error);
    });

    for (const scan of scans) {
        try {
            const response = await fetch(`/inventory/lookup?code=${encodeURIComponent(scan.code)}`, {
                headers: { Accept: 'application/json' },
            });
            if (! response.ok) {
                continue;
            }
            await new Promise((resolve, reject) => {
                const transaction = database.transaction(storeName, 'readwrite');
                transaction.objectStore(storeName).delete(scan.key);
                transaction.oncomplete = resolve;
                transaction.onerror = () => reject(transaction.error);
            });
        } catch {
            return;
        }
    }
}

window.inventoryScanner = {
    queue: queueScan,
    flush: flushScans,
    supportsCamera: 'BarcodeDetector' in window,
};

window.addEventListener('online', flushScans);
flushScans();
