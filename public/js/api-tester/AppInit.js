/**
 * AppInit Main Coordinator - Toolzy API Request Tester & Performance Analyzer
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialize Modules
    window.RequestBuilder.init();
    renderHistoryList();
    renderCollectionsList();
    renderEnvOptions();

    // 2. Attach Send Request Actions
    const sendBtn = document.getElementById('sendRequestBtn');
    if (sendBtn) {
        sendBtn.addEventListener('click', handleSendRequest);
    }

    // Keyboard shortcut Ctrl + Enter to send request
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            handleSendRequest();
        }
    });

    // 3. Sample Endpoints Handler
    const sampleBtn = document.getElementById('loadSampleApiBtn');
    if (sampleBtn) {
        sampleBtn.addEventListener('click', loadSampleEndpoint);
    }

    // 4. Code Generator Selector
    const codeLangSelect = document.getElementById('codeLangSelect');
    if (codeLangSelect) {
        codeLangSelect.addEventListener('change', updateGeneratedCode);
    }

    const copyCodeBtn = document.getElementById('copyCodeSnippetBtn');
    if (copyCodeBtn) {
        copyCodeBtn.addEventListener('click', function () {
            const pre = document.getElementById('generatedCodeSnippet');
            if (pre && window.copyToClipboard) {
                window.copyToClipboard(pre.textContent, this, 'Code copied to clipboard!');
                if (window.trackEvent) window.trackEvent('Copy Action', { type: 'code_snippet' });
            }
        });
    }

    // 5. Import Modal Handlers
    const importConfirmBtn = document.getElementById('confirmImportBtn');
    if (importConfirmBtn) {
        importConfirmBtn.addEventListener('click', handleImport);
    }

    // 6. Export Handlers
    const exportPostmanBtn = document.getElementById('exportPostmanBtn');
    if (exportPostmanBtn) exportPostmanBtn.addEventListener('click', handleExportPostman);

    const exportMarkdownBtn = document.getElementById('exportMarkdownBtn');
    if (exportMarkdownBtn) exportMarkdownBtn.addEventListener('click', handleExportMarkdown);

    // 7. History & Collection Action Handlers
    const clearHistBtn = document.getElementById('clearHistoryBtn');
    if (clearHistBtn) {
        clearHistBtn.addEventListener('click', function () {
            window.HistoryManager.clearAll();
            renderHistoryList();
        });
    }

    const addColFolderBtn = document.getElementById('addCollectionFolderBtn');
    if (addColFolderBtn) {
        addColFolderBtn.addEventListener('click', function () {
            const name = prompt('Enter new folder name:');
            if (name) {
                window.CollectionManager.addCollection(name);
                renderCollectionsList();
            }
        });
    }

    // Delegation on History List
    const historyContainer = document.getElementById('historyListContainer');
    if (historyContainer && !historyContainer.dataset.listenerAttached) {
        historyContainer.dataset.listenerAttached = 'true';
        historyContainer.addEventListener('click', function (e) {
            const replayEl = e.target.closest('.btn-replay-history');
            if (replayEl) {
                window.replayHistoryItem(replayEl.getAttribute('data-id'));
                return;
            }
            const delBtn = e.target.closest('.btn-delete-history');
            if (delBtn) {
                window.deleteHistoryItem(delBtn.getAttribute('data-id'));
                return;
            }
        });
    }

    // Delegation on Collections List
    const collectionsContainer = document.getElementById('collectionsContainer');
    if (collectionsContainer && !collectionsContainer.dataset.listenerAttached) {
        collectionsContainer.dataset.listenerAttached = 'true';
        collectionsContainer.addEventListener('click', function (e) {
            const item = e.target.closest('.btn-load-col-req');
            if (item) {
                const colIdx = parseInt(item.getAttribute('data-col-idx'), 10);
                const reqIdx = parseInt(item.getAttribute('data-req-idx'), 10);
                const cols = window.CollectionManager.getCollections();
                if (cols[colIdx] && cols[colIdx].requests[reqIdx]) {
                    window.RequestBuilder.loadRequestState(cols[colIdx].requests[reqIdx]);
                }
            }
        });
    }

    // Track page view event
    if (window.trackEvent) {
        window.trackEvent('API Tester Initialized', { version: '1.0' });
    }
});

let currentLastResponse = null;

async function handleSendRequest() {
    const sendBtn = document.getElementById('sendRequestBtn');
    const compiledReq = window.RequestBuilder.getCompiledRequest();

    if (!compiledReq.url) {
        if (window.showToast) window.showToast('Please enter an API URL to test.', 'warning');
        return;
    }

    // Update UI Loading State
    if (sendBtn) {
        sendBtn.disabled = true;
        sendBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...`;
    }

    if (window.trackEvent) {
        window.trackEvent('Request Sent', { method: compiledReq.method, url: compiledReq.url });
    }

    try {
        const response = await window.ApiEngine.sendRequest({
            url: compiledReq.url,
            method: compiledReq.method,
            headers: compiledReq.headers,
            body: compiledReq.body
        });

        currentLastResponse = response;

        // Render UI Displays
        window.ResponseViewer.renderResponse(response);
        window.PerformanceAnalyzer.render(response);
        updateGeneratedCode();

        // Save to Local History
        const histEntry = window.HistoryManager.addEntry(compiledReq, response);
        renderHistoryList();

        if (window.trackEvent) {
            window.trackEvent(response.ok ? 'Response Received' : 'Request Failed', {
                status: response.status,
                time: response.responseTimeMs
            });
        }
    } catch (err) {
        console.error('Request execution error:', err);
        if (window.showToast) window.showToast('Execution error: ' + err.message, 'danger');
    } finally {
        if (sendBtn) {
            sendBtn.disabled = false;
            sendBtn.innerHTML = `<i class="bi bi-send-fill me-1"></i> Send <span class="badge bg-white bg-opacity-25 ms-1 d-none d-md-inline">Ctrl+Enter</span>`;
        }
    }
}

function updateGeneratedCode() {
    const langSel = document.getElementById('codeLangSelect');
    const snippetPre = document.getElementById('generatedCodeSnippet');
    if (!langSel || !snippetPre) return;

    const req = window.RequestBuilder.getCompiledRequest();
    const lang = langSel.value || 'curl';
    const code = window.CodeGenerator.generate(lang, req);
    snippetPre.textContent = code;

    if (window.trackEvent) {
        window.trackEvent('Code Generated', { language: lang });
    }
}

function loadSampleEndpoint() {
    const samples = [
        {
            method: 'GET',
            url: 'https://jsonplaceholder.typicode.com/users/1',
            headers: { 'Accept': 'application/json' },
            queryParams: [{ key: 'active', value: 'true', enabled: true }],
            body: ''
        },
        {
            method: 'POST',
            url: 'https://jsonplaceholder.typicode.com/posts',
            headers: { 'Content-Type': 'application/json' },
            queryParams: [],
            body: JSON.stringify({ title: 'Testing API with Toolzy', body: 'Browser-based REST client.', userId: 1 }, null, 2)
        }
    ];

    const chosen = samples[Math.floor(Math.random() * samples.length)];
    window.RequestBuilder.loadRequestState(chosen);
    if (window.showToast) window.showToast('Sample request loaded!', 'info');
}

function renderHistoryList() {
    const container = document.getElementById('historyListContainer');
    if (!container) return;

    const list = window.HistoryManager.getHistory();
    if (list.length === 0) {
        container.innerHTML = `<div class="text-muted text-center py-4 small">No history items yet.<br>Sent requests will be logged here locally.</div>`;
        return;
    }

    container.innerHTML = list.map(item => {
        let badgeClass = 'bg-secondary';
        if (item.status >= 200 && item.status < 300) badgeClass = 'bg-success';
        if (item.status >= 400) badgeClass = 'bg-danger';

        return `
            <div class="p-2 border-bottom history-item d-flex align-items-center justify-content-between">
                <div class="text-truncate flex-grow-1 me-2 btn-replay-history" style="cursor:pointer;" data-id="${item.id}">
                    <span class="badge badge-method-${item.method} me-1" style="font-size:0.68rem;">${item.method}</span>
                    <span class="small font-monospace text-dark text-truncate d-inline-block" style="max-width: 170px;" title="${item.url}">${item.url}</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="badge ${badgeClass} text-white" style="font-size:0.65rem;">${item.status || 'Err'}</span>
                    <button class="btn btn-link text-secondary btn-sm p-0 ms-1 btn-delete-history" data-id="${item.id}" title="Delete"><i class="bi bi-x"></i></button>
                </div>
            </div>
        `;
    }).join('');
}

window.replayHistoryItem = function (id) {
    const list = window.HistoryManager.getHistory();
    const item = list.find(h => h.id === id);
    if (item) {
        window.RequestBuilder.loadRequestState(item);
        if (window.showToast) window.showToast('Request loaded from history.', 'info');
    }
};

window.deleteHistoryItem = function (id) {
    window.HistoryManager.deleteEntry(id);
    renderHistoryList();
};

function renderCollectionsList() {
    const container = document.getElementById('collectionsContainer');
    if (!container) return;

    const collections = window.CollectionManager.getCollections();
    container.innerHTML = collections.map((col, colIdx) => `
        <div class="mb-3">
            <div class="fw-bold small text-uppercase text-secondary mb-1 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-folder-fill text-warning me-1"></i> ${col.name}</span>
                <span class="badge bg-light text-dark">${col.requests.length}</span>
            </div>
            ${col.requests.length === 0 ? '<div class="small text-muted ps-3">Folder empty</div>' : ''}
            ${col.requests.map((r, reqIdx) => `
                <div class="ps-3 py-1 border-start border-2 border-primary small d-flex justify-content-between align-items-center hover-bg-light btn-load-col-req" style="cursor:pointer;" data-col-idx="${colIdx}" data-req-idx="${reqIdx}">
                    <span><strong class="text-primary">${r.method}</strong> ${r.name}</span>
                </div>
            `).join('')}
        </div>
    `).join('');
}

function renderEnvOptions() {
    const sel = document.getElementById('envSelector');
    if (!sel || !window.EnvManager) return;

    const envs = window.EnvManager.getEnvironments();
    const active = window.EnvManager.getActiveEnv();

    sel.innerHTML = Object.keys(envs).map(name => `
        <option value="${name}" ${name === active ? 'selected' : ''}>Environment: ${name}</option>
    `).join('');

    sel.addEventListener('change', function () {
        window.EnvManager.setActiveEnv(this.value);
        if (window.showToast) window.showToast(`Environment switched to "${this.value}"`, 'info');
    });
}

function handleImport() {
    const text = document.getElementById('importSourceTextarea')?.value || '';
    if (!text.trim()) return;

    if (text.trim().startsWith('curl')) {
        const parsed = window.ImportExport.parseCurl(text);
        if (parsed) {
            window.RequestBuilder.loadRequestState(parsed);
            if (window.showToast) window.showToast('cURL command successfully imported!', 'success');
        }
    } else {
        try {
            const jsonObj = JSON.parse(text);
            if (jsonObj.info && jsonObj.item) {
                const requests = window.ImportExport.parsePostmanCollection(jsonObj);
                if (requests.length > 0) {
                    window.RequestBuilder.loadRequestState(requests[0]);
                    if (window.showToast) window.showToast(`Imported Postman Collection with ${requests.length} request(s)!`, 'success');
                }
            } else if (jsonObj.paths) {
                const requests = window.ImportExport.parseOpenApi(jsonObj);
                if (requests.length > 0) {
                    window.RequestBuilder.loadRequestState(requests[0]);
                    if (window.showToast) window.showToast(`Imported OpenAPI spec with ${requests.length} endpoint(s)!`, 'success');
                }
            }
        } catch (e) {
            if (window.showToast) window.showToast('Failed to parse import content.', 'danger');
        }
    }

    // Close Bootstrap Modal if open
    const modalEl = document.getElementById('importModal');
    if (modalEl && window.bootstrap) {
        const modal = window.bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }
}

function handleExportPostman() {
    const req = window.RequestBuilder.getCompiledRequest();
    const col = window.ImportExport.exportToPostman('Exported Toolzy Collection', [req]);
    const blob = new Blob([JSON.stringify(col, null, 2)], { type: 'application/json' });
    downloadBlob(blob, 'toolzy-api-collection.postman_collection.json');
}

function handleExportMarkdown() {
    const req = window.RequestBuilder.getCompiledRequest();
    const md = window.ImportExport.exportToMarkdown(req, currentLastResponse);
    const blob = new Blob([md], { type: 'text/markdown' });
    downloadBlob(blob, 'api-documentation.md');
}

function downloadBlob(blob, filename) {
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = filename;
    a.click();
    if (window.showToast) window.showToast('Export downloaded successfully!', 'success');
}
