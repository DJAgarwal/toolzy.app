@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />

    <div class="row g-4">
        <!-- Main Tool Area -->
        <div class="col-lg-8">
            <!-- Generator Controls -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-magic me-2"></i>Generate UUIDs</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="uuidVersion" class="form-label small fw-bold text-uppercase text-muted">Version</label>
                            <select id="uuidVersion" class="form-select">
                                <option value="4" selected>Version 4 (Random)</option>
                                <option value="1">Version 1 (Timestamp)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="uuidQuantity" class="form-label small fw-bold text-uppercase text-muted">Quantity</label>
                            <input type="number" id="uuidQuantity" class="form-control" value="10" min="1" max="10000">
                        </div>
                        <div class="col-md-4">
                            <label for="uuidFormat" class="form-label small fw-bold text-uppercase text-muted">Case</label>
                            <select id="uuidFormat" class="form-select">
                                <option value="lowercase" selected>lowercase</option>
                                <option value="uppercase">UPPERCASE</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Hyphens</label>
                            <div class="d-flex gap-3 pt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hyphenFormat" id="hyphenStandard" value="standard" checked>
                                    <label class="form-check-label small" for="hyphenStandard">Standard</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hyphenFormat" id="hyphenNone" value="none">
                                    <label class="form-check-label small" for="hyphenNone">None</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="uuidStyle" class="form-label small fw-bold text-uppercase text-muted">Output Style(On Copy All)</label>
                            <select id="uuidStyle" class="form-select">
                                <option value="plain" selected>Plain List (New Line)</option>
                                <option value="csv">Comma Separated</option>
                                <option value="json">JSON Array</option>
                                <option value="sql">SQL Insert Values</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 d-flex flex-wrap gap-2">
                        <button id="generateBtn" class="btn btn-primary px-4"><i class="bi bi-play-fill me-1"></i>Generate UUIDs</button>
                        <button id="clearBtn" class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i>Clear</button>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="resultsCard" class="card mb-4 shadow-sm d-none">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Generated UUIDs</h5>
                    <div class="btn-group btn-group-sm">
                        <button id="copyAllBtn" class="btn btn-outline-primary"><i class="bi bi-clipboard me-1"></i>Copy All</button>
                        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-download me-1"></i>Download</button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item small" id="download-txt">TXT File</button></li>
                            <li><button class="dropdown-item small" id="download-csv">CSV File</button></li>
                            <li><button class="dropdown-item small" id="download-json">JSON File</button></li>
                            <li><button class="dropdown-item small" id="download-sql">SQL File</button></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive uuid-table-container">
                        <table class="table table-hover mb-0 small">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="60">#</th>
                                    <th>UUID</th>
                                    <th width="80" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="uuidTableBody">
                                <!-- Results will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light py-2 small d-flex justify-content-between text-muted">
                    <span id="duplicateCheck">Duplicate Check: <span class="text-success">No duplicates found</span></span>
                    <span id="genTime">Generation Time: 0ms</span>
                </div>
            </div>

            <!-- Inspector Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-search me-2"></i>UUID Inspector & Validator</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">Paste a UUID below to validate and analyze its structure.</p>
                    <div class="input-group mb-3">
                        <input type="text" id="inspectInput" class="form-control font-monospace" placeholder="e.g. 550e8400-e29b-41d4-a716-446655440000">
                        <button id="inspectBtn" class="btn btn-primary" type="button">Inspect</button>
                    </div>
                    
                    <div id="inspectResult" class="d-none mt-3">
                        <div id="validBadge" class="alert py-2 mb-3 small d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <span id="validText" class="fw-bold">Valid UUID Detected</span>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="p-2 border rounded bg-light">
                                    <div class="small text-muted text-uppercase fw-bold x-small">Version</div>
                                    <div id="inspectVersion" class="fw-bold">-</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-2 border rounded bg-light">
                                    <div class="small text-muted text-uppercase fw-bold x-small">Variant</div>
                                    <div id="inspectVariant" class="fw-bold">-</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-2 border rounded bg-light">
                                    <div class="small text-muted text-uppercase fw-bold x-small">Structure Details</div>
                                    <div id="inspectStructure" class="font-monospace small mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Area -->
        <div class="col-lg-4">
            <!-- Stats Panel -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2"></i>Generation Stats</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Entropy Source</span>
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 fw-normal">Web Crypto API</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Bits per UUID</span>
                            <span class="fw-bold">128 bits</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Unique Count</span>
                            <span id="uniqueCountBadge" class="fw-bold">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Collision Probability</span>
                            <span id="collisionProb" class="text-success fw-bold">Negligible</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Educational Quick Links -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>UUID Reference</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item bg-light fw-bold py-2 px-3 text-uppercase x-small">Common Versions</div>
                        <div class="list-group-item py-2 px-3">
                            <strong>v1:</strong> Timestamp + MAC/Random node ID. Good for sorting.
                        </div>
                        <div class="list-group-item py-2 px-3 border-bottom">
                            <strong>v4:</strong> Fully random bits. Best for general purpose use.
                        </div>
                        <div class="list-group-item bg-light fw-bold py-2 px-3 text-uppercase x-small">Standards</div>
                        <div class="list-group-item py-2 px-3">
                            <strong>RFC 4122:</strong> The standard defining UUID structure.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational & SEO Content -->
    <div class="mt-5 border-top pt-5">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="h4 fw-bold mb-4">In-depth Guide to UUIDs</h2>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <h5 class="fw-bold">What is a UUID?</h5>
                        <p class="text-muted small">A Universally Unique Identifier (UUID) is a 128-bit number used to uniquely identify information in computer systems without significant central coordination. While the probability of two UUIDs being the same is not zero, it is so small that it is generally considered negligible for practical purposes.</p>
                        
                        <h5 class="fw-bold">UUID v1 vs. UUID v4</h5>
                        <p class="text-muted small"><strong>Version 1</strong> is generated from a time-based value and a node ID (MAC address). This makes them sortable by time but can expose information about the machine that generated them. <strong>Version 4</strong> is generated entirely from random bits, making them truly anonymous and perfect for most modern applications.</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold">Database Performance Considerations</h5>
                        <p class="text-muted small">Using UUIDs as primary keys can have performance implications. Because standard v4 UUIDs are random, they can cause index fragmentation in B-tree based databases like MySQL. For better performance, consider using sortable UUIDs (like v1 or ULID) or storing them in binary format (16 bytes) rather than strings.</p>
                        
                        <h5 class="fw-bold">Security & Entropy</h5>
                        <p class="text-muted small">Toolzy uses the <code>crypto.getRandomValues()</code> API, which is the gold standard for browser-based randomness. This ensures that every UUID generated is unpredictable and secure for use in session identifiers, API keys, and secure record tracking.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
.uuid-table-container {
    max-height: 500px;
}

.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

.x-small {
    font-size: 0.7rem;
}

#uuidTableBody .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.sticky-top {
    z-index: 1020;
}

.inspect-segment {
    display: inline-block;
    padding: 2px 4px;
    margin: 0 1px;
    border-radius: 3px;
}
.seg-time { background-color: rgba(13, 110, 253, 0.15); color: #0d6efd; }
.seg-version { background-color: rgba(25, 135, 84, 0.15); color: #198754; font-weight: bold; }
.seg-variant { background-color: rgba(255, 193, 7, 0.15); color: #997404; font-weight: bold; }
.seg-node { background-color: rgba(108, 117, 125, 0.15); color: #6c757d; }
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const UUIDTool = (function() {
    let generatedUUIDs = [];
    
    const elements = {
        version: document.getElementById('uuidVersion'),
        quantity: document.getElementById('uuidQuantity'),
        format: document.getElementById('uuidFormat'),
        style: document.getElementById('uuidStyle'),
        generateBtn: document.getElementById('generateBtn'),
        clearBtn: document.getElementById('clearBtn'),
        copyAllBtn: document.getElementById('copyAllBtn'),
        resultsCard: document.getElementById('resultsCard'),
        tableBody: document.getElementById('uuidTableBody'),
        duplicateCheck: document.getElementById('duplicateCheck'),
        genTime: document.getElementById('genTime'),
        uniqueCountBadge: document.getElementById('uniqueCountBadge'),
        collisionProb: document.getElementById('collisionProb'),
        inspectInput: document.getElementById('inspectInput'),
        inspectBtn: document.getElementById('inspectBtn'),
        inspectResult: document.getElementById('inspectResult'),
        inspectVersion: document.getElementById('inspectVersion'),
        inspectVariant: document.getElementById('inspectVariant'),
        inspectStructure: document.getElementById('inspectStructure'),
        validBadge: document.getElementById('validBadge'),
        validText: document.getElementById('validText')
    };

    function init() {
        bindEvents();
    }

    function bindEvents() {
        elements.generateBtn.addEventListener('click', generate);
        elements.clearBtn.addEventListener('click', clear);
        elements.copyAllBtn.addEventListener('click', copyAll);
        elements.inspectBtn.addEventListener('click', inspect);
        
        // Download handlers
        document.getElementById('download-txt').addEventListener('click', () => download('txt'));
        document.getElementById('download-csv').addEventListener('click', () => download('csv'));
        document.getElementById('download-json').addEventListener('click', () => download('json'));
        document.getElementById('download-sql').addEventListener('click', () => download('sql'));

        // Event delegation for "Copy One" buttons in the table
        elements.tableBody.addEventListener('click', (e) => {
            const btn = e.target.closest('.copy-one-btn');
            if (btn) {
                const uuid = btn.dataset.uuid;
                copyOne(uuid, btn);
            }
        });
        
        // Auto-inspect on paste
        elements.inspectInput.addEventListener('input', () => {
            if (elements.inspectInput.value.trim().length >= 32) {
                inspect();
            }
        });
    }

    // RFC 4122 v4 Generator (Random)
    function generateV4() {
        const rnd = window.crypto.getRandomValues(new Uint8Array(16));
        rnd[6] = (rnd[6] & 0x0f) | 0x40; // Version 4
        rnd[8] = (rnd[8] & 0x3f) | 0x80; // Variant 10 (RFC 4122)
        
        return formatBytes(rnd);
    }

    // RFC 4122 v1 Generator (Time-based)
    let lastV1Time = 0;
    let v1Sequence = window.crypto.getRandomValues(new Uint16Array(1))[0] & 0x3fff;
    let v1Node = window.crypto.getRandomValues(new Uint8Array(6));
    v1Node[0] |= 0x01; // Set multicast bit for random node ID

    function generateV1() {
        let now = Date.now();
        // Adjust for 100ns intervals since Oct 15, 1582
        let msecs = now + 12219292800000; 
        
        if (msecs <= lastV1Time) {
            v1Sequence = (v1Sequence + 1) & 0x3fff;
        }
        lastV1Time = msecs;

        const timeLow = ((msecs & 0xfffffff) * 10000) & 0xffffffff;
        const timeMid = ((msecs / 0x100000000) * 10000) & 0xffff;
        const timeHi = (((msecs / 0x1000000000000) * 10000) & 0x0fff) | 0x1000; // v1

        const bytes = new Uint8Array(16);
        bytes[0] = timeLow >>> 24; bytes[1] = timeLow >>> 16; bytes[2] = timeLow >>> 8; bytes[3] = timeLow;
        bytes[4] = timeMid >>> 8; bytes[5] = timeMid;
        bytes[6] = timeHi >>> 8; bytes[7] = timeHi;
        bytes[8] = (v1Sequence >>> 8) | 0x80; bytes[9] = v1Sequence;
        bytes.set(v1Node, 10);

        return formatBytes(bytes);
    }

    function formatBytes(bytes) {
        const hex = Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
        return `${hex.slice(0, 8)}-${hex.slice(8, 12)}-${hex.slice(12, 16)}-${hex.slice(16, 20)}-${hex.slice(20)}`;
    }

    function generate() {
        const version = parseInt(elements.version.value);
        const qty = Math.min(Math.max(parseInt(elements.quantity.value) || 1, 1), 10000);
        const isUppercase = elements.format.value === 'uppercase';
        const noHyphens = document.querySelector('input[name="hyphenFormat"]:checked').value === 'none';

        const startTime = performance.now();
        const results = [];
        
        for (let i = 0; i < qty; i++) {
            let uuid = (version === 4) ? generateV4() : generateV1();
            if (noHyphens) uuid = uuid.replace(/-/g, '');
            if (isUppercase) uuid = uuid.toUpperCase();
            results.push(uuid);
        }

        const endTime = performance.now();
        generatedUUIDs = results;
        
        renderResults(results, endTime - startTime);
    }

    function renderResults(uuids, time) {
        elements.resultsCard.classList.remove('d-none');
        elements.genTime.textContent = `Generation Time: ${time.toFixed(2)}ms`;
        
        // Stats
        const unique = new Set(uuids);
        const duplicateCount = uuids.length - unique.size;
        elements.uniqueCountBadge.textContent = unique.size;
        
        if (duplicateCount > 0) {
            elements.duplicateCheck.innerHTML = `Duplicate Check: <span class="text-danger">${duplicateCount} duplicates detected!</span>`;
            elements.collisionProb.textContent = 'Possible';
            elements.collisionProb.className = 'text-warning fw-bold';
        } else {
            elements.duplicateCheck.innerHTML = `Duplicate Check: <span class="text-success">No duplicates found</span>`;
            elements.collisionProb.textContent = uuids.length > 1000 ? 'Extremely Low' : 'Negligible';
            elements.collisionProb.className = 'text-success fw-bold';
        }

        // Render Table (limited to first 1000 for DOM performance)
        const displayLimit = 1000;
        let html = '';
        for (let i = 0; i < Math.min(uuids.length, displayLimit); i++) {
            html += `<tr>
                <td class="text-muted">${i + 1}</td>
                <td class="font-monospace">${uuids[i]}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-secondary copy-one-btn" data-uuid="${uuids[i]}">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </td>
            </tr>`;
        }

        if (uuids.length > displayLimit) {
            html += `<tr><td colspan="3" class="text-center text-muted py-3">... and ${uuids.length - displayLimit} more (exported in full)</td></tr>`;
        }

        elements.tableBody.innerHTML = html;
        showToast(`Generated ${uuids.length} UUIDs`, 'success');
        
        // Dispatch event for analytics
        window.dispatchEvent(new CustomEvent('uuid_generated', { detail: { count: uuids.length, version: elements.version.value } }));
    }

    function clear() {
        generatedUUIDs = [];
        elements.tableBody.innerHTML = '';
        elements.resultsCard.classList.add('d-none');
        elements.uniqueCountBadge.textContent = '0';
        showToast('Results cleared', 'info');
    }

    function copyOne(uuid, btn) {
        navigator.clipboard.writeText(uuid).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
            btn.classList.add('border-success');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('border-success');
            }, 1500);
            showToast('UUID copied to clipboard', 'success');
        });
    }

    function getFormattedOutput() {
        const style = elements.style.value;
        if (style === 'csv') return generatedUUIDs.join(',');
        if (style === 'json') return JSON.stringify(generatedUUIDs, null, 2);
        if (style === 'sql') {
            return `INSERT INTO table_name (uuid) VALUES\n` + 
                   generatedUUIDs.map(u => `('${u}')`).join(',\n') + ';';
        }
        return generatedUUIDs.join('\n');
    }

    function copyAll() {
        const text = getFormattedOutput();
        navigator.clipboard.writeText(text).then(() => {
            showToast('All UUIDs copied to clipboard', 'success');
            window.dispatchEvent(new CustomEvent('uuid_copy_all'));
        });
    }

    function download(format) {
        if (generatedUUIDs.length === 0) return;
        
        let content = '';
        let filename = 'uuids';
        let type = 'text/plain';

        switch(format) {
            case 'csv': content = generatedUUIDs.join(','); filename += '.csv'; type = 'text/csv'; break;
            case 'json': content = JSON.stringify(generatedUUIDs, null, 2); filename += '.json'; type = 'application/json'; break;
            case 'sql': 
                content = `INSERT INTO table_name (uuid) VALUES\n` + generatedUUIDs.map(u => `('${u}')`).join(',\n') + ';';
                filename += '.sql';
                break;
            default: content = generatedUUIDs.join('\n'); filename += '.txt';
        }

        const blob = new Blob([content], { type });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
        
        showToast(`Downloaded ${filename}`, 'success');
        window.dispatchEvent(new CustomEvent('uuid_download', { detail: { format } }));
    }

    function inspect() {
        const raw = elements.inspectInput.value.trim();
        if (!raw) return;

        const clean = raw.replace(/-/g, '').toLowerCase();
        const isHex = /^[0-9a-f]{32}$/.test(clean);
        
        elements.inspectResult.classList.remove('d-none');
        
        if (!isHex || (raw.includes('-') && !/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i.test(raw))) {
            elements.validBadge.className = 'alert alert-danger py-2 mb-3 small d-flex align-items-center';
            elements.validText.textContent = 'Invalid UUID Format';
            elements.inspectVersion.textContent = 'N/A';
            elements.inspectVariant.textContent = 'N/A';
            elements.inspectStructure.innerHTML = '<span class="text-danger">Provided string is not a valid 128-bit hex UUID.</span>';
            return;
        }

        // It's valid, let's analyze bits
        // Version is at 13th hex char (index 12)
        const version = clean.charAt(12);
        // Variant is at 17th hex char (index 16) - first bits of 9th byte
        const variantByte = parseInt(clean.charAt(16), 16);
        let variant = 'Unknown';
        if ((variantByte & 0x8) === 0) variant = 'NCS (0xx)';
        else if ((variantByte & 0xc) === 0x8) variant = 'RFC 4122 (10x)';
        else if ((variantByte & 0xe) === 0xc) variant = 'Microsoft (110)';
        else variant = 'Reserved (111)';

        elements.validBadge.className = 'alert alert-success py-2 mb-3 small d-flex align-items-center';
        elements.validText.textContent = 'Valid UUID Detected';
        elements.inspectVersion.textContent = `Version ${version}`;
        elements.inspectVariant.textContent = variant;

        // Visual Breakdown
        const s = clean;
        const html = `
            <span class="inspect-segment seg-time" title="Time Low">${s.slice(0, 8)}</span>-
            <span class="inspect-segment seg-time" title="Time Mid">${s.slice(8, 12)}</span>-
            <span class="inspect-segment seg-version" title="Version">${s.slice(12, 13)}</span><span class="inspect-segment seg-time" title="Time High">${s.slice(13, 16)}</span>-
            <span class="inspect-segment seg-variant" title="Variant">${s.slice(16, 18)}</span><span class="inspect-segment seg-node" title="Clock Seq">${s.slice(18, 20)}</span>-
            <span class="inspect-segment seg-node" title="Node ID">${s.slice(20)}</span>
        `;
        elements.inspectStructure.innerHTML = html;
        
        window.dispatchEvent(new CustomEvent('uuid_inspected', { detail: { version, variant } }));
    }

    return { init, copyOne, download };
})();

document.addEventListener('DOMContentLoaded', function() {
    UUIDTool.init();
});
</script>
@endpush
