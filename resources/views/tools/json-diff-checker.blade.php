@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />

    <div class="row g-4 mb-4">
        <!-- Input Panel A -->
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">JSON A (Original)</h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-info btn-toggle-highlight" data-panel="a" title="Toggle Highlight Mode"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-outline-secondary btn-format" data-panel="a" title="Beautify"><i class="bi bi-code-square"></i></button>
                        <button class="btn btn-outline-primary btn-example" data-panel="a">Example</button>
                        <button class="btn btn-outline-danger btn-clear" data-panel="a">Clear</button>
                    </div>
                </div>
                <div class="card-body p-0 position-relative editor-container">
                    <textarea id="jsonA" class="form-control border-0 font-monospace p-3" rows="15" placeholder="Paste your first JSON here..."></textarea>
                    <div id="highlightA" class="editor-highlight-view d-none p-3 font-monospace"></div>
                </div>
                <div class="card-footer bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="file" id="fileA" class="d-none" accept=".json,.txt">
                        <button class="btn btn-sm btn-outline-secondary btn-upload-trigger" data-target="fileA"><i class="bi bi-upload me-1"></i>Upload</button>
                        <span id="infoA" class="small text-muted italic">Empty</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Panel B -->
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-success">JSON B (Modified)</h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-info btn-toggle-highlight" data-panel="b" title="Toggle Highlight Mode"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-outline-secondary btn-format" data-panel="b" title="Beautify"><i class="bi bi-code-square"></i></button>
                        <button class="btn btn-outline-primary btn-example" data-panel="b">Example</button>
                        <button class="btn btn-outline-danger btn-clear" data-panel="b">Clear</button>
                    </div>
                </div>
                <div class="card-body p-0 position-relative editor-container">
                    <textarea id="jsonB" class="form-control border-0 font-monospace p-3" rows="15" placeholder="Paste your second JSON here..."></textarea>
                    <div id="highlightB" class="editor-highlight-view d-none p-3 font-monospace"></div>
                </div>
                <div class="card-footer bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="file" id="fileB" class="d-none" accept=".json,.txt">
                        <button class="btn btn-sm btn-outline-secondary btn-upload-trigger" data-target="fileB"><i class="bi bi-upload me-1"></i>Upload</button>
                        <span id="infoB" class="small text-muted italic">Empty</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Options Panel -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-gear-fill me-2"></i>Comparison Options</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="optPropOrder" checked>
                        <label class="form-check-label small" for="optPropOrder">Ignore Property Order</label>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="optWhitespace" checked>
                        <label class="form-check-label small" for="optWhitespace">Ignore Whitespace</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="optCase">
                        <label class="form-check-label small" for="optCase">Ignore Case</label>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="optArrayOrder">
                        <label class="form-check-label small" for="optArrayOrder">Ignore Array Order</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="optIgnoreKeys" class="form-label small fw-bold text-uppercase text-muted">Exclude Specific Keys (comma separated)</label>
                    <input type="text" id="optIgnoreKeys" class="form-control form-control-sm" placeholder="e.g. timestamp, updated_at, id">
                </div>
            </div>
            <div class="mt-4">
                <button id="btnCompare" class="btn btn-primary px-5 py-2 fw-bold w-100 w-md-auto">
                    <i class="bi bi-search me-2"></i>Compare JSON Documents
                </button>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="resultsArea" class="d-none">
        <!-- Summary Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="p-3 border rounded bg-white text-center shadow-sm h-100">
                    <div class="h3 mb-0 fw-bold text-primary" id="statDiffs">0</div>
                    <div class="small text-muted text-uppercase">Differences</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 border rounded bg-white text-center shadow-sm h-100">
                    <div class="h3 mb-0 fw-bold text-success" id="statAdded">0</div>
                    <div class="small text-muted text-uppercase">Added</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 border rounded bg-white text-center shadow-sm h-100">
                    <div class="h3 mb-0 fw-bold text-danger" id="statRemoved">0</div>
                    <div class="small text-muted text-uppercase">Removed</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="p-3 border rounded bg-white text-center shadow-sm h-100">
                    <div class="h3 mb-0 fw-bold text-warning" id="statModified">0</div>
                    <div class="small text-muted text-uppercase">Modified</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded bg-white text-center shadow-sm h-100">
                    <div class="h3 mb-0 fw-bold text-muted" id="statTime">0ms</div>
                    <div class="small text-muted text-uppercase">Time</div>
                </div>
            </div>
        </div>

        <!-- Diff Details -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Difference Breakdown</h5>
                <div class="d-flex gap-2">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="viewTable">Table</button>
                        <button type="button" class="btn btn-outline-secondary" id="viewVisual">Visual</button>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary btn-export" data-format="json"><i class="bi bi-filetype-json me-1"></i>JSON</button>
                        <button class="btn btn-outline-primary btn-export" data-format="csv"><i class="bi bi-filetype-csv me-1"></i>CSV</button>
                        <button class="btn btn-success btn-export" data-format="html"><i class="bi bi-filetype-html me-1"></i>Report</button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="tableContainer">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 small" id="diffTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="250">Path</th>
                                    <th>Original (A)</th>
                                    <th>Modified (B)</th>
                                    <th width="120">Type</th>
                                </tr>
                            </thead>
                            <tbody id="diffTableBody">
                                <!-- Diff rows -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="visualContainer" class="d-none">
                    <div class="visual-diff-container">
                        <div class="visual-diff-panel border-end">
                            <div class="visual-diff-line header text-primary">Original JSON (A)</div>
                            <div id="visualA"></div>
                        </div>
                        <div class="visual-diff-panel">
                            <div class="visual-diff-line header text-success">Modified JSON (B)</div>
                            <div id="visualB"></div>
                        </div>
                    </div>
                </div>

                <div id="noDiffMsg" class="text-center py-5 d-none">
                    <i class="bi bi-check-circle-fill text-success fs-1"></i>
                    <h5 class="mt-3 fw-bold">No differences found!</h5>
                    <p class="text-muted">The JSON documents are identical based on your options.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Content -->
    <div class="mt-5 border-top pt-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <h2 class="h4 fw-bold mb-4">Mastering JSON Comparison</h2>
                <div class="row g-4 text-muted small">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Why use a JSON-aware Diff?</h6>
                        <p>Standard text diff tools (like WinMerge or Git diff) treat JSON as plain text. If you beautify one file and minify another, they will show every single line as different. A true JSON diff engine parses the structure, ignoring whitespace and property order to find real data changes.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Handling Dynamic Keys</h6>
                        <p>When comparing API responses, keys like <code>id</code>, <code>created_at</code>, or <code>request_id</code> often differ between environments. Use the <strong>Exclude Specific Keys</strong> option to filter these out and focus on the business logic of your payload.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Array Comparison Modes</h6>
                        <p>By default, arrays are compared by index. If your API returns a list of items where order isn't guaranteed (like tags or search results), enable <strong>Ignore Array Order</strong> to check if the same items exist regardless of their position.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Privacy & Performance</h6>
                        <p>Toolzy's JSON Diff Checker works 100% locally. We never transmit your data to any server. This makes it safe for comparing production configurations, private user data, or sensitive API keys.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Diff Tips</h6>
                        <ul class="small text-muted ps-3 mb-0">
                            <li class="mb-2"><strong>Normalization:</strong> Use the beautify button to clean up your JSON before starting.</li>
                            <li class="mb-2"><strong>JSON Paths:</strong> Click on a path in the breakdown table to copy it to your clipboard.</li>
                            <li class="mb-2"><strong>Shortcuts:</strong> Press <kbd>Ctrl + Enter</kbd> to trigger the comparison.</li>
                            <li class="mb-2"><strong>Large Files:</strong> Our engine can handle multi-megabyte payloads smoothly.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

#jsonA, #jsonB {
    font-size: 0.85rem;
    line-height: 1.5;
    background-color: #fcfcfc;
    resize: vertical;
}

.table-responsive {
    max-height: 600px;
}

#diffTable code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    color: #e83e8c;
    word-break: break-all;
}

.diff-path {
    cursor: pointer;
    color: #0d6efd;
    font-weight: bold;
}
.diff-path:hover {
    text-decoration: underline;
}

.diff-added { background-color: rgba(25, 135, 84, 0.15); border-left: 4px solid #198754; }
.diff-removed { background-color: rgba(220, 53, 69, 0.15); border-left: 4px solid #dc3545; }
.diff-modified { background-color: rgba(255, 193, 7, 0.15); border-left: 4px solid #ffc107; }

.visual-diff-container {
    display: flex;
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    font-size: 0.8rem;
    line-height: 1.5;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    overflow-x: auto;
}

.visual-diff-panel {
    flex: 1;
    min-width: 300px;
    padding: 1rem;
    white-space: pre;
}

.visual-diff-line {
    display: block;
    margin: 0 -1rem;
    padding: 0 1rem;
}

.visual-diff-line.added { background-color: #e6ffec; color: #24292e; }
.visual-diff-line.removed { background-color: #ffebe9; color: #24292e; }
.visual-diff-line.modified { background-color: #fff8c5; color: #24292e; }
.visual-diff-line.header { font-weight: bold; background: #e9ecef; border-bottom: 1px solid #dee2e6; margin-top: -1rem; margin-bottom: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.5rem; }

.diff-type-badge {
    font-size: 0.7rem;
    text-transform: uppercase;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 4px;
}

.italic { font-style: italic; }

.editor-container {
    min-height: 400px;
}

.editor-highlight-view {
    background-color: #fcfcfc;
    min-height: 400px;
    max-height: 600px;
    overflow: auto;
    white-space: pre;
    font-size: 0.85rem;
    line-height: 1.5;
    border: 1px solid #dee2e6;
    border-radius: 4px;
}

.editor-highlight-view .visual-diff-line {
    margin: 0 -1rem;
    padding: 0 1rem;
}
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const DiffTool = (function() {
    let diffs = [];
    let stats = { added: 0, removed: 0, modified: 0 };
    
    const elements = {
        jsonA: document.getElementById('jsonA'),
        jsonB: document.getElementById('jsonB'),
        btnCompare: document.getElementById('btnCompare'),
        resultsArea: document.getElementById('resultsArea'),
        diffTableBody: document.getElementById('diffTableBody'),
        noDiffMsg: document.getElementById('noDiffMsg'),
        statDiffs: document.getElementById('statDiffs'),
        statAdded: document.getElementById('statAdded'),
        statRemoved: document.getElementById('statRemoved'),
        statModified: document.getElementById('statModified'),
        statTime: document.getElementById('statTime'),
        infoA: document.getElementById('infoA'),
        infoB: document.getElementById('infoB'),
        highlightA: document.getElementById('highlightA'),
        highlightB: document.getElementById('highlightB'),
        tableContainer: document.getElementById('tableContainer'),
        visualContainer: document.getElementById('visualContainer'),
        visualA: document.getElementById('visualA'),
        visualB: document.getElementById('visualB'),
        viewTable: document.getElementById('viewTable'),
        viewVisual: document.getElementById('viewVisual')
    };

    function init() {
        bindEvents();
    }

    function bindEvents() {
        elements.btnCompare.addEventListener('click', () => compare());
        
        document.getElementById('fileA').addEventListener('change', (e) => handleFileUpload(e, 'a'));
        document.getElementById('fileB').addEventListener('change', (e) => handleFileUpload(e, 'b'));
        
        // Debounced auto-compare on input
        let timeout;
        const autoCompare = () => {
            clearTimeout(timeout);
            timeout = setTimeout(() => compare(true), 500);
        };
        elements.jsonA.addEventListener('input', autoCompare);
        elements.jsonB.addEventListener('input', autoCompare);

        // Shortcut Ctrl + Enter
        window.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                compare();
            }
        });

        // Format buttons
        document.querySelectorAll('.btn-format').forEach(btn => {
            btn.addEventListener('click', () => format(btn.getAttribute('data-panel')));
        });

        // Example buttons
        document.querySelectorAll('.btn-example').forEach(btn => {
            btn.addEventListener('click', () => loadExample(btn.getAttribute('data-panel')));
        });

        // Clear buttons
        document.querySelectorAll('.btn-clear').forEach(btn => {
            btn.addEventListener('click', () => clear(btn.getAttribute('data-panel')));
        });

        // Toggle Highlights buttons
        document.querySelectorAll('.btn-toggle-highlight').forEach(btn => {
            btn.addEventListener('click', () => toggleHighlight(btn.getAttribute('data-panel')));
        });

        // Upload triggers
        document.querySelectorAll('.btn-upload-trigger').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById(btn.getAttribute('data-target')).click();
            });
        });

        // Export buttons
        document.querySelectorAll('.btn-export').forEach(btn => {
            btn.addEventListener('click', () => exportReport(btn.getAttribute('data-format')));
        });

        // View Toggles
        elements.viewTable.addEventListener('click', () => {
            elements.viewTable.classList.add('active');
            elements.viewVisual.classList.remove('active');
            elements.tableContainer.classList.remove('d-none');
            elements.visualContainer.classList.add('d-none');
        });

        elements.viewVisual.addEventListener('click', () => {
            elements.viewVisual.classList.add('active');
            elements.viewTable.classList.remove('active');
            elements.visualContainer.classList.remove('d-none');
            elements.tableContainer.classList.add('d-none');
            renderVisualDiff();
        });

        // Diff path delegation
        elements.diffTableBody.addEventListener('click', (e) => {
            if (e.target.classList.contains('diff-path')) {
                copyPath(e.target.getAttribute('data-path'));
            }
        });
    }

    function compare(silent = false) {
        const rawA = elements.jsonA.value.trim();
        const rawB = elements.jsonB.value.trim();

        if (!rawA || !rawB) {
            if (!silent) showToast('Please enter JSON in both panels', 'warning');
            return;
        }

        try {
            const start = performance.now();
            const objA = JSON.parse(rawA);
            const objB = JSON.parse(rawB);

            diffs = [];
            stats = { added: 0, removed: 0, modified: 0 };

            const options = {
                ignoreKeys: document.getElementById('optIgnoreKeys').value.split(',').map(k => k.trim()).filter(k => k),
                ignoreCase: document.getElementById('optCase').checked,
                ignoreArrayOrder: document.getElementById('optArrayOrder').checked,
                ignoreWhitespace: document.getElementById('optWhitespace').checked
            };

            recursiveDiff(objA, objB, '', options);

            const end = performance.now();
            renderResults(end - start);
            if (!silent) showToast('Comparison complete', 'success');
        } catch (e) {
            if (!silent) showToast('Invalid JSON: ' + e.message, 'danger');
        }
    }

    function addDiff(path, oldVal, newVal, type) {
        diffs.push({ path, oldVal, newVal, type });
        stats[type]++;
    }

    function recursiveDiff(a, b, path, opts) {
        // If keys should be ignored
        const key = path.split('.').pop();
        if (opts.ignoreKeys.includes(key)) return;

        // Check if types are different
        if (typeof a !== typeof b) {
            addDiff(path, a, b, 'modified');
            return;
        }

        // Handle Nulls
        if (a === null || b === null) {
            if (a !== b) addDiff(path, a, b, 'modified');
            return;
        }

        // Handle Arrays
        if (Array.isArray(a)) {
            if (!Array.isArray(b)) {
                addDiff(path, a, b, 'modified');
                return;
            }

            if (opts.ignoreArrayOrder) {
                // Simplified set-based comparison for ignore order
                const setA = new Set(a.map(i => JSON.stringify(i)));
                const setB = new Set(b.map(i => JSON.stringify(i)));
                
                a.forEach((item, i) => {
                    if (!setB.has(JSON.stringify(item))) {
                        addDiff(`${path}[${i}]`, item, undefined, 'removed');
                    }
                });
                b.forEach((item, i) => {
                    if (!setA.has(JSON.stringify(item))) {
                        addDiff(`${path}[${i}]`, undefined, item, 'added');
                    }
                });
            } else {
                const maxLen = Math.max(a.length, b.length);
                for (let i = 0; i < maxLen; i++) {
                    recursiveDiff(a[i], b[i], `${path}[${i}]`, opts);
                }
            }
            return;
        }

        // Handle Objects
        if (typeof a === 'object') {
            const keysA = Object.keys(a);
            const keysB = Object.keys(b);
            const allKeys = [...new Set([...keysA, ...keysB])];

            allKeys.forEach(k => {
                const newPath = path ? `${path}.${k}` : k;
                if (!(k in a)) {
                    addDiff(newPath, undefined, b[k], 'added');
                } else if (!(k in b)) {
                    addDiff(newPath, a[k], undefined, 'removed');
                } else {
                    recursiveDiff(a[k], b[k], newPath, opts);
                }
            });
            return;
        }

        // Handle Primitives
        let valA = a;
        let valB = b;

        if (opts.ignoreCase && typeof a === 'string' && typeof b === 'string') {
            valA = a.toLowerCase();
            valB = b.toLowerCase();
        }

        if (valA !== valB) {
            addDiff(path, a, b, 'modified');
        }
    }

    function toggleHighlight(panel) {
        const textarea = (panel === 'a') ? elements.jsonA : elements.jsonB;
        const highlightDiv = (panel === 'a') ? elements.highlightA : elements.highlightB;
        const btn = document.querySelector(`.btn-toggle-highlight[data-panel="${panel}"]`);

        if (highlightDiv.classList.contains('d-none')) {
            if (!textarea.value.trim()) return showToast('Enter JSON first', 'warning');
            try {
                // Ensure it's beautified for matching lines
                const obj = JSON.parse(textarea.value);
                textarea.value = JSON.stringify(obj, null, 4);
                
                // Refresh diffs before showing highlight
                compare(true);

                textarea.classList.add('d-none');
                highlightDiv.classList.remove('d-none');
                btn.classList.add('active');
                renderInPanelHighlight(panel);
            } catch (e) {
                showToast('Invalid JSON', 'danger');
            }
        } else {
            textarea.classList.remove('d-none');
            highlightDiv.classList.add('d-none');
            btn.classList.remove('active');
        }
    }

    function renderVisualDiff() {
        try {
            const objA = JSON.parse(elements.jsonA.value);
            const objB = JSON.parse(elements.jsonB.value);
            const linesA = JSON.stringify(objA, null, 4).split('\n');
            const linesB = JSON.stringify(objB, null, 4).split('\n');
            const diffPaths = diffs.reduce((acc, d) => { acc[d.path] = d.type; return acc; }, {});

            elements.visualA.innerHTML = generateHighlightedLines(linesA, diffPaths, 'removed');
            elements.visualB.innerHTML = generateHighlightedLines(linesB, diffPaths, 'added');
        } catch (e) {}
    }

    function renderResults(time) {
        elements.resultsArea.classList.remove('d-none');
        elements.statDiffs.textContent = diffs.length;
        elements.statAdded.textContent = stats.added;
        elements.statRemoved.textContent = stats.removed;
        elements.statModified.textContent = stats.modified;
        elements.statTime.textContent = time.toFixed(2) + 'ms';

        if (diffs.length === 0) {
            elements.diffTableBody.innerHTML = '';
            elements.noDiffMsg.classList.remove('d-none');
            return;
        }

        elements.noDiffMsg.classList.add('d-none');
        elements.diffTableBody.innerHTML = diffs.map(d => `
            <tr class="diff-${d.type}">
                <td><span class="diff-path" data-path="${d.path}">${d.path || 'root'}</span></td>
                <td><code>${formatVal(d.oldVal)}</code></td>
                <td><code>${formatVal(d.newVal)}</code></td>
                <td><span class="diff-type-badge bg-${getTypeColor(d.type)} text-white">${d.type}</span></td>
            </tr>
        `).join('');

        // Sync visual diff if visible
        if (!elements.visualContainer.classList.contains('d-none')) {
            renderVisualDiff();
        }

        // Sync in-panel highlights if visible
        if (!elements.highlightA.classList.contains('d-none')) renderInPanelHighlight('a');
        if (!elements.highlightB.classList.contains('d-none')) renderInPanelHighlight('b');
    }

    function renderInPanelHighlight(panel) {
        const textarea = (panel === 'a') ? elements.jsonA : elements.jsonB;
        const highlightDiv = (panel === 'a') ? elements.highlightA : elements.highlightB;
        const activeType = (panel === 'a') ? 'removed' : 'added';

        try {
            const obj = JSON.parse(textarea.value);
            const lines = JSON.stringify(obj, null, 4).split('\n');
            const diffPaths = diffs.reduce((acc, d) => {
                acc[d.path] = d.type;
                return acc;
            }, {});

            highlightDiv.innerHTML = generateHighlightedLines(lines, diffPaths, activeType);
        } catch (e) {}
    }

    function generateHighlightedLines(lines, diffPaths, activeType) {
        const pathStack = []; // Stores keys/indices at each depth
        const arrayIndices = []; // Stores current index if depth is an array

        return lines.map((line) => {
            const trimmed = line.trim();
            const indent = line.search(/\S/);
            if (indent === -1 || trimmed === '{' || trimmed === '[' || trimmed === '}' || trimmed === ']' || trimmed === '},' || trimmed === '],') {
                return `<span class="visual-diff-line">${escapeHtml(line)}</span>`;
            }
            
            const depth = Math.floor(indent / 4);
            let type = '';
            let currentPath = '';

            const keyMatch = line.match(/"([^"]+)":/);

            if (keyMatch) {
                const key = keyMatch[1];
                pathStack[depth] = key;
                pathStack.length = depth + 1;
                arrayIndices[depth] = -1; 
                
                // Construct path: omit root depth if it's just the wrapper
                currentPath = pathStack.slice(1).join('.');
            } else {
                // It's an array element (primitive or start of object/array)
                if (arrayIndices[depth] === undefined || arrayIndices[depth] === null) arrayIndices[depth] = 0;
                else arrayIndices[depth]++;
                
                const parentPath = pathStack.slice(1, depth).join('.');
                const indexPart = `[${arrayIndices[depth]}]`;
                currentPath = parentPath ? `${parentPath}${indexPart}` : indexPart;
            }

            if (currentPath && diffPaths[currentPath]) {
                const dType = diffPaths[currentPath];
                if (dType === 'modified' || dType === activeType) {
                    type = dType;
                }
            }

            return `<span class="visual-diff-line ${type}">${escapeHtml(line)}</span>`;
        }).join('');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatVal(v) {
        if (v === undefined) return '<span class="text-muted italic">undefined</span>';
        if (v === null) return 'null';
        if (typeof v === 'object') return JSON.stringify(v);
        return String(v);
    }

    function getTypeColor(t) {
        if (t === 'added') return 'success';
        if (t === 'removed') return 'danger';
        return 'warning';
    }

    function format(panel) {
        const el = (panel === 'a') ? elements.jsonA : elements.jsonB;
        try {
            const obj = JSON.parse(el.value);
            el.value = JSON.stringify(obj, null, 4);
            showToast('JSON beautified', 'info');
        } catch (e) {
            showToast('Cannot format invalid JSON', 'danger');
        }
    }

    function handleFileUpload(e, panel) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (event) => {
            const val = event.target.result;
            if (panel === 'a') {
                elements.jsonA.value = val;
                elements.infoA.textContent = `${file.name} (${formatSize(file.size)})`;
            } else {
                elements.jsonB.value = val;
                elements.infoB.textContent = `${file.name} (${formatSize(file.size)})`;
            }
            showToast(`Loaded ${file.name}`, 'info');
        };
        reader.readAsText(file);
    }

    function formatSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024, sizes = ['B', 'KB', 'MB'], i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function loadExample(panel) {
        const examples = {
            a: { id: 1, name: "Toolzy", version: "1.0", features: ["Fast", "Private"], settings: { theme: "dark", notifications: true } },
            b: { id: 1, name: "Toolzy App", version: "1.1", features: ["Fast", "Private", "Secure"], settings: { theme: "light" }, author: "Dheeraj" }
        };
        
        elements.jsonA.value = JSON.stringify(examples.a, null, 4);
        elements.jsonB.value = JSON.stringify(examples.b, null, 4);
        compare();
    }

    function clear(panel) {
        const el = (panel === 'a') ? elements.jsonA : elements.jsonB;
        const info = (panel === 'a') ? elements.infoA : elements.infoB;
        el.value = '';
        info.textContent = 'Empty';
    }

    function copyPath(p) {
        navigator.clipboard.writeText(p).then(() => showToast('Path copied: ' + p, 'success'));
    }

    function exportReport(format) {
        if (diffs.length === 0 && !elements.resultsArea.classList.contains('d-none')) {
            return showToast('No differences to export', 'info');
        }

        let content = '';
        let filename = `json-diff-report.${format}`;
        let type = 'text/plain';

        if (format === 'json') {
            content = JSON.stringify({ summary: stats, differences: diffs }, null, 2);
            type = 'application/json';
        } else if (format === 'csv') {
            content = `"Path","Original","Modified","Type"\n` + diffs.map(d => `"${d.path}","${String(d.oldVal).replace(/"/g, '""')}","${String(d.newVal).replace(/"/g, '""')}","${d.type}"`).join('\n');
            type = 'text/csv';
        } else if (format === 'html') {
            content = generateHtmlReport();
            type = 'text/html';
        }

        const blob = new Blob([content], { type });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
    }

    function generateHtmlReport() {
        return `
            <!DOCTYPE html>
            <html>
            <head>
                <title>JSON Diff Report</title>
                <style nonce="{{ $cspNonce }}">
                    body { font-family: sans-serif; padding: 40px; color: #333; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                    th { background: #f4f4f4; }
                    .added { background: #e6ffec; }
                    .removed { background: #ffebe9; }
                    .modified { background: #fff8c5; }
                    .summary { display: flex; gap: 20px; margin-bottom: 30px; }
                    .stat { border: 1px solid #ddd; padding: 15px; border-radius: 8px; flex: 1; text-align: center; }
                </style>
            </head>
            <body>
                <h1>JSON Difference Report</h1>
                <p>Generated by Toolzy on ${new Date().toLocaleString()}</p>
                <div class="summary">
                    <div class="stat"><b>Total Diffs:</b><br>${diffs.length}</div>
                    <div class="stat"><b>Added:</b><br>${stats.added}</div>
                    <div class="stat"><b>Removed:</b><br>${stats.removed}</div>
                    <div class="stat"><b>Modified:</b><br>${stats.modified}</div>
                </div>
                <table>
                    <thead><tr><th>Path</th><th>Original</th><th>Modified</th><th>Type</th></tr></thead>
                    <tbody>
                        ${diffs.map(d => `<tr class="${d.type}"><td>${d.path}</td><td>${formatVal(d.oldVal)}</td><td>${formatVal(d.newVal)}</td><td>${d.type}</td></tr>`).join('')}
                    </tbody>
                </table>
            </body>
            </html>
        `;
    }

    return { init, format, loadExample, clear, compare, copyPath, exportReport };
})();

document.addEventListener('DOMContentLoaded', () => {
    DiffTool.init();
});
</script>
@endpush
