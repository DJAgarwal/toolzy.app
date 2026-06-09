@extends('layouts.app')

@section('content')
<x-ui-trust-indicator />

<!-- Input Section -->
<div class="mb-4">
    <label for="jwt-input" class="form-label fw-semibold">JWT Input:</label>
    <div class="position-relative">
        <textarea id="jwt-input" class="form-control font-monospace p-3" rows="6" placeholder="Paste your JWT token here (header.payload.signature)..."></textarea>
        <div class="position-absolute bottom-0 end-0 p-2 text-muted small" id="char-count">0 characters</div>
    </div>
</div>

<div class="row g-2 mb-4">
    <div class="col-12 col-md-3 d-grid">
        <button class="btn btn-primary" id="decode-btn">Decode JWT</button>
    </div>
    <div class="col-6 col-md-3 d-grid">
        <button class="btn btn-outline-secondary" id="paste-btn">Paste</button>
    </div>
    <div class="col-6 col-md-3 d-grid">
        <button class="btn btn-outline-danger" id="clear-btn">Clear</button>
    </div>
    <div class="col-12 col-md-3 d-grid">
        <button class="btn btn-outline-info" id="example-btn">Show Example</button>
    </div>
</div>

<!-- Validation Status -->
<div class="alert alert-danger d-none shadow-sm border-0 rounded-3 mb-4" id="validation-section" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <span id="validation-message">Invalid JWT format.</span>
</div>

<!-- Security Warnings Container -->
<div id="security-warnings" class="mb-4"></div>

<!-- Results Section (Hidden until decoded) -->
<div class="d-none" id="results-section">
    <div class="row g-4">
        <!-- Header & Payload JSON Views -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">Header (Decoded):</label>
            <div class="card shadow-sm border-0 rounded-3 mb-2">
                <div class="card-body bg-light p-0 border-top rounded-3">
                    <pre class="json-code p-3 mb-0" id="header-json"></pre>
                </div>
            </div>
            <div class="btn-group w-100">
                <button class="btn btn-sm btn-outline-secondary" onclick="copyText('header-json', 'Header')">Copy</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="downloadJSON('header-json', 'jwt-header.json')">Download</button>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Payload (Decoded):</label>
            <div class="card shadow-sm border-0 rounded-3 mb-2">
                <div class="card-body bg-light p-0 border-top rounded-3">
                    <pre class="json-code p-3 mb-0" id="payload-json"></pre>
                </div>
            </div>
            <div class="btn-group w-100">
                <button class="btn btn-sm btn-outline-secondary" onclick="copyText('payload-json', 'Payload')">Copy</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="downloadJSON('payload-json', 'jwt-payload.json')">Download</button>
            </div>
        </div>

        <!-- Expiration & Status Summary -->
        <div class="col-lg-12">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <div class="text-muted small mb-1 text-uppercase fw-bold tracking-wider">Expiration Time</div>
                        <div class="h6 mb-0 fw-bold text-dark" id="expires-at">-</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <div class="text-muted small mb-1 text-uppercase fw-bold tracking-wider">Token Status</div>
                        <div id="expiration-status">-</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Token Analysis Table -->
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold small text-uppercase tracking-wider">Claim Analysis</h5>
                </div>
                <div class="card-body border-top p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 small text-muted text-uppercase tracking-wider">Claim</th>
                                    <th class="small text-muted text-uppercase tracking-wider">Value</th>
                                    <th class="small text-muted text-uppercase tracking-wider">Description</th>
                                </tr>
                            </thead>
                            <tbody id="analysis-table-body">
                                <!-- Analysis rows will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signature Verification -->
        <div class="col-lg-12">
            <hr class="my-4">
            <h5 class="fw-bold mb-3">Signature Verification</h5>
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Algorithm</label>
                    <select class="form-select" id="verify-alg" disabled>
                        <option value="HS256">HS256 (HMAC)</option>
                        <option value="RS256">RS256 (RSASSA)</option>
                        <option value="none">None</option>
                    </select>
                </div>
                <div class="col-md-6" id="hmac-input-group">
                    <label class="form-label small fw-bold">Secret Key</label>
                    <div class="input-group">
                        <input type="text" id="verify-secret" class="form-control font-monospace" placeholder="Enter secret key...">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" id="secret-base64">
                            <label class="form-check-label ms-2 small" for="secret-base64">Base64</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-none" id="rsa-input-group">
                    <label class="form-label small fw-bold">Public Key (PEM)</label>
                    <textarea id="verify-public-key" class="form-control font-monospace" rows="2" placeholder="-----BEGIN PUBLIC KEY-----"></textarea>
                </div>
                <div class="col-md-3 d-grid">
                    <button class="btn btn-warning fw-bold" id="verify-btn">Verify Signature</button>
                </div>
            </div>
            
            <div id="verify-result" class="mt-3 d-none">
                <div class="alert mb-0 border-0 rounded-3">
                    <span id="verify-result-icon"></span>
                    <strong id="verify-result-text"></strong>
                </div>
            </div>
        </div>
        
        <!-- Batch Download -->
        <div class="col-12 d-grid mt-4">
            <button class="btn btn-outline-dark" onclick="downloadFullAnalysis()">
                <i class="bi bi-file-earmark-arrow-down me-1"></i> Download Full Analysis JSON
            </button>
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
    #char-count {
        margin-bottom: 5px;
        margin-right: 5px;
    }
    .json-code {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-size: 0.85rem;
        color: #212529;
        overflow-x: auto;
        max-height: 400px;
        line-height: 1.5;
    }
    .json-key { color: #8250df; }
    .json-string { color: #0a3069; }
    .json-number { color: #cf222e; }
    .json-boolean { color: #cf222e; }
    .json-null { color: #cf222e; }
    .font-monospace {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace !important;
    }
    .tracking-wider {
        letter-spacing: 0.05em;
    }
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', () => {
    // Standard claims dictionary
    const CLAIMS_MAP = {
        'iss': 'Issuer - The entity that issued the JWT.',
        'sub': 'Subject - The entity to which the JWT refers.',
        'aud': 'Audience - The intended recipients of the JWT.',
        'exp': 'Expiration Time - When the token becomes invalid.',
        'nbf': 'Not Before - When the token starts being valid.',
        'iat': 'Issued At - When the token was generated.',
        'jti': 'JWT ID - Unique identifier for the token.',
        'typ': 'Type - The media type of the token.',
        'alg': 'Algorithm - The cryptographic algorithm used.',
        'kid': 'Key ID - Hint indicating which key was used.',
        'cty': 'Content Type - Used if the JWT carries a nested payload.'
    };

    const input = document.getElementById('jwt-input');
    const charCount = document.getElementById('char-count');
    const decodeBtn = document.getElementById('decode-btn');
    const pasteBtn = document.getElementById('paste-btn');
    const clearBtn = document.getElementById('clear-btn');
    const exampleBtn = document.getElementById('example-btn');
    
    const resultsSection = document.getElementById('results-section');
    const validationSection = document.getElementById('validation-section');
    const validationMessage = document.getElementById('validation-message');
    const headerJson = document.getElementById('header-json');
    const payloadJson = document.getElementById('payload-json');
    const analysisBody = document.getElementById('analysis-table-body');
    const expiresAtEl = document.getElementById('expires-at');
    const expirationStatusEl = document.getElementById('expiration-status');
    const verifyAlg = document.getElementById('verify-alg');
    const hmacGroup = document.getElementById('hmac-input-group');
    const rsaGroup = document.getElementById('rsa-input-group');
    const verifyBtn = document.getElementById('verify-btn');
    const verifyResult = document.getElementById('verify-result');
    const warningsEl = document.getElementById('security-warnings');

    let currentToken = null, decodedHeader = null, decodedPayload = null;
    let rawSignature = null, rawHeader = null, rawPayload = null;

    function base64UrlDecode(str) {
        str = str.replace(/-/g, '+').replace(/_/g, '/');
        while (str.length % 4) str += '=';
        try {
            return decodeURIComponent(atob(str).split('').map(c => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)).join(''));
        } catch (e) { return atob(str); }
    }

    function syntaxHighlight(json) {
        if (typeof json != 'string') json = JSON.stringify(json, undefined, 4);
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+-]?\d+)?)/g, match => {
            let cls = 'json-number';
            if (/^"/.test(match)) cls = /:$/.test(match) ? 'json-key' : 'json-string';
            else if (/true|false/.test(match)) cls = 'json-boolean';
            else if (/null/.test(match)) cls = 'json-null';
            return `<span class="${cls}">${match}</span>`;
        });
    }

    function decodeJWT() {
        const token = input.value.trim();
        if (!token) return;

        validationSection.classList.add('d-none');
        resultsSection.classList.add('d-none');
        verifyResult.classList.add('d-none');
        warningsEl.innerHTML = '';

        const parts = token.split('.');
        if (parts.length !== 3) {
            validationMessage.innerText = "Invalid JWT format. Expected: header.payload.signature";
            validationSection.classList.remove('d-none');
            showToast('Invalid JWT format', 'danger');
            return;
        }

        try {
            rawHeader = parts[0]; rawPayload = parts[1]; rawSignature = parts[2];
            decodedHeader = JSON.parse(base64UrlDecode(rawHeader));
            decodedPayload = JSON.parse(base64UrlDecode(rawPayload));
            currentToken = token;

            displayResults();
            analyzeToken();
            dispatchAnalyticsEvent('tool_used', { tool: 'jwt-decoder' });
        } catch (e) {
            showToast('Failed to decode JWT contents', 'danger');
        }
    }

    function displayResults() {
        headerJson.innerHTML = syntaxHighlight(decodedHeader);
        payloadJson.innerHTML = syntaxHighlight(decodedPayload);
        resultsSection.classList.remove('d-none');

        const alg = decodedHeader.alg || 'none';
        verifyAlg.value = alg;
        hmacGroup.classList.toggle('d-none', !alg.startsWith('HS'));
        rsaGroup.classList.toggle('d-none', !alg.startsWith('RS'));
    }

    function analyzeToken() {
        analysisBody.innerHTML = '';
        let warnings = [];

        // Combine all claims for analysis
        const allClaims = { ...decodedHeader, ...decodedPayload };
        for (const [key, value] of Object.entries(allClaims)) {
            const row = document.createElement('tr');
            let displayValue = typeof value === 'object' ? JSON.stringify(value) : value;
            
            if (['exp', 'iat', 'nbf'].includes(key) && typeof value === 'number') {
                const date = new Date(value * 1000);
                displayValue = `<div class="fw-bold">${value}</div><div class="small text-muted">${date.toUTCString()}</div>`;
            }

            row.innerHTML = `
                <td class="ps-3"><code class="text-primary fw-bold">${key}</code></td>
                <td class="text-break">${displayValue}</td>
                <td class="small text-muted">${CLAIMS_MAP[key] || 'Custom Claim'}</td>
            `;
            analysisBody.appendChild(row);
        }

        // Time Analysis Logic
        if (decodedPayload.exp) {
            const now = Math.floor(Date.now() / 1000);
            const exp = decodedPayload.exp;
            const date = new Date(exp * 1000);
            expiresAtEl.innerText = date.toUTCString();

            if (exp < now) {
                expirationStatusEl.innerHTML = '<span class="badge bg-danger rounded-pill px-3">Expired</span>';
                warnings.push({ type: 'danger', msg: 'This token has expired and should not be used.' });
            } else {
                const diff = exp - now;
                const days = Math.floor(diff / 86400);
                const hours = Math.floor((diff % 86400) / 3600);
                expirationStatusEl.innerHTML = `<span class="badge bg-success rounded-pill px-3">Valid</span> <small class="text-muted ms-1">Remaining: ${days}d ${hours}h</small>`;
            }
        } else {
            expiresAtEl.innerText = 'No expiration claim';
            expirationStatusEl.innerHTML = '<span class="badge bg-warning text-dark rounded-pill px-3">Indefinite</span>';
            warnings.push({ type: 'warning', msg: 'Missing "exp" claim. This token might never expire.' });
        }

        // Additional Insights
        if (decodedHeader.alg === 'none') {
            warnings.push({ type: 'danger', msg: 'Algorithm set to "none". This token is completely unsigned and insecure.' });
        }
        if (currentToken.length > 8000) {
            warnings.push({ type: 'warning', msg: 'Token size is very large (> 8KB). It may be rejected by some web servers.' });
        }

        // Render warnings
        warnings.forEach(w => {
            const div = document.createElement('div');
            div.className = `alert alert-${w.type} border-0 shadow-sm rounded-3 mb-2`;
            div.innerHTML = `<i class="bi bi-exclamation-octagon-fill me-2"></i> ${w.msg}`;
            warningsEl.appendChild(div);
        });
    }

    async function verifySignature() {
        const alg = decodedHeader.alg;
        const msg = `${rawHeader}.${rawPayload}`;
        verifyResult.classList.remove('d-none');
        const alert = verifyResult.querySelector('.alert'), icon = document.getElementById('verify-result-icon'), text = document.getElementById('verify-result-text');

        try {
            let isValid = false;
            if (alg === 'HS256') {
                const secret = document.getElementById('verify-secret').value;
                if (!secret) throw new Error("Secret key required");
                const isBase64 = document.getElementById('secret-base64').checked;
                const keyData = isBase64 ? Uint8Array.from(atob(secret), c => c.charCodeAt(0)) : new TextEncoder().encode(secret);
                const key = await crypto.subtle.importKey('raw', keyData, { name: 'HMAC', hash: 'SHA-256' }, false, ['verify']);
                const sigData = Uint8Array.from(atob(rawSignature.replace(/-/g, '+').replace(/_/g, '/')), c => c.charCodeAt(0));
                isValid = await crypto.subtle.verify('HMAC', key, sigData, new TextEncoder().encode(msg));
            } else if (alg === 'RS256') {
                const pem = document.getElementById('verify-public-key').value;
                if (!pem) throw new Error("Public key required");
                const binaryDer = Uint8Array.from(atob(pem.replace(/-----BEGIN PUBLIC KEY-----|-----END PUBLIC KEY-----|\s/g, '')), c => c.charCodeAt(0));
                const key = await crypto.subtle.importKey('spki', binaryDer, { name: 'RSASSA-PKCS1-v1_5', hash: 'SHA-256' }, false, ['verify']);
                const sigData = Uint8Array.from(atob(rawSignature.replace(/-/g, '+').replace(/_/g, '/')), c => c.charCodeAt(0));
                isValid = await crypto.subtle.verify('RSASSA-PKCS1-v1_5', key, sigData, new TextEncoder().encode(msg));
            } else throw new Error("Verification for " + alg + " is not yet supported in this browser.");

            alert.className = `alert alert-${isValid ? 'success' : 'danger'} border-0 rounded-3 mb-0`;
            icon.innerHTML = `<i class="bi bi-${isValid ? 'check' : 'x'}-circle-fill me-2"></i>`;
            text.innerText = isValid ? 'Signature Verified!' : 'Invalid Signature!';
            showToast(isValid ? 'Signature Verified!' : 'Invalid Signature!', isValid ? 'success' : 'danger');
        } catch (e) {
            alert.className = 'alert alert-secondary border-0 rounded-3 mb-0';
            text.innerText = 'Error: ' + e.message;
            showToast(e.message, 'warning');
        }
    }

    // Event Listeners
    input.addEventListener('input', () => {
        charCount.innerText = `${input.value.length} characters`;
        if (input.value.trim().split('.').length === 3) decodeJWT();
    });

    decodeBtn.addEventListener('click', decodeJWT);
    clearBtn.addEventListener('click', () => {
        input.value = ''; charCount.innerText = '0 characters';
        resultsSection.classList.add('d-none'); validationSection.classList.add('d-none');
        warningsEl.innerHTML = '';
        showToast('Cleared!', 'info');
    });
    pasteBtn.addEventListener('click', async () => {
        try { input.value = await navigator.clipboard.readText(); input.dispatchEvent(new Event('input')); } catch (err) {}
    });
    exampleBtn.addEventListener('click', () => {
        input.value = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyLCJleHAiOjE5MTYyMzkwMjIsImFkbWluIjp0cnVlfQ.6D7_XwXWjXF7V_YxP9G8_O8X9XWjXF7V_YxP9G8_O8I";
        input.dispatchEvent(new Event('input'));
    });
    verifyBtn.addEventListener('click', verifySignature);

    window.copyText = function(id, label) {
        navigator.clipboard.writeText(document.getElementById(id).innerText).then(() => showToast(`${label} copied!`, 'success'));
    };
    window.downloadJSON = function(id, filename) {
        const a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([document.getElementById(id).innerText], { type: 'application/json' }));
        a.download = filename; a.click();
    };
    window.downloadFullAnalysis = function() {
        const analysis = { 
            header: decodedHeader, 
            payload: decodedPayload,
            status: {
                algorithm: decodedHeader.alg,
                expires: expiresAtEl.innerText,
                size: currentToken.length
            }
        };
        const a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([JSON.stringify(analysis, null, 4)], { type: 'application/json' }));
        a.download = 'jwt-analysis.json'; a.click();
    };

    function dispatchAnalyticsEvent(name, data) {
        document.dispatchEvent(new CustomEvent('toolzy-event', { detail: { name, data } }));
    }
});
</script>
@endpush
