@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />

    <!-- Mode Selector -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2">
                    <ul class="nav nav-pills nav-fill" id="modeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold" id="jsonToCsv-tab" data-bs-toggle="tab" data-bs-target="#jsonToCsv" type="button" role="tab"><i class="bi bi-filetype-json me-2"></i>JSON to CSV</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="csvToJson-tab" data-bs-toggle="tab" data-bs-target="#csvToJson" type="button" role="tab"><i class="bi bi-filetype-csv me-2"></i>CSV to JSON</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content" id="modeTabContent">
        <!-- JSON to CSV Panel -->
        <div class="tab-pane fade show active" id="jsonToCsv" role="tabpanel" aria-labelledby="jsonToCsv-tab">
            <div class="row g-4">
                <!-- Input Section -->
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Input JSON</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary load-example-btn" data-type="json">Example</button>
                                <button class="btn btn-sm btn-outline-danger clear-btn" data-type="json">Clear</button>
                            </div>
                        </div>
                        <div class="card-body p-0 position-relative">
                            <div id="dropZoneJSON" class="drop-zone d-none">
                                <div class="drop-zone-content">
                                    <i class="bi bi-cloud-upload fs-1"></i>
                                    <p class="mb-0 mt-2 fw-bold">Drop JSON File Here</p>
                                </div>
                            </div>
                            <textarea id="jsonInput" class="form-control border-0 font-monospace p-3" rows="15" placeholder="Paste your JSON array or object here..."></textarea>
                        </div>
                        <div class="card-footer bg-light py-2">
                            <input type="file" id="fileInputJSON" class="d-none" accept=".json,.txt">
                            <button id="btnUploadJSON" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-upload me-1"></i>Upload JSON File</button>
                        </div>
                    </div>
                </div>

                <!-- Options & Stats -->
                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Conversion Options</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Flattening</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="optFlatten" checked>
                                        <label class="form-check-label small" for="optFlatten">Flatten Nested Objects</label>
                                    </div>
                                    <select id="optFlattenSep" class="form-select form-select-sm">
                                        <option value=".">Dot Separator (user.name)</option>
                                        <option value="_">Underscore (user_name)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Array Handling</label>
                                    <select id="optArrayJoin" class="form-select form-select-sm">
                                        <option value=",">Join with Comma</option>
                                        <option value="|">Join with Pipe (|)</option>
                                        <option value=";">Join with Semicolon</option>
                                        <option value="json">Keep as JSON string</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <hr class="my-2">
                                    <button id="btnConvertJSON" class="btn btn-primary w-100 py-2 fw-bold">Convert JSON to CSV <i class="bi bi-arrow-right ms-2"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Output CSV</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary copy-output-btn" data-type="csv"><i class="bi bi-clipboard me-1"></i>Copy</button>
                                <button class="btn btn-sm btn-success download-btn" data-type="csv"><i class="bi bi-download me-1"></i>Download</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <textarea id="csvOutput" class="form-control border-0 font-monospace p-3" rows="8" readonly placeholder="Converted CSV will appear here..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CSV to JSON Panel -->
        <div class="tab-pane fade" id="csvToJson" role="tabpanel" aria-labelledby="csvToJson-tab">
            <div class="row g-4">
                <!-- Input Section -->
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Input CSV</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary load-example-btn" data-type="csv">Example</button>
                                <button class="btn btn-sm btn-outline-danger clear-btn" data-type="csv">Clear</button>
                            </div>
                        </div>
                        <div class="card-body p-0 position-relative">
                            <div id="dropZoneCSV" class="drop-zone d-none">
                                <div class="drop-zone-content">
                                    <i class="bi bi-cloud-upload fs-1"></i>
                                    <p class="mb-0 mt-2 fw-bold">Drop CSV File Here</p>
                                </div>
                            </div>
                            <textarea id="csvInput" class="form-control border-0 font-monospace p-3" rows="15" placeholder="Paste your CSV data here..."></textarea>
                        </div>
                        <div class="card-footer bg-light py-2">
                            <input type="file" id="fileInputCSV" class="d-none" accept=".csv,.txt">
                            <button id="btnUploadCSV" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-upload me-1"></i>Upload CSV File</button>
                        </div>
                    </div>
                </div>

                <!-- Options & Stats -->
                <div class="col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">CSV Options</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Delimiter</label>
                                    <select id="csvDelimiter" class="form-select form-select-sm">
                                        <option value=",">Comma (,)</option>
                                        <option value=";">Semicolon (;)</option>
                                        <option value="tab">Tab (\t)</option>
                                        <option value="|">Pipe (|)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Header Row</label>
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input" type="checkbox" id="csvHasHeader" checked>
                                        <label class="form-check-label small" for="csvHasHeader">Contains Headers</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Types</label>
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input" type="checkbox" id="csvAutoType" checked>
                                        <label class="form-check-label small" for="csvAutoType">Auto Detect Types</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <hr class="my-2">
                                    <button id="btnConvertCSV" class="btn btn-primary w-100 py-2 fw-bold">Convert CSV to JSON <i class="bi bi-arrow-right ms-2"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">Output JSON</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary copy-output-btn" data-type="json"><i class="bi bi-clipboard me-1"></i>Copy</button>
                                <button class="btn btn-sm btn-success download-btn" data-type="json"><i class="bi bi-download me-1"></i>Download</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <textarea id="jsonOutput" class="form-control border-0 font-monospace p-3" rows="8" readonly placeholder="Converted JSON will appear here..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Preview & Stats Section -->
    <div id="resultSection" class="mt-4 d-none">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold">Statistics</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Rows / Records:</span>
                                <span id="statRows" class="fw-bold">-</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Columns / Fields:</span>
                                <span id="statCols" class="fw-bold">-</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Input Size:</span>
                                <span id="statInSize" class="fw-bold">-</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Output Size:</span>
                                <span id="statOutSize" class="fw-bold">-</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Time:</span>
                                <span id="statTime" class="fw-bold">-</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Data Preview (First 100 rows)</h6>
                        <span class="badge bg-primary rounded-pill small" id="previewCount">0 rows</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive preview-table-container">
                            <table class="table table-hover table-sm mb-0 small text-nowrap" id="previewTable">
                                <!-- Preview table content -->
                            </table>
                        </div>
                        <div id="previewEmpty" class="text-center py-5 text-muted italic">
                            Convert some data to see the preview
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Content & FAQ -->
    <div class="mt-5 border-top pt-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <h2 class="h4 fw-bold mb-4">Mastering JSON and CSV Data Transformation</h2>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h5 class="fw-bold small text-uppercase text-primary">When to use JSON?</h5>
                        <p class="text-muted small">JSON is the standard for modern web applications. It supports complex nesting and different data types (strings, numbers, booleans, arrays). Use JSON when working with APIs, NoSQL databases like MongoDB, or when preserving hierarchical relationships in your data.</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold small text-uppercase text-success">When to use CSV?</h5>
                        <p class="text-muted small">CSV is the universal language of spreadsheets and data analysis. It's incredibly efficient for flat datasets and is supported by every major tool, including Excel, Google Sheets, and SQL databases. Use CSV for data exports, reporting, and bulk imports.</p>
                    </div>
                    <div class="col-md-12">
                        <h5 class="fw-bold small text-uppercase text-warning">Handling Nested Data</h5>
                        <p class="text-muted small">The biggest challenge in JSON to CSV conversion is hierarchy. Our tool uses <strong>Recursive Flattening</strong> to convert nested objects into dot-notation headers. For example, <code>{"user": {"id": 1}}</code> becomes a single column <code>user.id</code>. This ensures that no data is lost during the transformation to a flat format.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Conversion Best Practices</h6>
                        <ul class="small text-muted ps-3">
                            <li class="mb-2"><strong>Validate Input:</strong> Ensure your JSON is a valid array of objects for the cleanest CSV export.</li>
                            <li class="mb-2"><strong>Check Delimiters:</strong> If your data contains commas, use a Semicolon or Tab as a delimiter to avoid broken columns.</li>
                            <li class="mb-2"><strong>UTF-8 Encoding:</strong> Toolzy uses UTF-8 by default, supporting international characters and emojis.</li>
                            <li class="mb-2"><strong>Type Detection:</strong> Keep auto-type detection enabled to automatically convert numeric strings back to numbers in JSON.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="mt-5">
        <x-faq :tool="$tool" />
    </div>
</div>

<style nonce="{{ $cspNonce }}">
.preview-table-container {
    max-height: 400px;
}

.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

#jsonInput, #csvInput, #jsonOutput, #csvOutput {
    resize: none;
    font-size: 0.85rem;
    line-height: 1.5;
}

.drop-zone {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
    background: rgba(13, 110, 253, 0.9);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px dashed #fff;
}

.nav-pills .nav-link {
    color: #6c757d;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 0 4px;
}

.nav-pills .nav-link.active {
    background-color: #0d6efd;
    color: white;
}

.italic {
    font-style: italic;
}

.x-small {
    font-size: 0.7rem;
}

.table-sm th {
    background-color: #f8f9fa;
    position: sticky;
    top: 0;
    z-index: 1;
}
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const ConverterTool = (function() {
    let currentData = null;
    let currentMode = 'json'; // json or csv
    
    const elements = {
        jsonIn: document.getElementById('jsonInput'),
        csvIn: document.getElementById('csvInput'),
        jsonOut: document.getElementById('jsonOutput'),
        csvOut: document.getElementById('csvOutput'),
        btnConvertJSON: document.getElementById('btnConvertJSON'),
        btnConvertCSV: document.getElementById('btnConvertCSV'),
        resultSection: document.getElementById('resultSection'),
        previewTable: document.getElementById('previewTable'),
        previewEmpty: document.getElementById('previewEmpty'),
        previewCount: document.getElementById('previewCount'),
        statRows: document.getElementById('statRows'),
        statCols: document.getElementById('statCols'),
        statInSize: document.getElementById('statInSize'),
        statOutSize: document.getElementById('statOutSize'),
        statTime: document.getElementById('statTime')
    };

    function init() {
        bindEvents();
        setupDragAndDrop('dropZoneJSON', 'jsonInput', 'json');
        setupDragAndDrop('dropZoneCSV', 'csvInput', 'csv');
    }

    function bindEvents() {
        elements.btnConvertJSON.addEventListener('click', convertJSONToCSV);
        elements.btnConvertCSV.addEventListener('click', convertCSVToJSON);
        
        document.getElementById('fileInputJSON').addEventListener('change', (e) => handleFileUpload(e, 'json'));
        document.getElementById('fileInputCSV').addEventListener('change', (e) => handleFileUpload(e, 'csv'));
        
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', (e) => {
                currentMode = e.target.id.includes('json') ? 'json' : 'csv';
            });
        });

        // Load example buttons
        document.querySelectorAll('.load-example-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                loadExample(btn.getAttribute('data-type'));
            });
        });

        // Clear buttons
        document.querySelectorAll('.clear-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                clear(btn.getAttribute('data-type'));
            });
        });

        // Upload buttons
        document.getElementById('btnUploadJSON').addEventListener('click', () => {
            document.getElementById('fileInputJSON').click();
        });
        document.getElementById('btnUploadCSV').addEventListener('click', () => {
            document.getElementById('fileInputCSV').click();
        });

        // Copy output buttons
        document.querySelectorAll('.copy-output-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                copyOutput(btn.getAttribute('data-type'));
            });
        });

        // Download buttons
        document.querySelectorAll('.download-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                download(btn.getAttribute('data-type'));
            });
        });
    }

    // --- JSON TO CSV Logic ---

    function flattenObject(obj, prefix = '', sep = '.', result = {}) {
        for (const [key, value] of Object.entries(obj)) {
            const newKey = prefix ? `${prefix}${sep}${key}` : key;
            if (value !== null && typeof value === 'object' && !Array.isArray(value)) {
                flattenObject(value, newKey, sep, result);
            } else {
                result[newKey] = value;
            }
        }
        return result;
    }

    function processArrays(arr, method) {
        if (!Array.isArray(arr)) return arr;
        if (method === 'json') return JSON.stringify(arr);
        return arr.join(method);
    }

    function convertJSONToCSV() {
        const input = elements.jsonIn.value.trim();
        if (!input) return showToast('Please enter some JSON data', 'warning');

        try {
            const start = performance.now();
            let data = JSON.parse(input);
            if (!Array.isArray(data)) data = [data];

            const flatten = document.getElementById('optFlatten').checked;
            const sep = document.getElementById('optFlattenSep').value;
            const arrayMethod = document.getElementById('optArrayJoin').value;

            // Step 1: Flatten and collect headers
            const processedData = data.map(item => {
                let flat = flatten ? flattenObject(item, '', sep) : item;
                // Handle complex values
                for (let k in flat) {
                    if (Array.isArray(flat[k])) {
                        flat[k] = processArrays(flat[k], arrayMethod);
                    } else if (typeof flat[k] === 'object' && flat[k] !== null) {
                        flat[k] = JSON.stringify(flat[k]);
                    }
                }
                return flat;
            });

            const headers = [...new Set(processedData.flatMap(Object.keys))];
            
            // Step 2: Generate CSV string
            let csvRows = [headers.map(h => escapeCSV(h)).join(',')];
            
            processedData.forEach(row => {
                const values = headers.map(header => {
                    const val = row[header];
                    return escapeCSV(val === undefined || val === null ? '' : val);
                });
                csvRows.push(values.join(','));
            });

            const csvString = csvRows.join('\n');
            elements.csvOut.value = csvString;
            
            const end = performance.now();
            updateResults(processedData, headers, input.length, csvString.length, end - start);
            showToast('JSON converted to CSV successfully', 'success');
        } catch (e) {
            showToast('Invalid JSON: ' + e.message, 'danger');
        }
    }

    function escapeCSV(val) {
        let str = (typeof val === 'object' && val !== null) ? JSON.stringify(val) : String(val);
        if (str.includes(',') || str.includes('"') || str.includes('\n') || str.includes('\r')) {
            str = '"' + str.replace(/"/g, '""') + '"';
        }
        return str;
    }

    // --- CSV TO JSON Logic ---

    function convertCSVToJSON() {
        const input = elements.csvIn.value.trim();
        if (!input) return showToast('Please enter some CSV data', 'warning');

        const start = performance.now();
        const delimiter = document.getElementById('csvDelimiter').value === 'tab' ? '\t' : document.getElementById('csvDelimiter').value;
        const hasHeader = document.getElementById('csvHasHeader').checked;
        const autoType = document.getElementById('csvAutoType').checked;

        try {
            const rows = parseCSV(input, delimiter);
            if (rows.length === 0) throw new Error('No data found');

            let headers = [];
            let startIndex = 0;

            if (hasHeader) {
                headers = rows[0];
                startIndex = 1;
            } else {
                headers = rows[0].map((_, i) => `column_${i + 1}`);
            }

            const jsonResults = [];
            for (let i = startIndex; i < rows.length; i++) {
                const obj = {};
                rows[i].forEach((cell, idx) => {
                    if (idx < headers.length) {
                        let val = cell.trim();
                        if (autoType) val = detectType(val);
                        obj[headers[idx]] = val;
                    }
                });
                jsonResults.push(obj);
            }

            const jsonString = JSON.stringify(jsonResults, null, 2);
            elements.jsonOut.value = jsonString;

            const end = performance.now();
            updateResults(jsonResults, headers, input.length, jsonString.length, end - start);
            showToast('CSV converted to JSON successfully', 'success');
        } catch (e) {
            showToast('Error parsing CSV: ' + e.message, 'danger');
        }
    }

    function parseCSV(text, delimiter) {
        const rows = [];
        let currentRow = [];
        let currentCell = '';
        let inQuotes = false;

        for (let i = 0; i < text.length; i++) {
            const char = text[i];
            const nextChar = text[i + 1];

            if (char === '"' && inQuotes && nextChar === '"') {
                currentCell += '"';
                i++;
            } else if (char === '"') {
                inQuotes = !inQuotes;
            } else if (char === delimiter && !inQuotes) {
                currentRow.push(currentCell);
                currentCell = '';
            } else if ((char === '\r' || char === '\n') && !inQuotes) {
                if (char === '\r' && nextChar === '\n') i++;
                currentRow.push(currentCell);
                rows.push(currentRow);
                currentRow = [];
                currentCell = '';
            } else {
                currentCell += char;
            }
        }
        
        if (currentCell || currentRow.length > 0) {
            currentRow.push(currentCell);
            rows.push(currentRow);
        }

        return rows.filter(r => r.length > 0 && r.some(c => c !== ''));
    }

    function detectType(val) {
        if (val === '') return '';
        if (val.toLowerCase() === 'true') return true;
        if (val.toLowerCase() === 'false') return false;
        if (val.toLowerCase() === 'null') return null;
        if (!isNaN(val) && !isNaN(parseFloat(val))) return Number(val);
        return val;
    }

    // --- UI Helpers ---

    function updateResults(data, headers, inSize, outSize, time) {
        elements.resultSection.classList.remove('d-none');
        elements.previewEmpty.classList.add('d-none');
        
        elements.statRows.textContent = data.length.toLocaleString();
        elements.statCols.textContent = headers.length.toLocaleString();
        elements.statInSize.textContent = formatSize(inSize);
        elements.statOutSize.textContent = formatSize(outSize);
        elements.statTime.textContent = time.toFixed(2) + 'ms';

        renderPreview(data, headers);
    }

    function renderPreview(data, headers) {
        const previewData = data.slice(0, 100);
        elements.previewCount.textContent = `${data.length.toLocaleString()} rows total`;
        
        let html = '<thead><tr>';
        headers.forEach(h => html += `<th>${escapeHtml(h)}</th>`);
        html += '</tr></thead><tbody>';

        previewData.forEach(row => {
            html += '<tr>';
            headers.forEach(h => {
                let val = row[h];
                if (typeof val === 'object') val = JSON.stringify(val);
                html += `<td>${escapeHtml(String(val === undefined ? '' : val))}</td>`;
            });
            html += '</tr>';
        });
        html += '</tbody>';
        elements.previewTable.innerHTML = html;
    }

    function formatSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // --- Utilities ---

    function handleFileUpload(e, type) {
        const file = e.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = (event) => {
            if (type === 'json') elements.jsonIn.value = event.target.result;
            else elements.csvIn.value = event.target.result;
            showToast(`Loaded ${file.name}`, 'info');
        };
        reader.readAsText(file);
    }

    function setupDragAndDrop(zoneId, targetId, type) {
        const zone = document.getElementById(zoneId);
        const container = document.getElementById(targetId).parentElement;

        container.addEventListener('dragover', (e) => {
            e.preventDefault();
            zone.classList.remove('d-none');
        });

        zone.addEventListener('dragleave', (e) => {
            zone.classList.add('d-none');
        });

        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.add('d-none');
            const file = e.dataTransfer.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById(targetId).value = event.target.result;
                    showToast(`Dropped ${file.name}`, 'info');
                };
                reader.readAsText(file);
            }
        });
    }

    function loadExample(type) {
        if (type === 'json') {
            elements.jsonIn.value = JSON.stringify([
                { "id": 1, "name": "John Doe", "email": "john@example.com", "address": { "city": "New York", "zip": "10001" }, "tags": ["admin", "dev"] },
                { "id": 2, "name": "Jane Smith", "email": "jane@example.com", "address": { "city": "Los Angeles", "zip": "90001" }, "tags": ["editor"] },
                { "id": 3, "name": "Bob Wilson", "email": "bob@example.com", "address": { "city": "Chicago", "zip": "60601" } }
            ], null, 2);
        } else {
            elements.csvIn.value = "id,name,email,city,zip\n1,John Doe,john@example.com,New York,10001\n2,Jane Smith,jane@example.com,Los Angeles,90001\n3,Bob Wilson,bob@example.com,Chicago,60601";
        }
        showToast('Example data loaded', 'info');
    }

    function clear(type) {
        if (type === 'json') elements.jsonIn.value = '';
        else elements.csvIn.value = '';
        elements.resultSection.classList.add('d-none');
    }

    function copyOutput(type) {
        const el = (type === 'json') ? elements.jsonOut : elements.csvOut;
        if (!el.value) return;
        navigator.clipboard.writeText(el.value).then(() => {
            showToast('Output copied to clipboard', 'success');
        });
    }

    function download(type) {
        const el = (type === 'json') ? elements.jsonOut : elements.csvOut;
        if (!el.value) return;
        const blob = new Blob([el.value], { type: type === 'json' ? 'application/json' : 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `converted.${type}`;
        a.click();
        URL.revokeObjectURL(url);
    }

    return { init, loadExample, clear, copyOutput, download };
})();

document.addEventListener('DOMContentLoaded', function() {
    ConverterTool.init();
});
</script>
@endpush
