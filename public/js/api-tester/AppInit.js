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
            const delReqBtn = e.target.closest('.btn-delete-col-req');
            if (delReqBtn) {
                const colId = delReqBtn.getAttribute('data-col-id');
                const reqId = delReqBtn.getAttribute('data-req-id');
                window.CollectionManager.removeRequestFromCollection(colId, reqId);
                renderCollectionsList();
                if (window.showToast) window.showToast('API request removed from folder.', 'info');
                return;
            }

            const delColBtn = e.target.closest('.btn-delete-col');
            if (delColBtn) {
                const colId = delColBtn.getAttribute('data-col-id');
                if (confirm('Delete this collection folder and all its saved requests?')) {
                    window.CollectionManager.deleteCollection(colId);
                    renderCollectionsList();
                    if (window.showToast) window.showToast('Folder deleted.', 'warning');
                }
                return;
            }

            const addReqBtn = e.target.closest('.btn-add-req-to-col');
            if (addReqBtn) {
                const colId = addReqBtn.getAttribute('data-col-id');
                const compiledReq = window.RequestBuilder.getCompiledRequest();
                window.CollectionManager.addRequestToCollection(colId, compiledReq);
                renderCollectionsList();
                if (window.showToast) window.showToast('Current API saved to folder!', 'success');
                return;
            }

            const item = e.target.closest('.btn-load-col-req');
            if (item) {
                const colIdx = parseInt(item.getAttribute('data-col-idx'), 10);
                const reqIdx = parseInt(item.getAttribute('data-req-idx'), 10);
                const cols = window.CollectionManager.getCollections();
                if (cols[colIdx] && cols[colIdx].requests[reqIdx]) {
                    window.RequestBuilder.loadRequestState(cols[colIdx].requests[reqIdx]);
                    if (window.showToast) window.showToast('Request loaded from folder.', 'info');
                }
            }
        });
    }

    // Save to Collection Modal setup
    const saveModalEl = document.getElementById('saveCollectionModal');
    if (saveModalEl) {
        saveModalEl.addEventListener('show.bs.modal', setupSaveCollectionModal);
    }

    const confirmSaveBtn = document.getElementById('confirmSaveToCollectionBtn');
    if (confirmSaveBtn) {
        confirmSaveBtn.addEventListener('click', handleConfirmSaveToCollection);
    }

    const createFolderInModalBtn = document.getElementById('btnCreateFolderFromSaveModal');
    if (createFolderInModalBtn) {
        createFolderInModalBtn.addEventListener('click', function () {
            const name = prompt('Enter new folder name:');
            if (name && name.trim()) {
                const newCol = window.CollectionManager.addCollection(name.trim());
                renderCollectionsList();
                setupSaveCollectionModal();
                const sel = document.getElementById('saveReqFolderSelect');
                if (sel) sel.value = newCol.id;
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
    if (collections.length === 0) {
        container.innerHTML = `<div class="text-muted text-center py-4 small">No collection folders yet.<br>Click "+ New Folder" above to create one.</div>`;
        return;
    }

    container.innerHTML = collections.map((col, colIdx) => `
        <div class="mb-3 border rounded p-2 bg-light-subtle shadow-sm">
            <div class="fw-bold small text-dark mb-2 d-flex justify-content-between align-items-center">
                <span class="text-truncate me-1" title="${escapeHtml(col.name)}">
                    <i class="bi bi-folder-fill text-warning me-1"></i> ${escapeHtml(col.name)} 
                    <span class="badge bg-secondary rounded-pill ms-1">${col.requests.length}</span>
                </span>
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-outline-primary btn-xs py-0 px-1.5 btn-add-req-to-col" data-col-id="${col.id}" title="Save current request to this folder" style="font-size: 0.72rem;">
                        <i class="bi bi-plus-lg"></i> Add
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-xs py-0 px-1.5 btn-delete-col" data-col-id="${col.id}" title="Delete Folder" style="font-size: 0.72rem;">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            ${col.requests.length === 0 ? '<div class="small text-muted ps-2 italic py-1" style="font-size: 0.8rem;">Folder empty. Click "+ Add" or "Save" above to add API requests.</div>' : ''}
            <div class="list-group list-group-flush">
            ${col.requests.map((r, reqIdx) => `
                <div class="list-group-item list-group-item-action p-1.5 border-0 rounded mb-1 small d-flex justify-content-between align-items-center btn-load-col-req" style="cursor:pointer;" data-col-idx="${colIdx}" data-req-idx="${reqIdx}">
                    <div class="text-truncate me-2">
                        <span class="badge badge-method-${r.method} me-1" style="font-size:0.65rem;">${r.method}</span>
                        <span class="fw-semibold text-dark text-truncate d-inline-block align-middle" style="max-width: 140px;" title="${escapeHtml(r.name)}">${escapeHtml(r.name)}</span>
                    </div>
                    <button type="button" class="btn btn-link text-danger btn-sm p-0 btn-delete-col-req" data-col-id="${col.id}" data-req-id="${r.id}" title="Remove API from folder"><i class="bi bi-x-circle"></i></button>
                </div>
            `).join('')}
            </div>
        </div>
    `).join('');
}

function setupSaveCollectionModal() {
    const sel = document.getElementById('saveReqFolderSelect');
    const nameIn = document.getElementById('saveReqNameInput');
    if (!sel || !window.CollectionManager) return;

    const collections = window.CollectionManager.getCollections();
    if (collections.length === 0) {
        window.CollectionManager.addCollection('My Saved APIs');
        renderCollectionsList();
    }

    const updatedCols = window.CollectionManager.getCollections();
    sel.innerHTML = updatedCols.map(c => `<option value="${c.id}">${escapeHtml(c.name)}</option>`).join('');

    const compiledReq = window.RequestBuilder.getCompiledRequest();
    if (nameIn) {
        if (!nameIn.value || nameIn.value.trim() === '') {
            let defaultName = compiledReq.url ? compiledReq.url.replace(/^https?:\/\//, '') : 'API Request';
            if (defaultName.length > 35) defaultName = defaultName.substring(0, 35) + '...';
            nameIn.value = `${compiledReq.method} ${defaultName}`;
        }
    }
}

function handleConfirmSaveToCollection() {
    const sel = document.getElementById('saveReqFolderSelect');
    const nameIn = document.getElementById('saveReqNameInput');
    if (!sel || !window.CollectionManager) return;

    const folderId = sel.value;
    const reqName = nameIn ? nameIn.value.trim() : '';

    if (!folderId) {
        if (window.showToast) window.showToast('Please select or create a collection folder.', 'warning');
        return;
    }

    const compiledReq = window.RequestBuilder.getCompiledRequest();
    compiledReq.name = reqName || `${compiledReq.method} ${compiledReq.url}`;

    window.CollectionManager.addRequestToCollection(folderId, compiledReq);
    renderCollectionsList();

    if (window.showToast) window.showToast('API request saved to folder successfully!', 'success');

    const modalEl = document.getElementById('saveCollectionModal');
    if (modalEl && window.bootstrap) {
        const modal = window.bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }
}

function renderEnvOptions() {
    const sel = document.getElementById('envSelector');
    const modalSel = document.getElementById('modalEnvSelect');
    if (!window.EnvManager) return;

    const envs = window.EnvManager.getEnvironments();
    const active = window.EnvManager.getActiveEnv();

    const optionsHtml = Object.keys(envs).map(name => `
        <option value="${name}" ${name === active ? 'selected' : ''}>Environment: ${name}</option>
    `).join('');

    if (sel) {
        sel.innerHTML = optionsHtml;
        if (!sel.dataset.listenerAttached) {
            sel.dataset.listenerAttached = 'true';
            sel.addEventListener('change', function () {
                window.EnvManager.setActiveEnv(this.value);
                renderEnvOptions();
                if (window.showToast) window.showToast(`Environment switched to "${this.value}"`, 'info');
            });
        }
    }

    if (modalSel) {
        modalSel.innerHTML = Object.keys(envs).map(name => `
            <option value="${name}" ${name === active ? 'selected' : ''}>${name}</option>
        `).join('');

        if (!modalSel.dataset.listenerAttached) {
            modalSel.dataset.listenerAttached = 'true';
            modalSel.addEventListener('change', function () {
                window.EnvManager.setActiveEnv(this.value);
                renderEnvOptions();
                if (window.showToast) window.showToast(`Environment switched to "${this.value}"`, 'info');
            });
        }
    }

    const deleteEnvBtn = document.getElementById('btnDeleteActiveEnv');
    if (deleteEnvBtn) {
        deleteEnvBtn.disabled = (active === 'Default');
    }

    renderEnvVarsTable();
    attachEnvModalEvents();
}

function renderEnvVarsTable() {
    const tbody = document.getElementById('envVarsTableBody');
    if (!tbody || !window.EnvManager) return;

    const activeEnv = window.EnvManager.getActiveEnv();
    const envs = window.EnvManager.getEnvironments();
    const vars = envs[activeEnv] || {};
    const keys = Object.keys(vars);

    if (keys.length === 0) {
        tbody.innerHTML = `<tr><td colspan="3" class="text-muted text-center py-3 small">No environment variables defined for "${activeEnv}". Click "Add Variable" below.</td></tr>`;
        return;
    }

    tbody.innerHTML = keys.map(key => `
        <tr data-key="${escapeHtml(key)}">
            <td>
                <input type="text" class="form-control form-control-sm env-key-input font-monospace" data-old-key="${escapeHtml(key)}" value="${escapeHtml(key)}" placeholder="variable_name">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm env-val-input font-monospace" data-key="${escapeHtml(key)}" value="${escapeHtml(vars[key])}" placeholder="Value">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm btn-delete-env-var" data-key="${escapeHtml(key)}" title="Delete Variable">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function attachEnvModalEvents() {
    const tbody = document.getElementById('envVarsTableBody');
    if (tbody && !tbody.dataset.listenerAttached) {
        tbody.dataset.listenerAttached = 'true';

        tbody.addEventListener('change', function (e) {
            const activeEnv = window.EnvManager.getActiveEnv();

            if (e.target.classList.contains('env-key-input')) {
                const oldKey = e.target.getAttribute('data-old-key');
                const newKey = e.target.value.trim();
                const row = e.target.closest('tr');
                const valInput = row ? row.querySelector('.env-val-input') : null;
                const val = valInput ? valInput.value : '';

                if (oldKey && oldKey !== newKey) {
                    window.EnvManager.removeVariable(activeEnv, oldKey);
                }
                if (newKey) {
                    window.EnvManager.setVariable(activeEnv, newKey, val);
                    e.target.setAttribute('data-old-key', newKey);
                    if (valInput) valInput.setAttribute('data-key', newKey);
                }
            } else if (e.target.classList.contains('env-val-input')) {
                const row = e.target.closest('tr');
                const keyInput = row ? row.querySelector('.env-key-input') : null;
                const key = keyInput ? keyInput.value.trim() : e.target.getAttribute('data-key');
                if (key) {
                    window.EnvManager.setVariable(activeEnv, key, e.target.value);
                }
            }
        });

        tbody.addEventListener('click', function (e) {
            const delBtn = e.target.closest('.btn-delete-env-var');
            if (delBtn) {
                const key = delBtn.getAttribute('data-key');
                const activeEnv = window.EnvManager.getActiveEnv();
                if (key) {
                    window.EnvManager.removeVariable(activeEnv, key);
                    renderEnvVarsTable();
                    if (window.showToast) window.showToast(`Variable "${key}" removed.`, 'info');
                }
            }
        });
    }

    const addVarBtn = document.getElementById('btnAddEnvVarRow');
    if (addVarBtn && !addVarBtn.dataset.listenerAttached) {
        addVarBtn.dataset.listenerAttached = 'true';
        addVarBtn.addEventListener('click', function () {
            const activeEnv = window.EnvManager.getActiveEnv();
            let newKey = 'new_var';
            let count = 1;
            const envs = window.EnvManager.getEnvironments();
            const currentVars = envs[activeEnv] || {};

            while (currentVars[newKey]) {
                newKey = `new_var_${count++}`;
            }

            window.EnvManager.setVariable(activeEnv, newKey, '');
            renderEnvVarsTable();

            const lastInput = document.querySelector('#envVarsTableBody tr:last-child .env-key-input');
            if (lastInput) {
                lastInput.focus();
                lastInput.select();
            }
        });
    }

    const createEnvBtn = document.getElementById('btnCreateNewEnv');
    if (createEnvBtn && !createEnvBtn.dataset.listenerAttached) {
        createEnvBtn.dataset.listenerAttached = 'true';
        createEnvBtn.addEventListener('click', function () {
            const name = prompt('Enter new Environment name (e.g., Staging, Production):');
            if (name && name.trim()) {
                const trimmed = name.trim();
                window.EnvManager.addEnvironment(trimmed);
                window.EnvManager.setActiveEnv(trimmed);
                renderEnvOptions();
                if (window.showToast) window.showToast(`Environment "${trimmed}" created & selected.`, 'success');
            }
        });
    }

    const deleteEnvBtn = document.getElementById('btnDeleteActiveEnv');
    if (deleteEnvBtn && !deleteEnvBtn.dataset.listenerAttached) {
        deleteEnvBtn.dataset.listenerAttached = 'true';
        deleteEnvBtn.addEventListener('click', function () {
            const active = window.EnvManager.getActiveEnv();
            if (active === 'Default') {
                alert('Cannot delete Default environment.');
                return;
            }
            if (confirm(`Are you sure you want to delete environment "${active}"?`)) {
                window.EnvManager.deleteEnvironment(active);
                renderEnvOptions();
                if (window.showToast) window.showToast(`Environment "${active}" deleted.`, 'warning');
            }
        });
    }
}

function escapeHtml(str) {
    return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
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
