/**
 * Collection Manager - Toolzy
 * Organizes requests into local folders & collections.
 */

window.CollectionManager = (function () {
    const STORAGE_KEY = 'toolzy_api_collections';

    let collections = [
        {
            id: 'col_auth',
            name: 'Authentication APIs',
            requests: []
        },
        {
            id: 'col_testing',
            name: 'Testing & Staging',
            requests: []
        },
        {
            id: 'col_prod',
            name: 'Production APIs',
            requests: []
        }
    ];

    function init() {
        try {
            const data = localStorage.getItem(STORAGE_KEY);
            if (data) collections = JSON.parse(data);
        } catch (e) {
            console.warn('LocalStorage error in CollectionManager:', e);
        }
    }

    function save() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(collections));
        } catch (e) {
            console.error('Failed to save collections:', e);
        }
    }

    function getCollections() { return collections; }

    function addCollection(name) {
        const id = 'col_' + Date.now();
        const newCol = { id: id, name: name, requests: [] };
        collections.push(newCol);
        save();
        return newCol;
    }

    function deleteCollection(id) {
        collections = collections.filter(c => c.id !== id);
        save();
    }

    function addRequestToCollection(collectionId, reqData) {
        const col = collections.find(c => c.id === collectionId);
        if (col) {
            const reqItem = {
                id: 'req_col_' + Date.now(),
                name: reqData.name || (reqData.method + ' ' + reqData.url),
                method: reqData.method,
                url: reqData.url,
                headers: reqData.headers,
                queryParams: reqData.queryParams,
                auth: reqData.auth,
                body: reqData.body,
                bodyType: reqData.bodyType,
                formData: reqData.formData || [],
                urlEncoded: reqData.urlEncoded || []
            };
            col.requests.push(reqItem);
            save();
            return reqItem;
        }
    }

    function removeRequestFromCollection(collectionId, requestId) {
        const col = collections.find(c => c.id === collectionId);
        if (col) {
            col.requests = col.requests.filter(r => r.id !== requestId);
            save();
        }
    }

    init();

    return {
        getCollections: getCollections,
        addCollection: addCollection,
        deleteCollection: deleteCollection,
        addRequestToCollection: addRequestToCollection,
        removeRequestFromCollection: removeRequestFromCollection
    };
})();
