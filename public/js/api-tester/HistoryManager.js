/**
 * History Manager - Toolzy
 * Saves, loads, searches, replays, and manages local request history (max 50 requests).
 */

window.HistoryManager = (function () {
    const STORAGE_KEY = 'toolzy_api_history';
    const MAX_ITEMS = 50;

    let historyList = [];

    function init() {
        try {
            const data = localStorage.getItem(STORAGE_KEY);
            if (data) historyList = JSON.parse(data);
        } catch (e) {
            console.warn('LocalStorage error in HistoryManager:', e);
        }
    }

    function save() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(historyList));
        } catch (e) {
            console.error('Failed to save history:', e);
        }
    }

    function addEntry(req, res) {
        const id = 'req_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
        const entry = {
            id: id,
            timestamp: new Date().toISOString(),
            method: req.method || 'GET',
            url: req.url || '',
            headers: req.headers || {},
            queryParams: req.queryParams || [],
            auth: req.auth || { type: 'none' },
            body: req.body || '',
            bodyType: req.bodyType || 'json',
            formData: req.formData || [],
            urlEncoded: req.urlEncoded || [],
            status: res.status || 0,
            statusText: res.statusText || 'Error',
            responseTimeMs: res.responseTimeMs || 0,
            isFavorite: false,
            name: req.name || (req.method + ' ' + (req.url ? new URL(req.url, 'http://localhost').pathname : '/'))
        };

        historyList.unshift(entry);
        if (historyList.length > MAX_ITEMS) {
            historyList = historyList.slice(0, MAX_ITEMS);
        }
        save();
        return entry;
    }

    function getHistory() { return historyList; }

    function toggleFavorite(id) {
        const item = historyList.find(h => h.id === id);
        if (item) {
            item.isFavorite = !item.isFavorite;
            save();
        }
    }

    function deleteEntry(id) {
        historyList = historyList.filter(h => h.id !== id);
        save();
    }

    function clearAll() {
        historyList = [];
        save();
    }

    function renameEntry(id, newName) {
        const item = historyList.find(h => h.id === id);
        if (item) {
            item.name = newName;
            save();
        }
    }

    init();

    return {
        addEntry: addEntry,
        getHistory: getHistory,
        toggleFavorite: toggleFavorite,
        deleteEntry: deleteEntry,
        clearAll: clearAll,
        renameEntry: renameEntry
    };
})();
