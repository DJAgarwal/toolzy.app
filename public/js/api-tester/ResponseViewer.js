/**
 * Response Viewer Module - Toolzy
 * Interactive JSON tree, XML renderer, sandboxed HTML preview, Header security analyzer, and Cookie viewer.
 */

window.ResponseViewer = (function () {

    /**
     * Main display handler for API responses
     */
    function renderResponse(res) {
        renderStatusHeader(res);
        renderResponseBody(res);
        renderResponseHeaders(res);
        renderResponseCookies(res);
        renderHeaderSecurityAnalysis(res);
    }

    function renderStatusHeader(res) {
        const container = document.getElementById('responseStatusContainer');
        if (!container) return;

        if (res.status === 0) {
            container.innerHTML = `
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-3 bg-danger bg-opacity-10 border border-danger rounded mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="status-pill status-error"><i class="bi bi-x-circle-fill"></i> ${res.statusText}</span>
                        <span class="text-danger small fw-semibold">${res.errorMessage || 'Browser Security / CORS Block'}</span>
                    </div>
                    <span class="badge bg-secondary">${res.responseTimeMs} ms</span>
                </div>
            `;
            return;
        }

        let statusClass = 'status-2xx';
        if (res.status >= 300 && res.status < 400) statusClass = 'status-3xx';
        if (res.status >= 400 && res.status < 500) statusClass = 'status-4xx';
        if (res.status >= 500) statusClass = 'status-5xx';

        const sizeKb = (res.sizeBytes / 1024).toFixed(2);

        container.innerHTML = `
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-2 bg-light border rounded mb-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="status-pill ${statusClass}">
                        <i class="bi bi-check-circle-fill"></i> ${res.status} ${res.statusText}
                    </span>
                    <span class="text-muted small"><strong>Time:</strong> <span class="text-dark fw-bold">${res.responseTimeMs} ms</span></span>
                    <span class="text-muted small"><strong>Size:</strong> <span class="text-dark fw-bold">${sizeKb} KB</span></span>
                </div>
                <div>
                    <span class="badge bg-outline-secondary text-secondary border">${res.contentType.split(';')[0]}</span>
                </div>
            </div>
        `;
    }

    function renderResponseBody(res) {
        const rawBodyText = res.body || '';
        const contentType = (res.contentType || '').toLowerCase();

        // 1. Raw Text View
        const rawEl = document.getElementById('responseRawBody');
        if (rawEl) rawEl.textContent = rawBodyText || '(Empty Response)';

        // 2. Try JSON Parse
        let jsonParsed = null;
        try {
            if (rawBodyText.trim().startsWith('{') || rawBodyText.trim().startsWith('[')) {
                jsonParsed = JSON.parse(rawBodyText);
            }
        } catch (e) {}

        const jsonTreeEl = document.getElementById('responseJsonTree');
        const jsonStatsEl = document.getElementById('jsonStatsBar');
        
        if (jsonTreeEl && !jsonTreeEl.dataset.listenerAttached) {
            jsonTreeEl.dataset.listenerAttached = 'true';
            jsonTreeEl.addEventListener('click', function (e) {
                const toggle = e.target.closest('.json-tree-toggle');
                if (toggle && toggle.nextElementSibling) {
                    toggle.nextElementSibling.classList.toggle('d-none');
                }
            });
        }

        if (jsonParsed !== null && jsonTreeEl) {
            jsonTreeEl.innerHTML = buildJsonTreeHTML(jsonParsed, '');
            if (jsonStatsEl) {
                const stats = calcJsonStats(jsonParsed, rawBodyText.length);
                jsonStatsEl.innerHTML = `
                    <span class="badge bg-secondary me-2">Size: ${stats.sizeKb} KB</span>
                    <span class="badge bg-primary me-2">Objects: ${stats.objects}</span>
                    <span class="badge bg-info">Arrays: ${stats.arrays}</span>
                `;
            }
        } else if (jsonTreeEl) {
            jsonTreeEl.innerHTML = `<div class="text-muted italic p-3">Response is not valid JSON. View Raw or HTML tab.</div>`;
            if (jsonStatsEl) jsonStatsEl.innerHTML = '';
        }

        // 3. HTML Sandboxed Preview
        const iframe = document.getElementById('responseHtmlIframe');
        if (iframe) {
            if (contentType.includes('html') || rawBodyText.trim().startsWith('<html') || rawBodyText.trim().startsWith('<!DOCTYPE')) {
                iframe.srcdoc = rawBodyText;
            } else {
                iframe.srcdoc = `<html><body style="font-family:sans-serif;padding:20px;color:#666;">Not an HTML response. View Raw or JSON tab.</body></html>`;
            }
        }

        // 4. Image / Media Preview
        const imgPreview = document.getElementById('responseImagePreview');
        if (imgPreview) {
            if (contentType.includes('image')) {
                imgPreview.src = 'data:' + contentType + ';base64,' + btoa(rawBodyText);
                imgPreview.classList.remove('d-none');
            } else {
                imgPreview.classList.add('d-none');
            }
        }
    }

    function buildJsonTreeHTML(obj, path) {
        if (obj === null) return `<span class="json-tree-null">null</span>`;
        if (typeof obj === 'boolean') return `<span class="json-tree-boolean">${obj}</span>`;
        if (typeof obj === 'number') return `<span class="json-tree-number">${obj}</span>`;
        if (typeof obj === 'string') return `<span class="json-tree-string">"${escapeHtml(obj)}"</span>`;

        if (Array.isArray(obj)) {
            if (obj.length === 0) return `[]`;
            let items = obj.map((item, idx) => {
                let currentPath = path ? `${path}[${idx}]` : `[${idx}]`;
                return `<div class="json-tree-node"><span class="json-tree-key">${idx}:</span> ${buildJsonTreeHTML(item, currentPath)}</div>`;
            }).join('');
            return `<span class="json-tree-toggle" style="cursor:pointer;">[▾]</span><div>[${items}]</div>`;
        }

        if (typeof obj === 'object') {
            let keys = Object.keys(obj);
            if (keys.length === 0) return `{}`;
            let props = keys.map(k => {
                let currentPath = path ? `${path}.${k}` : k;
                return `<div class="json-tree-node"><span class="json-tree-key">"${escapeHtml(k)}":</span> ${buildJsonTreeHTML(obj[k], currentPath)}</div>`;
            }).join('');
            return `<span class="json-tree-toggle" style="cursor:pointer;">{▾}</span><div>{${props}}</div>`;
        }

        return escapeHtml(String(obj));
    }

    function calcJsonStats(obj, byteLen) {
        let objects = 0;
        let arrays = 0;

        function walk(o) {
            if (o && typeof o === 'object') {
                if (Array.isArray(o)) {
                    arrays++;
                    o.forEach(walk);
                } else {
                    objects++;
                    Object.values(o).forEach(walk);
                }
            }
        }
        walk(obj);

        return {
            sizeKb: (byteLen / 1024).toFixed(2),
            objects: objects,
            arrays: arrays
        };
    }

    function renderResponseHeaders(res) {
        const tableBody = document.getElementById('responseHeadersTableBody');
        if (!tableBody) return;

        const headers = res.headers || {};
        const keys = Object.keys(headers);

        if (keys.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="2" class="text-muted text-center py-3">No headers returned or restricted by CORS policy.</td></tr>`;
            return;
        }

        tableBody.innerHTML = keys.map(k => `
            <tr>
                <td class="fw-semibold text-primary font-monospace" style="width: 35%;">${escapeHtml(k)}</td>
                <td class="font-monospace text-break">${escapeHtml(headers[k])}</td>
            </tr>
        `).join('');
    }

    function renderResponseCookies(res) {
        const container = document.getElementById('responseCookiesContainer');
        if (!container) return;

        container.innerHTML = `
            <div class="alert alert-info small mb-0">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Security Note:</strong> Browsers restrict direct JavaScript access to <code>Set-Cookie</code> response headers and <code>HttpOnly</code> cookies for cross-origin fetch requests to prevent session hijacking.
            </div>
        `;
    }

    function renderHeaderSecurityAnalysis(res) {
        const container = document.getElementById('securityAnalysisContainer');
        if (!container) return;

        const headers = res.headers || {};
        const securityChecks = [
            { key: 'strict-transport-security', label: 'Strict-Transport-Security (HSTS)', desc: 'Enforces HTTPS connection security.' },
            { key: 'content-security-policy', label: 'Content-Security-Policy (CSP)', desc: 'Prevents XSS and unauthorized script execution.' },
            { key: 'x-content-type-options', label: 'X-Content-Type-Options', desc: 'Prevents MIME-sniffing vulnerabilities.' },
            { key: 'x-frame-options', label: 'X-Frame-Options', desc: 'Protects against clickjacking attacks.' },
            { key: 'cache-control', label: 'Cache-Control', desc: 'Specifies caching policy for sensitive API data.' }
        ];

        let html = `<div class="card p-3 shadow-sm border-0"><h6 class="fw-bold mb-3"><i class="bi bi-shield-check text-success me-2"></i> API Security & Cache Headers Audit</h6><div class="list-group list-group-flush">`;

        securityChecks.forEach(check => {
            const present = headers[check.key] !== undefined;
            if (present) {
                html += `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${check.label}</strong>
                            <div class="small text-muted font-monospace">${escapeHtml(headers[check.key])}</div>
                        </div>
                        <span class="badge bg-success"><i class="bi bi-check-lg"></i> Present</span>
                    </div>
                `;
            } else {
                html += `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${check.label}</strong>
                            <div class="small text-muted">${check.desc}</div>
                        </div>
                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Missing</span>
                    </div>
                `;
            }
        });

        html += `</div></div>`;
        container.innerHTML = html;
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    return {
        renderResponse: renderResponse
    };
})();
