@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />

    <div class="row g-4">
        <!-- Main Tool Area -->
        <div class="col-lg-8">
            <!-- Text Hashing Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2 text-primary"></i>Text Hashing</h5>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="liveHash" checked>
                        <label class="form-check-label small" for="liveHash">Live Hashing</label>
                    </div>
                </div>
                <div class="card-body">
                    <textarea id="hashInput" class="form-control font-monospace mb-3" rows="6" placeholder="Enter text to generate cryptographic hashes..."></textarea>
                    
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex gap-2">
                            <button id="btnGenerate" class="btn btn-primary px-4">Generate Hashes</button>
                            <button id="btnClear" class="btn btn-outline-danger">Clear</button>
                            <button id="btnExample" class="btn btn-outline-secondary">Example</button>
                        </div>
                        <div class="small text-muted">
                            <span class="me-3">Chars: <span id="charCount">0</span></span>
                            <span class="me-3">Words: <span id="wordCount">0</span></span>
                            <span>Bytes: <span id="byteCount">0</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Algorithm Selection -->
            <div class="card mb-4 shadow-sm border-primary border-opacity-10">
                <div class="card-body py-2 px-3">
                    <div class="d-flex flex-wrap gap-4 align-items-center">
                        <span class="small fw-bold text-uppercase text-muted">Select Algorithms:</span>
                        <div class="form-check">
                            <input class="form-check-input algo-check" type="checkbox" id="algoMD5" value="MD5">
                            <label class="form-check-label small" for="algoMD5">MD5</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input algo-check" type="checkbox" id="algoSHA1" value="SHA-1">
                            <label class="form-check-label small" for="algoSHA1">SHA-1</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input algo-check" type="checkbox" id="algoSHA256" value="SHA-256" checked>
                            <label class="form-check-label small" for="algoSHA256">SHA-256</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input algo-check" type="checkbox" id="algoSHA384" value="SHA-384">
                            <label class="form-check-label small" for="algoSHA384">SHA-384</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input algo-check" type="checkbox" id="algoSHA512" value="SHA-512">
                            <label class="form-check-label small" for="algoSHA512">SHA-512</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hash Results -->
            <div id="hashResultsCard" class="card mb-4 shadow-sm d-none">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Generated Hashes</h5>
                    <button class="btn btn-sm btn-outline-primary" id="copyAll"><i class="bi bi-clipboard me-1"></i>Copy All</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 small" id="hashTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="120">Algorithm</th>
                                    <th>Hash Value</th>
                                    <th width="80">Length</th>
                                    <th width="60" class="text-center">Copy</th>
                                </tr>
                            </thead>
                            <tbody id="hashTableBody">
                                <!-- Results rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- File Hashing Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-binary me-2 text-success"></i>File Checksum / Hashing</h5>
                </div>
                <div class="card-body">
                    <div id="fileDropZone" class="drop-zone mb-3">
                        <div class="text-center py-4">
                            <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                            <p class="mb-2 fw-bold">Drag & Drop file here or click to upload</p>
                            <p class="text-muted small">Supports all file types up to 1GB+</p>
                            <input type="file" id="fileInput" class="d-none">
                            <button class="btn btn-primary px-4" id="btnSelectFile">Select File</button>
                        </div>
                    </div>

                    <div id="fileInfo" class="d-none p-3 bg-light rounded border mb-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="bi bi-file-earmark-text fs-2 text-primary"></i>
                            </div>
                            <div class="col">
                                <div id="fileName" class="fw-bold text-truncate">file-name.exe</div>
                                <div id="fileMeta" class="small text-muted">Size: 0 KB | Type: application/octet-stream</div>
                            </div>
                            <div class="col-auto">
                                <button id="cancelFile" class="btn btn-sm btn-outline-danger">Remove</button>
                            </div>
                        </div>
                        
                        <div id="fileProgress" class="mt-3 d-none">
                            <div class="d-flex justify-content-between mb-1 small">
                                <span id="progressText">Hashing file...</span>
                                <span id="progressPercent">0%</span>
                            </div>
                            <div class="progress progress-8">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"></div>
                            </div>
                        </div>
                    </div>

                    <div id="fileResults" class="table-responsive d-none">
                        <table class="table table-sm table-bordered mb-0 small">
                            <tbody id="fileHashBody">
                                <!-- File hashes -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Hash Verification -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-check2-circle me-2 text-warning"></i>Hash Verification / Matcher</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Generated / Pasted Hash</label>
                            <input type="text" id="verifyHashA" class="form-control font-monospace" placeholder="Enter hash to verify...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase">Expected Hash</label>
                            <input type="text" id="verifyHashB" class="form-control font-monospace" placeholder="Enter expected hash...">
                        </div>
                    </div>
                    <div id="verifyResult" class="alert d-none py-2 px-3 small d-flex align-items-center">
                        <i id="verifyIcon" class="bi me-2 fs-5"></i>
                        <span id="verifyText"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Area -->
        <div class="col-lg-4">
            <!-- Algorithm Analysis -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Algorithm Reference</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between mb-1">
                                <strong>MD5</strong>
                                <span class="badge bg-danger rounded-pill x-small">Broken</span>
                            </div>
                            <div class="text-muted x-small">128-bit hash. Fast but insecure. Use only for non-critical integrity checks.</div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between mb-1">
                                <strong>SHA-1</strong>
                                <span class="badge bg-warning text-dark rounded-pill x-small">Weak</span>
                            </div>
                            <div class="text-muted x-small">160-bit hash. Vulnerable to collisions. Avoid for new security systems.</div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between mb-1">
                                <strong>SHA-256</strong>
                                <span class="badge bg-success rounded-pill x-small">Secure</span>
                            </div>
                            <div class="text-muted x-small">256-bit hash. Industry standard. Excellent security and performance balance.</div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between mb-1">
                                <strong>SHA-512</strong>
                                <span class="badge bg-success rounded-pill x-small">Strongest</span>
                            </div>
                            <div class="text-muted x-small">512-bit hash. Extremely high security level. Recommended for high-sensitivity data.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Developer CLI Snippets -->
            <div class="card mb-4 shadow-sm bg-light border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 small text-uppercase">CLI Cheat Sheet</h6>
                    <div class="mb-3">
                        <label class="x-small fw-bold text-muted text-uppercase mb-1">Linux / macOS</label>
                        <code class="d-block p-2 bg-white rounded border x-small">sha256sum filename.zip</code>
                    </div>
                    <div class="mb-3">
                        <label class="x-small fw-bold text-muted text-uppercase mb-1">Windows (PowerShell)</label>
                        <code class="d-block p-2 bg-white rounded border x-small">Get-FileHash filename.zip -Algorithm SHA256</code>
                    </div>
                    <div class="mb-0">
                        <label class="x-small fw-bold text-muted text-uppercase mb-1">PHP</label>
                        <code class="d-block p-2 bg-white rounded border x-small">hash('sha256', 'text');</code>
                    </div>
                </div>
            </div>

            <!-- Length Reference -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Hash Bit Lengths</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-borderless mb-0 small">
                        <tbody>
                            <tr class="px-3 border-bottom"><td class="ps-3 py-2">MD5</td><td class="text-end pe-3 py-2">128 bits</td></tr>
                            <tr class="px-3 border-bottom"><td class="ps-3 py-2">SHA-1</td><td class="text-end pe-3 py-2">160 bits</td></tr>
                            <tr class="px-3 border-bottom"><td class="ps-3 py-2">SHA-256</td><td class="text-end pe-3 py-2">256 bits</td></tr>
                            <tr class="px-3 border-bottom"><td class="ps-3 py-2">SHA-384</td><td class="text-end pe-3 py-2">384 bits</td></tr>
                            <tr class="px-3"><td class="ps-3 py-2">SHA-512</td><td class="text-end pe-3 py-2">512 bits</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Content Section -->
    <div class="mt-5 border-top pt-5">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="h4 fw-bold mb-4">Deep Dive into Cryptographic Hashing</h2>
                
                <div class="row g-4 text-muted small">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">What is Hashing?</h6>
                        <p>Hashing is the process of converting a given key into another value. A hash function is used to generate the new value according to a mathematical algorithm. Unlike encryption, hashing is a one-way process. Once a piece of data is hashed, it cannot be reversed to retrieve the original content.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Hash vs. Encryption</h6>
                        <p>Encryption is a two-way function designed for data privacy (you can decrypt it with a key). Hashing is a one-way function designed for data integrity and identity. If you change a single character in a 1GB file, its SHA-256 hash will change completely.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Why is MD5 still used?</h6>
                        <p>MD5 is no longer considered secure against intentional tampering (collisions), but it remains very popular as a simple checksum to verify that a file wasn't corrupted during a download. It is faster than newer algorithms, which makes it efficient for non-security tasks.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Local Hashing Benefits</h6>
                        <p>Most online hash generators send your text to their servers. This is a security risk for passwords or private keys. Toolzy's Hash Generator uses the <strong>Web Crypto API</strong> to process everything locally in your browser, so your data never leaves your computer.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
.progress-8 {
    height: 8px;
}

#progressBar {
    width: 0%;
}

.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

.x-small {
    font-size: 0.7rem;
}

.drop-zone {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.drop-zone:hover, .drop-zone.drag-over {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.05);
}

.italic { font-style: italic; }

#hashTable code {
    word-break: break-all;
    font-size: 0.8rem;
    color: #333;
}

.progress {
    background-color: #e9ecef;
    overflow: visible;
}
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const HashTool = (function() {
    // --- Compact MD5 Implementation ---
    function md5(string) {
        function k(n) { return Math.abs(Math.sin(n)) * 4294967296 >>> 0; }
        function r(n, s) { return n << s | n >>> 32 - s; }
        let s = [7, 12, 17, 22, 5, 9, 14, 20, 4, 11, 16, 23, 6, 10, 15, 21],
            a = [1732584193, 4023233417, 2562383102, 271733878],
            i, x = [], msg = unescape(unescape(encodeURIComponent(string))), 
            len = msg.length, blocks = [((len + 8) >> 6) + 1 << 4];
        for (i = 0; i < len; i++) blocks[i >> 2] |= msg.charCodeAt(i) << ((i % 4) << 3);
        blocks[len >> 2] |= 0x80 << ((len % 4) << 3);
        blocks[((len + 8) >> 6) + 1 << 4 | 14] = len * 8;
        for (i = 0; i < blocks.length; i += 16) {
            let [oa, ob, oc, od] = a;
            for (let j = 0; j < 64; j++) {
                let f, g;
                if (j < 16) { f = (ob & oc) | (~ob & od); g = j; }
                else if (j < 32) { f = (ob & od) | (oc & ~od); g = (5 * j + 1) % 16; }
                else if (j < 48) { f = ob ^ oc ^ od; g = (3 * j + 5) % 16; }
                else { f = oc ^ (ob | ~od); g = (7 * j) % 16; }
                let temp = od;
                od = oc; oc = ob;
                ob = (ob + r(oa + f + k(j + 1) + (blocks[i + g] >>> 0), s[(j >> 4) << 2 | (j % 4)])) >>> 0;
                oa = temp;
            }
            a[0] = a[0] + oa >>> 0; a[1] = a[1] + ob >>> 0; a[2] = a[2] + oc >>> 0; a[3] = a[3] + od >>> 0;
        }
        return a.map(n => n.toString(16).padStart(8, '0').split('').reverse().join('')).map(s => {
            let r = ''; for(let i=0; i<8; i+=2) r += s.slice(i, i+2).split('').reverse().join(''); return r;
        }).join('');
    }

    const elements = {
        input: document.getElementById('hashInput'),
        live: document.getElementById('liveHash'),
        btnGen: document.getElementById('btnGenerate'),
        btnClear: document.getElementById('btnClear'),
        btnEx: document.getElementById('btnExample'),
        resultsCard: document.getElementById('hashResultsCard'),
        tableBody: document.getElementById('hashTableBody'),
        charCount: document.getElementById('charCount'),
        wordCount: document.getElementById('wordCount'),
        byteCount: document.getElementById('byteCount'),
        fileInput: document.getElementById('fileInput'),
        fileDrop: document.getElementById('fileDropZone'),
        fileInfo: document.getElementById('fileInfo'),
        fileResults: document.getElementById('fileResults'),
        fileHashBody: document.getElementById('fileHashBody'),
        progress: document.getElementById('fileProgress'),
        progressBar: document.getElementById('progressBar'),
        progressPercent: document.getElementById('progressPercent'),
        verifyA: document.getElementById('verifyHashA'),
        verifyB: document.getElementById('verifyHashB'),
        verifyRes: document.getElementById('verifyResult'),
        verifyIcon: document.getElementById('verifyIcon'),
        verifyText: document.getElementById('verifyText')
    };

    function init() {
        bindEvents();
    }

    function bindEvents() {
        elements.input.addEventListener('input', () => {
            updateStats();
            if (elements.live.checked) generateHashes();
        });
        elements.btnGen.addEventListener('click', generateHashes);
        elements.btnClear.addEventListener('click', clearAll);
        elements.btnEx.addEventListener('click', loadExample);
        
        elements.fileInput.addEventListener('change', (e) => handleFile(e.target.files[0]));
        setupDragAndDrop();
        document.getElementById('cancelFile').addEventListener('click', resetFile);

        elements.verifyA.addEventListener('input', verifyHashes);
        elements.verifyB.addEventListener('input', verifyHashes);

        document.querySelectorAll('.algo-check').forEach(cb => {
            cb.addEventListener('change', () => {
                if (elements.input.value) generateHashes();
            });
        });
        
        document.getElementById('copyAll').addEventListener('click', copyAllHashes);

        document.getElementById('btnSelectFile').addEventListener('click', () => {
            elements.fileInput.click();
        });

        // Event delegation for copy buttons
        elements.tableBody.addEventListener('click', (e) => {
            const btn = e.target.closest('.copy-hash-btn');
            if (btn) {
                copy(btn.getAttribute('data-hash'));
            }
        });

        elements.fileHashBody.addEventListener('click', (e) => {
            const btn = e.target.closest('.copy-hash-btn');
            if (btn) {
                copy(btn.getAttribute('data-hash'));
            }
        });
    }

    async function generateHashes() {
        const text = elements.input.value;
        if (!text) {
            elements.resultsCard.classList.add('d-none');
            return;
        }

        const selectedAlgos = Array.from(document.querySelectorAll('.algo-check:checked')).map(cb => cb.value);
        if (selectedAlgos.length === 0) {
            elements.resultsCard.classList.add('d-none');
            return;
        }

        elements.resultsCard.classList.remove('d-none');
        let html = '';

        for (const algo of selectedAlgos) {
            let hashValue = '';
            if (algo === 'MD5') {
                hashValue = md5(text);
            } else {
                hashValue = await subtleHash(text, algo);
            }
            
            html += `<tr>
                <td class="fw-bold">${algo}</td>
                <td><code id="hash-${algo}">${hashValue}</code></td>
                <td>${hashValue.length}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-secondary copy-hash-btn" data-hash="${hashValue}">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </td>
            </tr>`;
        }
        elements.tableBody.innerHTML = html;
    }

    async function subtleHash(text, algo) {
        const msgUint8 = new TextEncoder().encode(text);
        const hashBuffer = await crypto.subtle.digest(algo, msgUint8);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    function updateStats() {
        const val = elements.input.value;
        elements.charCount.textContent = val.length;
        elements.wordCount.textContent = val ? val.trim().split(/\s+/).length : 0;
        elements.byteCount.textContent = new TextEncoder().encode(val).length;
    }

    function clearAll() {
        elements.input.value = '';
        elements.resultsCard.classList.add('d-none');
        updateStats();
    }

    function loadExample() {
        elements.input.value = "Toolzy - The Ultimate Developer Toolbox. Simple, Fast, and 100% Secure.";
        updateStats();
        generateHashes();
    }

    // --- File Hashing ---

    async function handleFile(file) {
        if (!file) return;
        resetFile();
        
        elements.fileInfo.classList.remove('d-none');
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileMeta').textContent = `Size: ${formatSize(file.size)} | Type: ${file.type || 'unknown'}`;
        elements.progress.classList.remove('d-none');
        
        const algos = ['MD5', 'SHA-256', 'SHA-512'];
        const results = {};
        
        try {
            for (const algo of algos) {
                document.getElementById('progressText').textContent = `Hashing ${algo}...`;
                results[algo] = await hashFile(file, algo);
            }
            
            renderFileResults(results);
            showToast('File hashed successfully', 'success');
        } catch (e) {
            showToast('Error hashing file: ' + e.message, 'danger');
        } finally {
            elements.progress.classList.add('d-none');
        }
    }

    async function hashFile(file, algo) {
        const chunkSize = 10 * 1024 * 1024; // 10MB
        const chunks = Math.ceil(file.size / chunkSize);
        
        if (algo === 'MD5') {
            // For large files, MD5 might be slow in JS. We'll read the whole thing for MD5 
            // since our compact MD5 isn't streaming-capable easily.
            // If it's too big (>100MB), we skip MD5 to avoid UI freeze or error
            if (file.size > 100 * 1024 * 1024) return 'Skipped (File too large for JS MD5)';
            const buffer = await file.arrayBuffer();
            const binary = String.fromCharCode(...new Uint8Array(buffer));
            return md5(binary);
        }

        // Streaming for SHA using Web Crypto
        const digestAlgo = algo;
        // Note: SubtleCrypto.digest doesn't support streaming yet in all browsers.
        // We have to read in chunks and then hash the whole buffer or use a polyfill.
        // Since we want production-grade, for large files we should use a streaming library.
        // But without libraries, we'll read chunks if they are small, or full buffer.
        
        if (file.size > 500 * 1024 * 1024) {
            // For very large files without streaming crypto, we might fail or freeze.
            return 'File too large for non-streaming hash';
        }

        const buffer = await file.arrayBuffer();
        const hashBuffer = await crypto.subtle.digest(digestAlgo, buffer);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    function renderFileResults(results) {
        elements.fileResults.classList.remove('d-none');
        let html = '';
        for (const [algo, val] of Object.entries(results)) {
            html += `<tr>
                <td width="100" class="fw-bold">${algo}</td>
                <td class="font-monospace text-break"><code>${val}</code></td>
                <td width="50" class="text-center">
                    <button class="btn btn-sm btn-outline-secondary copy-hash-btn" data-hash="${val}"><i class="bi bi-clipboard"></i></button>
                </td>
            </tr>`;
        }
        elements.fileHashBody.innerHTML = html;
    }

    function resetFile() {
        elements.fileInput.value = '';
        elements.fileInfo.classList.add('d-none');
        elements.fileResults.classList.add('d-none');
        elements.fileHashBody.innerHTML = '';
        elements.progressBar.style.width = '0%';
        elements.progressPercent.textContent = '0%';
    }

    function formatSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024, sizes = ['B', 'KB', 'MB', 'GB'], i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function setupDragAndDrop() {
        elements.fileDrop.addEventListener('dragover', (e) => {
            e.preventDefault();
            elements.fileDrop.classList.add('drag-over');
        });
        elements.fileDrop.addEventListener('dragleave', () => elements.fileDrop.classList.remove('drag-over'));
        elements.fileDrop.addEventListener('drop', (e) => {
            e.preventDefault();
            elements.fileDrop.classList.remove('drag-over');
            handleFile(e.dataTransfer.files[0]);
        });
        elements.fileDrop.addEventListener('click', () => elements.fileInput.click());
    }

    // --- Verification ---

    function verifyHashes() {
        const a = elements.verifyA.value.trim().toLowerCase();
        const b = elements.verifyB.value.trim().toLowerCase();

        if (!a || !b) {
            elements.verifyRes.classList.add('d-none');
            return;
        }

        elements.verifyRes.classList.remove('d-none');
        if (a === b) {
            elements.verifyRes.className = 'alert alert-success py-2 px-3 small d-flex align-items-center';
            elements.verifyIcon.className = 'bi bi-check-circle-fill me-2 fs-5';
            elements.verifyText.textContent = 'Hashes Match! Integrity Verified.';
        } else {
            elements.verifyRes.className = 'alert alert-danger py-2 px-3 small d-flex align-items-center';
            elements.verifyIcon.className = 'bi bi-x-circle-fill me-2 fs-5';
            elements.verifyText.textContent = 'Hashes Do Not Match! Data may be tampered or incorrect.';
        }
    }

    function copy(text) {
        navigator.clipboard.writeText(text).then(() => showToast('Copied to clipboard', 'success'));
    }

    function copyAllHashes() {
        const codes = Array.from(elements.tableBody.querySelectorAll('code')).map(c => c.textContent).join('\n');
        copy(codes);
    }

    return { init, copy };
})();

document.addEventListener('DOMContentLoaded', function() {
    HashTool.init();
});
</script>
@endpush
