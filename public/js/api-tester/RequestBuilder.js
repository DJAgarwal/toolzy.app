/**
 * Request Builder Module - Toolzy
 * Manages HTTP method, URL, Query params sync, Header table with presets, Auth modes, JWT decoding, Body formatters.
 */

window.RequestBuilder = (function () {

    let queryParamsState = [];

    let headersState = [
        { key: 'Accept', value: 'application/json', enabled: true }
    ];

    let formDataState = [];
    let urlEncodedState = [];

    function init() {
        const urlInput = document.getElementById('requestUrlInput');
        if (urlInput && urlInput.value) {
            onUrlInputChanged.call(urlInput);
        } else {
            renderQueryParamsTable();
        }
        renderHeadersTable();
        renderFormDataTable();
        renderUrlEncodedTable();
        attachEvents();
        updateBadges();
    }

    function attachEvents() {
        const urlInput = document.getElementById('requestUrlInput');
        if (urlInput) {
            urlInput.addEventListener('input', onUrlInputChanged);
        }

        const methodSelect = document.getElementById('requestMethodSelect');
        if (methodSelect) {
            methodSelect.addEventListener('change', function () {
                this.className = `form-select method-select fw-bold badge-method-${this.value}`;
            });
        }

        const authTypeSelect = document.getElementById('authTypeSelect');
        if (authTypeSelect) {
            authTypeSelect.addEventListener('change', onAuthTypeChanged);
        }

        const bodyTypeSelect = document.getElementById('bodyTypeSelect');
        if (bodyTypeSelect) {
            bodyTypeSelect.addEventListener('change', onBodyTypeChanged);
        }

        const jwtInput = document.getElementById('authJwtTokenInput');
        if (jwtInput) {
            jwtInput.addEventListener('input', decodeJwtPayload);
        }

        const addQpBtn = document.getElementById('addQueryParamBtn');
        if (addQpBtn) addQpBtn.addEventListener('click', () => addQueryParam());

        const clearQpBtn = document.getElementById('clearQueryParamsBtn');
        if (clearQpBtn) clearQpBtn.addEventListener('click', () => clearQueryParams());

        const addHBtn = document.getElementById('addHeaderBtn');
        if (addHBtn) addHBtn.addEventListener('click', () => addHeader());

        const formatJsonBtn = document.getElementById('formatJsonBodyBtn');
        if (formatJsonBtn) formatJsonBtn.addEventListener('click', () => formatJsonBody());

        const minifyJsonBtn = document.getElementById('minifyJsonBodyBtn');
        if (minifyJsonBtn) minifyJsonBtn.addEventListener('click', () => minifyJsonBody());

        const addFdBtn = document.getElementById('addFormDataBtn');
        if (addFdBtn) addFdBtn.addEventListener('click', () => addFormData());

        const addUeBtn = document.getElementById('addUrlEncodedBtn');
        if (addUeBtn) addUeBtn.addEventListener('click', () => addUrlEncoded());

        document.querySelectorAll('.btn-preset-header').forEach(btn => {
            btn.addEventListener('click', function () {
                const preset = this.getAttribute('data-preset');
                if (preset) addPresetHeader(preset);
            });
        });

        // Event delegation for Query Params
        const qpTbody = document.getElementById('queryParamsTbody');
        if (qpTbody && !qpTbody.dataset.listenerAttached) {
            qpTbody.dataset.listenerAttached = 'true';
            qpTbody.addEventListener('input', function (e) {
                const idx = parseInt(e.target.getAttribute('data-idx'), 10);
                if (isNaN(idx)) return;
                if (e.target.classList.contains('qp-key')) updateQueryParam(idx, 'key', e.target.value);
                if (e.target.classList.contains('qp-val')) updateQueryParam(idx, 'value', e.target.value);
            });
            qpTbody.addEventListener('change', function (e) {
                const idx = parseInt(e.target.getAttribute('data-idx'), 10);
                if (!isNaN(idx) && e.target.classList.contains('qp-toggle')) toggleQueryParam(idx);
            });
            qpTbody.addEventListener('click', function (e) {
                const delBtn = e.target.closest('.qp-delete');
                if (delBtn) {
                    const idx = parseInt(delBtn.getAttribute('data-idx'), 10);
                    if (!isNaN(idx)) deleteQueryParam(idx);
                }
            });
        }

        // Event delegation for Headers
        const hTbody = document.getElementById('headersTbody');
        if (hTbody && !hTbody.dataset.listenerAttached) {
            hTbody.dataset.listenerAttached = 'true';
            hTbody.addEventListener('input', function (e) {
                const idx = parseInt(e.target.getAttribute('data-idx'), 10);
                if (isNaN(idx)) return;
                if (e.target.classList.contains('h-key')) updateHeader(idx, 'key', e.target.value);
                if (e.target.classList.contains('h-val')) updateHeader(idx, 'value', e.target.value);
            });
            hTbody.addEventListener('change', function (e) {
                const idx = parseInt(e.target.getAttribute('data-idx'), 10);
                if (!isNaN(idx) && e.target.classList.contains('h-toggle')) toggleHeader(idx);
            });
            hTbody.addEventListener('click', function (e) {
                const delBtn = e.target.closest('.h-delete');
                if (delBtn) {
                    const idx = parseInt(delBtn.getAttribute('data-idx'), 10);
                    if (!isNaN(idx)) deleteHeader(idx);
                }
            });
        }

        // Event delegation for Form Data
        const fdTbody = document.getElementById('formDataTbody');
        if (fdTbody && !fdTbody.dataset.listenerAttached) {
            fdTbody.dataset.listenerAttached = 'true';
            fdTbody.addEventListener('input', function (e) {
                const idx = parseInt(e.target.getAttribute('data-idx'), 10);
                if (isNaN(idx)) return;
                if (e.target.classList.contains('fd-key')) updateFormData(idx, 'key', e.target.value);
                if (e.target.classList.contains('fd-val')) updateFormData(idx, 'value', e.target.value);
            });
            fdTbody.addEventListener('click', function (e) {
                const delBtn = e.target.closest('.fd-delete');
                if (delBtn) {
                    const idx = parseInt(delBtn.getAttribute('data-idx'), 10);
                    if (!isNaN(idx)) deleteFormData(idx);
                }
            });
        }

        // Event delegation for URL Encoded
        const ueTbody = document.getElementById('urlEncodedTbody');
        if (ueTbody && !ueTbody.dataset.listenerAttached) {
            ueTbody.dataset.listenerAttached = 'true';
            ueTbody.addEventListener('input', function (e) {
                const idx = parseInt(e.target.getAttribute('data-idx'), 10);
                if (isNaN(idx)) return;
                if (e.target.classList.contains('ue-key')) updateUrlEncoded(idx, 'key', e.target.value);
                if (e.target.classList.contains('ue-val')) updateUrlEncoded(idx, 'value', e.target.value);
            });
            ueTbody.addEventListener('click', function (e) {
                const delBtn = e.target.closest('.ue-delete');
                if (delBtn) {
                    const idx = parseInt(delBtn.getAttribute('data-idx'), 10);
                    if (!isNaN(idx)) deleteUrlEncoded(idx);
                }
            });
        }
    }

    // --- Query Parameters ---
    function renderQueryParamsTable() {
        const tbody = document.getElementById('queryParamsTbody');
        if (!tbody) return;

        if (queryParamsState.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-muted text-center py-2">No query parameters. Click "Add Parameter" below.</td></tr>`;
            return;
        }

        tbody.innerHTML = queryParamsState.map((param, idx) => `
            <tr>
                <td class="text-center" style="width: 40px;">
                    <input type="checkbox" class="form-check-input qp-toggle" data-idx="${idx}" ${param.enabled ? 'checked' : ''}>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm kv-input qp-key" data-idx="${idx}" placeholder="Key" value="${escapeAttr(param.key)}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm kv-input qp-val" data-idx="${idx}" placeholder="Value" value="${escapeAttr(param.value)}">
                </td>
                <td class="text-center" style="width: 50px;">
                    <button type="button" class="btn btn-outline-danger btn-sm qp-delete" data-idx="${idx}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        `).join('');
    }

    function addQueryParam(key = '', val = '') {
        queryParamsState.push({ key: key, value: val, enabled: true });
        renderQueryParamsTable();
        syncParamsToUrl();
    }

    function updateQueryParam(idx, field, value) {
        if (queryParamsState[idx]) {
            queryParamsState[idx][field] = value;
            syncParamsToUrl();
        }
    }

    function toggleQueryParam(idx) {
        if (queryParamsState[idx]) {
            queryParamsState[idx].enabled = !queryParamsState[idx].enabled;
            renderQueryParamsTable();
            syncParamsToUrl();
        }
    }

    function deleteQueryParam(idx) {
        queryParamsState.splice(idx, 1);
        renderQueryParamsTable();
        syncParamsToUrl();
    }

    function clearQueryParams() {
        queryParamsState = [];
        renderQueryParamsTable();
        syncParamsToUrl();
    }

    function syncParamsToUrl() {
        const urlInput = document.getElementById('requestUrlInput');
        if (!urlInput) return;

        let rawUrl = urlInput.value.trim();
        if (!rawUrl) return;

        try {
            const hasProtocol = /^https?:\/\//i.test(rawUrl);
            const dummyBase = hasProtocol ? '' : 'https://';
            const u = new URL(dummyBase + rawUrl);

            // Clear existing query params in URL
            u.search = '';
            queryParamsState.forEach(p => {
                if (p.enabled && p.key) {
                    u.searchParams.append(p.key, p.value);
                }
            });

            urlInput.value = hasProtocol ? u.href : u.href.replace('https://', '');
            updateBadges();
        } catch (e) {}
    }

    function onUrlInputChanged() {
        const rawUrl = this.value.trim();
        if (!rawUrl) return;

        try {
            const hasProtocol = /^https?:\/\//i.test(rawUrl);
            const dummyBase = hasProtocol ? '' : 'https://';
            const u = new URL(dummyBase + rawUrl);
            let newParams = [];
            u.searchParams.forEach((v, k) => {
                newParams.push({ key: k, value: v, enabled: true });
            });
            queryParamsState = newParams;
            renderQueryParamsTable();
            updateBadges();
        } catch (e) {}
    }

    // --- Headers ---
    function renderHeadersTable() {
        const tbody = document.getElementById('headersTbody');
        if (!tbody) return;

        if (headersState.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-muted text-center py-2">No custom headers. Click "Add Header" below.</td></tr>`;
            return;
        }

        tbody.innerHTML = headersState.map((h, idx) => `
            <tr>
                <td class="text-center" style="width: 40px;">
                    <input type="checkbox" class="form-check-input h-toggle" data-idx="${idx}" ${h.enabled ? 'checked' : ''}>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm kv-input h-key" data-idx="${idx}" placeholder="Header Name" value="${escapeAttr(h.key)}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm kv-input h-val" data-idx="${idx}" placeholder="Header Value" value="${escapeAttr(h.value)}">
                </td>
                <td class="text-center" style="width: 50px;">
                    <button type="button" class="btn btn-outline-danger btn-sm h-delete" data-idx="${idx}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        `).join('');
    }

    function addHeader(key = '', val = '') {
        headersState.push({ key: key, value: val, enabled: true });
        renderHeadersTable();
    }

    function updateHeader(idx, field, value) {
        if (headersState[idx]) {
            headersState[idx][field] = value;
        }
    }

    function toggleHeader(idx) {
        if (headersState[idx]) {
            headersState[idx].enabled = !headersState[idx].enabled;
            renderHeadersTable();
        }
    }

    function deleteHeader(idx) {
        headersState.splice(idx, 1);
        renderHeadersTable();
    }

    function addPresetHeader(presetType) {
        const presets = {
            'json': { key: 'Content-Type', val: 'application/json' },
            'xml': { key: 'Content-Type', val: 'application/xml' },
            'form': { key: 'Content-Type', val: 'multipart/form-data' },
            'urlencoded': { key: 'Content-Type', val: 'application/x-www-form-urlencoded' },
            'accept_json': { key: 'Accept', val: 'application/json' },
            'user_agent': { key: 'User-Agent', val: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Toolzy-API-Tester/1.0' }
        };

        if (presets[presetType]) {
            addHeader(presets[presetType].key, presets[presetType].val);
        }
    }

    // --- Authentication ---
    function onAuthTypeChanged() {
        const val = this.value;
        document.querySelectorAll('.auth-section').forEach(sec => sec.classList.add('d-none'));

        const targetSec = document.getElementById('authSec_' + val);
        if (targetSec) targetSec.classList.remove('d-none');
    }

    function decodeJwtPayload() {
        const token = this.value.trim();
        const outputEl = document.getElementById('jwtPayloadOutput');
        if (!outputEl) return;

        if (!token) {
            outputEl.textContent = 'Paste a JWT token to decode its payload.';
            return;
        }

        try {
            const parts = token.split('.');
            if (parts.length < 2) {
                outputEl.textContent = 'Invalid JWT format (must have 3 parts separated by dots).';
                return;
            }
            const payloadDecoded = atob(parts[1].replace(/-/g, '+').replace(/_/g, '/'));
            const json = JSON.parse(payloadDecoded);
            outputEl.textContent = JSON.stringify(json, null, 2);
        } catch (e) {
            outputEl.textContent = 'Error decoding JWT payload: ' + e.message;
        }
    }

    // --- Body ---
    function onBodyTypeChanged() {
        const val = this.value;
        document.querySelectorAll('.body-section').forEach(sec => sec.classList.add('d-none'));

        const targetSec = document.getElementById('bodySec_' + val);
        if (targetSec) targetSec.classList.remove('d-none');

        // Automatically set or adjust Content-Type header
        if (val === 'json') addPresetHeader('json');
        if (val === 'xml') addPresetHeader('xml');
        if (val === 'form') addPresetHeader('form');
        if (val === 'urlencoded') addPresetHeader('urlencoded');
    }

    function formatJsonBody() {
        const textarea = document.getElementById('requestBodyTextarea');
        if (!textarea) return;
        try {
            const json = JSON.parse(textarea.value);
            textarea.value = JSON.stringify(json, null, 2);
            if (window.showToast) window.showToast('JSON beautified successfully!', 'success');
        } catch (e) {
            if (window.showToast) window.showToast('Invalid JSON: ' + e.message, 'danger');
        }
    }

    function minifyJsonBody() {
        const textarea = document.getElementById('requestBodyTextarea');
        if (!textarea) return;
        try {
            const json = JSON.parse(textarea.value);
            textarea.value = JSON.stringify(json);
            if (window.showToast) window.showToast('JSON minified successfully!', 'success');
        } catch (e) {
            if (window.showToast) window.showToast('Invalid JSON: ' + e.message, 'danger');
        }
    }

    // --- Form Data ---
    function renderFormDataTable() {
        const tbody = document.getElementById('formDataTbody');
        if (!tbody) return;
        tbody.innerHTML = formDataState.map((item, idx) => `
            <tr>
                <td><input type="text" class="form-control form-control-sm kv-input fd-key" data-idx="${idx}" placeholder="Key" value="${escapeAttr(item.key)}"></td>
                <td><input type="text" class="form-control form-control-sm kv-input fd-val" data-idx="${idx}" placeholder="Value" value="${escapeAttr(item.value)}"></td>
                <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm fd-delete" data-idx="${idx}"><i class="bi bi-trash"></i></button></td>
            </tr>
        `).join('');
    }
    function addFormData() { formDataState.push({ key: '', value: '' }); renderFormDataTable(); }
    function updateFormData(i, k, v) { if (formDataState[i]) formDataState[i][k] = v; }
    function deleteFormData(i) { formDataState.splice(i, 1); renderFormDataTable(); }

    // --- URL Encoded ---
    function renderUrlEncodedTable() {
        const tbody = document.getElementById('urlEncodedTbody');
        if (!tbody) return;
        tbody.innerHTML = urlEncodedState.map((item, idx) => `
            <tr>
                <td><input type="text" class="form-control form-control-sm kv-input ue-key" data-idx="${idx}" placeholder="Key" value="${escapeAttr(item.key)}"></td>
                <td><input type="text" class="form-control form-control-sm kv-input ue-val" data-idx="${idx}" placeholder="Value" value="${escapeAttr(item.value)}"></td>
                <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm ue-delete" data-idx="${idx}"><i class="bi bi-trash"></i></button></td>
            </tr>
        `).join('');
    }
    function addUrlEncoded() { urlEncodedState.push({ key: '', value: '' }); renderUrlEncodedTable(); }
    function updateUrlEncoded(i, k, v) { if (urlEncodedState[i]) urlEncodedState[i][k] = v; }
    function deleteUrlEncoded(i) { urlEncodedState.splice(i, 1); renderUrlEncodedTable(); }

    function updateBadges() {
        const pBadge = document.getElementById('paramsCountBadge');
        if (pBadge) pBadge.textContent = queryParamsState.filter(p => p.enabled).length;

        const hBadge = document.getElementById('headersCountBadge');
        if (hBadge) hBadge.textContent = headersState.filter(h => h.enabled).length;
    }

    /**
     * Compiles full request state into an object ready for execution/code generation
     */
    function getCompiledRequest() {
        const method = document.getElementById('requestMethodSelect')?.value || 'GET';
        let rawUrl = document.getElementById('requestUrlInput')?.value.trim() || '';

        // Substitute environment variables {{var}}
        rawUrl = window.EnvManager ? window.EnvManager.substitute(rawUrl) : rawUrl;

        // Auto-prefix protocol if missing (prevents fetch from converting URL into relative path on local server resulting in 404)
        if (rawUrl && !/^https?:\/\//i.test(rawUrl)) {
            rawUrl = 'https://' + rawUrl;
        }

        // Compile Headers
        let finalHeaders = {};
        headersState.forEach(h => {
            if (h.enabled && h.key) {
                // Avoid sending User-Agent as custom header because browser fetch rejects forbidden headers & fails CORS preflights
                if (h.key.toLowerCase() === 'user-agent') return;
                finalHeaders[h.key] = window.EnvManager ? window.EnvManager.substitute(h.value) : h.value;
            }
        });

        // Compile Auth
        const authType = document.getElementById('authTypeSelect')?.value || 'none';
        if (authType === 'bearer') {
            const token = document.getElementById('authBearerTokenInput')?.value.trim();
            if (token) finalHeaders['Authorization'] = 'Bearer ' + (window.EnvManager ? window.EnvManager.substitute(token) : token);
        } else if (authType === 'basic') {
            const user = document.getElementById('authBasicUserInput')?.value || '';
            const pass = document.getElementById('authBasicPassInput')?.value || '';
            finalHeaders['Authorization'] = 'Basic ' + btoa(user + ':' + pass);
        } else if (authType === 'apikey') {
            const key = document.getElementById('authApiKeyNameInput')?.value.trim();
            const val = document.getElementById('authApiKeyValueInput')?.value.trim();
            const loc = document.getElementById('authApiKeyLocSelect')?.value || 'header';
            if (key && val) {
                if (loc === 'header') {
                    finalHeaders[key] = val;
                } else {
                    rawUrl += (rawUrl.includes('?') ? '&' : '?') + encodeURIComponent(key) + '=' + encodeURIComponent(val);
                }
            }
        }

        // Compile Body
        const bodyType = document.getElementById('bodyTypeSelect')?.value || 'none';
        let finalBody = null;

        if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())) {
            if (bodyType === 'json' || bodyType === 'xml' || bodyType === 'raw') {
                const rawBody = document.getElementById('requestBodyTextarea')?.value || '';
                finalBody = window.EnvManager ? window.EnvManager.substitute(rawBody) : rawBody;
            } else if (bodyType === 'urlencoded') {
                const params = new URLSearchParams();
                urlEncodedState.forEach(i => {
                    if (i.key) params.append(i.key, window.EnvManager ? window.EnvManager.substitute(i.value) : i.value);
                });
                finalBody = params.toString();
            } else if (bodyType === 'form') {
                const fd = new FormData();
                formDataState.forEach(i => {
                    if (i.key) fd.append(i.key, window.EnvManager ? window.EnvManager.substitute(i.value) : i.value);
                });
                finalBody = fd;
            }
        }

        return {
            method: method,
            url: rawUrl,
            headers: finalHeaders,
            queryParams: queryParamsState,
            body: finalBody,
            bodyType: bodyType,
            formData: formDataState,
            urlEncoded: urlEncodedState
        };
    }

    function loadRequestState(req) {
        if (!req) return;

        // 1. Method
        if (req.method) {
            const mSel = document.getElementById('requestMethodSelect');
            if (mSel) {
                const upperM = req.method.toUpperCase();
                mSel.value = upperM;
                mSel.className = `form-select method-select fw-bold badge-method-${upperM}`;
            }
        }

        // 2. URL & Query Params
        if (req.url) {
            const urlIn = document.getElementById('requestUrlInput');
            if (urlIn) urlIn.value = req.url;
        }

        if (req.queryParams && Array.isArray(req.queryParams)) {
            queryParamsState = req.queryParams;
            renderQueryParamsTable();
        } else if (req.url && req.url.includes('?')) {
            try {
                const u = new URL(req.url.startsWith('http') ? req.url : 'https://' + req.url);
                let qp = [];
                u.searchParams.forEach((v, k) => qp.push({ key: k, value: v, enabled: true }));
                queryParamsState = qp;
                renderQueryParamsTable();
            } catch (e) {}
        } else {
            queryParamsState = [];
            renderQueryParamsTable();
        }

        // 3. Headers & Auth detection
        let hState = [];
        let authBearerToken = null;

        if (req.headers) {
            Object.keys(req.headers).forEach(k => {
                const val = req.headers[k];
                if (k.toLowerCase() === 'authorization' && typeof val === 'string' && val.startsWith('Bearer ')) {
                    authBearerToken = val.substring(7).trim();
                } else {
                    hState.push({ key: k, value: val, enabled: true });
                }
            });
        }
        headersState = hState;
        renderHeadersTable();

        if (authBearerToken) {
            const bearerInput = document.getElementById('authBearerTokenInput');
            const authSelect = document.getElementById('authTypeSelect');
            if (bearerInput && authSelect) {
                authSelect.value = 'bearer';
                bearerInput.value = authBearerToken;
                onAuthTypeChanged.call(authSelect);
            }
        } else if (req.auth && req.auth.type) {
            const authSelect = document.getElementById('authTypeSelect');
            if (authSelect) {
                authSelect.value = req.auth.type;
                onAuthTypeChanged.call(authSelect);
            }
        }

        // 4. Body & Body Type
        const bTypeSel = document.getElementById('bodyTypeSelect');
        const bType = req.bodyType || (req.formData && req.formData.length ? 'form' : (req.urlEncoded && req.urlEncoded.length ? 'urlencoded' : (req.body ? 'json' : 'none')));

        if (bTypeSel) {
            bTypeSel.value = bType;
            onBodyTypeChanged.call(bTypeSel);
        }

        if (req.formData && Array.isArray(req.formData)) {
            formDataState = req.formData;
            renderFormDataTable();
        }
        if (req.urlEncoded && Array.isArray(req.urlEncoded)) {
            urlEncodedState = req.urlEncoded;
            renderUrlEncodedTable();
        }
        if (req.body !== undefined) {
            const txt = document.getElementById('requestBodyTextarea');
            if (txt) {
                txt.value = typeof req.body === 'string' ? req.body : JSON.stringify(req.body, null, 2);
            }
        }

        // 5. Update Badges & Active Tab
        updateBadges();

        if (bType !== 'none' && ['POST', 'PUT', 'PATCH', 'DELETE'].includes((req.method || '').toUpperCase())) {
            const bodyTabBtn = document.getElementById('body-tab');
            if (bodyTabBtn && window.bootstrap) {
                const tab = new window.bootstrap.Tab(bodyTabBtn);
                tab.show();
            }
        }
    }

    function escapeAttr(str) { return String(str || '').replace(/"/g, '&quot;'); }

    return {
        init: init,
        addQueryParam: addQueryParam,
        updateQueryParam: updateQueryParam,
        toggleQueryParam: toggleQueryParam,
        deleteQueryParam: deleteQueryParam,
        clearQueryParams: clearQueryParams,
        addHeader: addHeader,
        updateHeader: updateHeader,
        toggleHeader: toggleHeader,
        deleteHeader: deleteHeader,
        addPresetHeader: addPresetHeader,
        formatJsonBody: formatJsonBody,
        minifyJsonBody: minifyJsonBody,
        addFormData: addFormData,
        updateFormData: updateFormData,
        deleteFormData: deleteFormData,
        addUrlEncoded: addUrlEncoded,
        updateUrlEncoded: updateUrlEncoded,
        deleteUrlEncoded: deleteUrlEncoded,
        getCompiledRequest: getCompiledRequest,
        loadRequestState: loadRequestState
    };
})();
