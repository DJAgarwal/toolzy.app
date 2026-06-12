@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- Trust Indicator --}}
    <x-ui-trust-indicator />
    </div>

    {{-- Main Tool Interface --}}
    <div class="row g-4">
        {{-- Input & Controls Column --}}
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-code-slash me-2"></i>JavaScript Input
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" id="loadExample">
                            <i class="bi bi-lightbulb me-1"></i>Example
                        </button>
                        <button class="btn btn-sm btn-outline-danger" id="clearInput">
                            <i class="bi bi-trash me-1"></i>Clear
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 position-relative">
                    <textarea id="jsInput" class="form-control border-0 font-monospace p-3" 
                        placeholder="function hello(name){console.log('Hello '+name);}"></textarea>
                    
                    <div class="input-actions-overlay position-absolute bottom-0 end-0 p-3 d-flex gap-2">
                        <label class="btn btn-sm btn-light border shadow-sm mb-0" title="Upload File">
                            <i class="bi bi-upload"></i>
                            <input type="file" id="fileInput" class="d-none" accept=".js,.mjs,.cjs,.txt">
                        </label>
                        <button class="btn btn-sm btn-light border shadow-sm" id="copyInput" title="Copy Input">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
                <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between text-muted small">
                    <div>
                        <span id="charCount">0</span> characters | 
                        <span id="lineCount">0</span> lines | 
                        <span id="fileSize">0 KB</span>
                    </div>
                    <div id="statusIndicator" class="text-success d-none">
                        <i class="bi bi-check-circle-fill me-1"></i>Processed
                    </div>
                </div>
            </div>

            {{-- Controls Accordion --}}
            <div class="accordion mb-4" id="controlsAccordion">
                <div class="accordion-item border-0 shadow-sm overflow-hidden mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#formattingOptions">
                            <i class="bi bi-sliders me-2"></i>Formatting & Minification Options
                        </button>
                    </h2>
                    <div id="formattingOptions" class="accordion-collapse collapse show" data-bs-parent="#controlsAccordion">
                        <div class="accordion-body bg-white">
                            <div class="row g-3">
                                {{-- Indentation --}}
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Indentation</label>
                                    <select class="form-select form-select-sm" id="optIndent">
                                        <option value="2">2 Spaces</option>
                                        <option value="4" selected>4 Spaces</option>
                                        <option value="8">8 Spaces</option>
                                        <option value="tab">Tabs</option>
                                    </select>
                                </div>
                                {{-- Quote Style --}}
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Quote Style</label>
                                    <select class="form-select form-select-sm" id="optQuotes">
                                        <option value="preserve" selected>Preserve</option>
                                        <option value="single">Single Quotes</option>
                                        <option value="double">Double Quotes</option>
                                    </select>
                                </div>
                                {{-- Line Width --}}
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Max Line Width</label>
                                    <input type="number" class="form-select form-select-sm" id="optWidth" value="120" min="40" max="500">
                                </div>
                                {{-- Semicolons --}}
                                <div class="col-md-4">
                                    <div class="form-check form-switch small">
                                        <input class="form-check-input" type="checkbox" id="optSemicolons" checked>
                                        <label class="form-check-label fw-bold" for="optSemicolons">Ensure Semicolons</label>
                                    </div>
                                </div>
                                {{-- Trailing Commas --}}
                                <div class="col-md-4">
                                    <div class="form-check form-switch small">
                                        <input class="form-check-input" type="checkbox" id="optTrailingCommas">
                                        <label class="form-check-label fw-bold" for="optTrailingCommas">Add Trailing Commas</label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <button class="btn btn-primary px-4" id="actionBeautify">
                                    <i class="bi bi-magic me-2"></i>Beautify JavaScript
                                </button>
                                <button class="btn btn-dark px-4" id="actionMinify">
                                    <i class="bi bi-lightning-fill me-2"></i>Minify JavaScript
                                </button>
                                <button class="btn btn-outline-info px-4" id="actionAnalyze">
                                    <i class="bi bi-graph-up me-2 text-info"></i>Analyze Code
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle px-4" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-file-earmark-arrow-down me-2"></i>Export Report
                                    </button>
                                    <ul class="dropdown-menu shadow">
                                        <li><a class="dropdown-item" href="#" id="exportJSON"><i class="bi bi-filetype-json me-2"></i>Download JSON Report</a></li>
                                        <li><a class="dropdown-item" href="#" id="exportHTML"><i class="bi bi-filetype-html me-2"></i>Download HTML Report</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Output Section --}}
            <div id="outputContainer" class="card shadow-sm border-0 mb-4 d-none">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-success">
                        <i class="bi bi-check2-all me-2"></i>Processed Result
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-success" id="copyOutput">
                            <i class="bi bi-clipboard me-1"></i>Copy
                        </button>
                        <button class="btn btn-sm btn-outline-success" id="downloadOutput">
                            <i class="bi bi-download me-1"></i>Download
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <pre id="outputPre" class="m-0 p-3 bg-light language-javascript"><code id="outputCode"></code></pre>
                </div>
                <div id="minifyStats" class="card-footer bg-light py-2 px-3 text-muted small d-none">
                    <div class="row text-center">
                        <div class="col-3 border-end">Original: <strong id="statOriginal">-</strong></div>
                        <div class="col-3 border-end">Minified: <strong id="statMinified">-</strong></div>
                        <div class="col-3 border-end">Saved: <strong id="statSaved">-</strong></div>
                        <div class="col-3 text-success">Reduction: <strong id="statReduction">-</strong></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar / Analysis Column --}}
        <div class="col-xl-4">
            {{-- Syntax Validation Card --}}
            <div class="card shadow-sm border-0 mb-4 h-auto">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bug me-2 text-warning"></i>Syntax Validation
                    </h5>
                </div>
                <div class="card-body py-3" id="validationCardBody">
                    <div id="validationEmpty" class="text-center py-3 text-muted">
                        <i class="bi bi-search fs-1 d-block mb-2"></i>
                        <p class="mb-0 small">Enter code to see validation results</p>
                    </div>
                    <div id="validationSuccess" class="alert alert-success d-none mb-0 py-2">
                        <i class="bi bi-check-circle-fill me-2"></i>No syntax issues detected.
                    </div>
                    <div id="validationError" class="alert alert-danger d-none mb-0 py-2">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <span id="validationMsg"></span>
                    </div>
                </div>
            </div>

            {{-- Quick Analysis Card --}}
            <div class="card shadow-sm border-0 mb-4 h-auto">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-activity me-2 text-info"></i>Code Analysis
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small" id="analysisList">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Functions <span class="badge bg-primary rounded-pill" id="statFunctions">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Classes <span class="badge bg-secondary rounded-pill" id="statClasses">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Variables <span class="badge bg-info rounded-pill" id="statVars">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Async Functions <span class="badge bg-warning text-dark rounded-pill" id="statAsync">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ES6+ Features <span class="badge bg-dark rounded-pill" id="statES6">0</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Complexity Card --}}
            <div class="card shadow-sm border-0 mb-4 h-auto">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart me-2 text-purple"></i>Complexity
                    </h5>
                </div>
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-bold">Complexity Level</span>
                        <span id="statComplexityLabel" class="badge bg-success">Simple</span>
                    </div>
                    <div class="progress complexity-progress">
                        <div id="complexityProgress" class="progress-bar bg-success" role="progressbar"></div>
                    </div>
                    <div class="mt-3 text-muted small" id="complexityDescription">
                        Code structure is straightforward and easy to maintain.
                    </div>
                </div>
            </div>

            {{-- Optimization Suggestions --}}
            <div id="optimizationCard" class="card shadow-sm border-0 mb-4 h-auto d-none">
                <div class="card-header bg-white py-3 text-primary fw-bold">
                    <i class="bi bi-lightning me-2"></i>Optimization Tips
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush small" id="optimizationList"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Styles --}}
<style nonce="{{ $cspNonce }}">
    .font-monospace { font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace !important; }
    #jsInput { height: 400px !important; resize: vertical; border-radius: 0; }
    #jsInput:focus { box-shadow: none; border-color: transparent; }
    .text-purple { color: #6f42c1; }
    .bg-purple { background-color: #6f42c1; }
    pre { border-radius: 8px; font-size: 0.9rem; }
    .accordion-button:not(.collapsed) { background-color: #f8f9fa; color: #0d6efd; }
    .list-group-item { border-left: 0; border-right: 0; }
    .list-group-item:first-child { border-top: 0; }
    .list-group-item:last-child { border-bottom: 0; }
    #outputPre { max-height: 550px !important; overflow: auto; }
    .complexity-progress { height: 10px; }
    #complexityProgress { width: 20%; }
</style>

@push('scripts')
{{-- External Libraries --}}
<script nonce="{{ $cspNonce }}" src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify.min.js"></script>
<script nonce="{{ $cspNonce }}" src="https://cdnjs.cloudflare.com/ajax/libs/acorn/8.10.0/acorn.min.js"></script>
<script nonce="{{ $cspNonce }}" src="https://cdnjs.cloudflare.com/ajax/libs/acorn-walk/8.2.0/walk.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet" />
<script nonce="{{ $cspNonce }}" src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script nonce="{{ $cspNonce }}" src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>

<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const jsInput = document.getElementById('jsInput');
    const charCount = document.getElementById('charCount');
    const lineCount = document.getElementById('lineCount');
    const fileSize = document.getElementById('fileSize');
    const outputContainer = document.getElementById('outputContainer');
    const outputCode = document.getElementById('outputCode');
    const outputPre = document.getElementById('outputPre');
    const minifyStats = document.getElementById('minifyStats');

    // Action Buttons
    const btnBeautify = document.getElementById('actionBeautify');
    const btnMinify = document.getElementById('actionMinify');
    const btnAnalyze = document.getElementById('actionAnalyze');
    const btnClear = document.getElementById('clearInput');
    const btnExample = document.getElementById('loadExample');
    const btnCopyInput = document.getElementById('copyInput');
    const btnCopyOutput = document.getElementById('copyOutput');
    const btnDownload = document.getElementById('downloadOutput');
    const fileInput = document.getElementById('fileInput');

    // Analysis Elements
    const validationEmpty = document.getElementById('validationEmpty');
    const validationSuccess = document.getElementById('validationSuccess');
    const validationError = document.getElementById('validationError');
    const validationMsg = document.getElementById('validationMsg');
    const statFunctions = document.getElementById('statFunctions');
    const statClasses = document.getElementById('statClasses');
    const statVars = document.getElementById('statVars');
    const statAsync = document.getElementById('statAsync');
    const statES6 = document.getElementById('statES6');
    const statComplexityLabel = document.getElementById('statComplexityLabel');
    const complexityProgress = document.getElementById('complexityProgress');
    const complexityDescription = document.getElementById('complexityDescription');
    const optimizationCard = document.getElementById('optimizationCard');
    const optimizationList = document.getElementById('optimizationList');

    // Export Buttons
    const btnExportJSON = document.getElementById('exportJSON');
    const btnExportHTML = document.getElementById('exportHTML');

    let currentAnalysis = null;

    // Options
    const optIndent = document.getElementById('optIndent');
    const optQuotes = document.getElementById('optQuotes');
    const optWidth = document.getElementById('optWidth');
    const optSemicolons = document.getElementById('optSemicolons');
    const optTrailingCommas = document.getElementById('optTrailingCommas');

    function showNotify(msg, type = 'info') {
        if (typeof showToast === 'function') {
            showToast(msg, type);
        } else {
            alert(msg);
        }
    }

    // Input Event Listeners
    jsInput.addEventListener('input', updateStats);
    
    function updateStats() {
        const text = jsInput.value;
        charCount.textContent = text.length;
        lineCount.textContent = text.split('\n').length;
        fileSize.textContent = (text.length / 1024).toFixed(2) + ' KB';
        
        // Auto-validate on input
        validateSyntax(text);
    }

    function validateSyntax(code) {
        if (!code.trim()) {
            validationEmpty.classList.remove('d-none');
            validationSuccess.classList.add('d-none');
            validationError.classList.add('d-none');
            return;
        }

        try {
            acorn.parse(code, { ecmaVersion: 'latest', sourceType: 'module' });
            validationEmpty.classList.add('d-none');
            validationSuccess.classList.remove('d-none');
            validationError.classList.add('d-none');
        } catch (e) {
            validationEmpty.classList.add('d-none');
            validationSuccess.classList.add('d-none');
            validationError.classList.remove('d-none');
            validationMsg.textContent = e.message;
        }
    }

    // Load Example
    btnExample.addEventListener('click', () => {
        jsInput.value = `/**
 * Simple User Service Class
 * Demonstrates various ES6+ features
 */
import { storage } from './utils.js';

class UserService {
    constructor(apiEndpoint) {
        this.api = apiEndpoint;
        this.users = [];
    }

    async fetchUsers(limit = 10) {
        try {
            const response = await fetch(\`\${this.api}/users?limit=\${limit}\`);
            const data = await response.json();
            this.users = data?.results ?? [];
            return this.users;
        } catch (error) {
            console.error("Failed to fetch users", error);
            return [];
        }
    }

    findUserById(id) {
        return this.users.find(u => u.id === id);
    }

    get activeCount() {
        return this.users.filter(u => u.isActive).length;
    }
}

const service = new UserService('https://api.example.com');
service.fetchUsers().then(users => {
    console.log("Total Users:", users.length);
});`;
        updateStats();
        showNotify('Example code loaded', 'success');
    });

    // Clear Input
    btnClear.addEventListener('click', () => {
        jsInput.value = '';
        updateStats();
        outputContainer.classList.add('d-none');
        optimizationCard.classList.add('d-none');
        currentAnalysis = null;
    });

    // Copy Input
    btnCopyInput.addEventListener('click', () => {
        if (!jsInput.value) return;
        navigator.clipboard.writeText(jsInput.value);
        showNotify('Input copied to clipboard', 'success');
    });

    // Copy Output
    btnCopyOutput.addEventListener('click', () => {
        navigator.clipboard.writeText(outputCode.textContent);
        showNotify('Output copied to clipboard', 'success');
    });

    // Download Output
    btnDownload.addEventListener('click', () => {
        const content = outputCode.textContent;
        const blob = new Blob([content], { type: 'text/javascript' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        const isMinified = !minifyStats.classList.contains('d-none');
        a.href = url;
        a.download = isMinified ? 'minified.js' : 'formatted.js';
        a.click();
        URL.revokeObjectURL(url);
    });

    // File Upload
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = (event) => {
            jsInput.value = event.target.result;
            updateStats();
            showNotify('File loaded successfully', 'success');
        };
        reader.readAsText(file);
    });

    // Beautify Action
    btnBeautify.addEventListener('click', () => {
        const code = jsInput.value;
        if (!code.trim()) return;

        const indentSize = optIndent.value === 'tab' ? 1 : parseInt(optIndent.value);
        const indentChar = optIndent.value === 'tab' ? '\t' : ' ';

        const options = {
            indent_size: indentSize,
            indent_char: indentChar,
            max_preserve_newlines: 2,
            preserve_newlines: true,
            keep_array_indentation: false,
            break_chained_methods: false,
            indent_scripts: 'normal',
            brace_style: 'collapse',
            space_before_conditional: true,
            unescape_strings: false,
            jslint_happy: false,
            end_with_newline: true,
            wrap_line_length: parseInt(optWidth.value),
            indent_inner_html: false,
            comma_first: false,
            e4x: false,
            indent_empty_lines: false
        };

        let result = js_beautify(code, options);

        // Simple Quote Management (Post-processing)
        if (optQuotes.value === 'single') {
            result = result.replace(/"([^"\\]*(\\.[^"\\]*)*)"/g, "'$1'");
        } else if (optQuotes.value === 'double') {
            result = result.replace(/'([^'\\]*(\\.[^'\\]*)*)'/g, '"$1"');
        }

        displayResult(result, 'beautify');
        currentAnalysis = analyzeCode(code);
    });

    // Minify Action (Basic Browser-side Minification)
    btnMinify.addEventListener('click', () => {
        const code = jsInput.value;
        if (!code.trim()) return;

        // Note: Real minification like Terser is heavy for browser. 
        // We use a robust regex-based minifier that preserves functionality but strips all noise.
        let minified = code
            .replace(/\/\*[\s\S]*?\*\/|([^\\:]|^)\/\/.*$/gm, '$1') // Remove comments
            .replace(/\s+([!#$%&()*+,\-./:;<=>?@[\]^_{|}~])\s+/g, '$1') // Remove spaces around operators
            .replace(/\s+([!#$%&()*+,\-./:;<=>?@[\]^_{|}~])/g, '$1')
            .replace(/([!#$%&()*+,\-./:;<=>?@[\]^_{|}~])\s+/g, '$1')
            .replace(/\s+/g, ' ') // Collapse multiple spaces
            .trim();

        const originalSize = code.length;
        const minifiedSize = minified.length;
        const saved = originalSize - minifiedSize;
        const reduction = ((saved / originalSize) * 100).toFixed(1);

        displayResult(minified, 'minify', { originalSize, minifiedSize, saved, reduction });
        currentAnalysis = analyzeCode(code);
    });

    // Analyze Action
    btnAnalyze.addEventListener('click', () => {
        if (!jsInput.value.trim()) return;
        currentAnalysis = analyzeCode(jsInput.value);
        showNotify('Analysis complete', 'info');
    });

    // Export JSON
    btnExportJSON.addEventListener('click', (e) => {
        e.preventDefault();
        if (!currentAnalysis) {
            currentAnalysis = analyzeCode(jsInput.value);
        }
        if (!currentAnalysis) return;

        const blob = new Blob([JSON.stringify(currentAnalysis, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'js-analysis.json';
        a.click();
        URL.revokeObjectURL(url);
    });

    // Export HTML
    btnExportHTML.addEventListener('click', (e) => {
        e.preventDefault();
        if (!currentAnalysis) {
            currentAnalysis = analyzeCode(jsInput.value);
        }
        if (!currentAnalysis) return;

        const htmlReport = `
<!DOCTYPE html>
<html>
<head>
    <title>JavaScript Analysis Report - Toolzy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { padding: 40px; background: #f8f9fa; }</style>
</head>
<body>
    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h2 class="mb-0 h4">JavaScript Analysis Report</h2>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h4 class="h5 fw-bold border-bottom pb-2">Code Statistics</h4>
                        <table class="table table-sm">
                            <tr><th class="text-muted">Functions</th><td class="text-end fw-bold">${currentAnalysis.stats.functions}</td></tr>
                            <tr><th class="text-muted">Classes</th><td class="text-end fw-bold">${currentAnalysis.stats.classes}</td></tr>
                            <tr><th class="text-muted">Variables</th><td class="text-end fw-bold">${currentAnalysis.stats.vars}</td></tr>
                            <tr><th class="text-muted">Async Functions</th><td class="text-end fw-bold">${currentAnalysis.stats.asyncs}</td></tr>
                            <tr><th class="text-muted">ES6+ Usage</th><td class="text-end fw-bold">${currentAnalysis.stats.es6}</td></tr>
                            <tr><th class="text-muted">Complexity Score</th><td class="text-end fw-bold">${currentAnalysis.stats.complexity}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4 class="h5 fw-bold border-bottom pb-2">Optimization Tips</h4>
                        ${currentAnalysis.suggestions.length > 0 ? `
                        <ul class="list-group list-group-flush">
                            ${currentAnalysis.suggestions.map(s => `<li class="list-group-item px-0 py-2 border-0 small"><i class="bi bi-info-circle text-primary me-2"></i>${s}</li>`).join('')}
                        </ul>
                        ` : '<p class="text-muted small">No specific optimization suggestions identified.</p>'}
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light text-muted small text-center py-3">
                Generated by Toolzy JavaScript Formatter & Analyzer
            </div>
        </div>
    </div>
</body>
</html>`;
        
        const blob = new Blob([htmlReport], { type: 'text/html' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'js-report.html';
        a.click();
        URL.revokeObjectURL(url);
    });

    function displayResult(result, mode, stats = null) {
        outputContainer.classList.remove('d-none');
        outputCode.textContent = result;
        
        // Re-highlight
        Prism.highlightElement(outputCode);
        
        if (mode === 'minify' && stats) {
            minifyStats.classList.remove('d-none');
            document.getElementById('statOriginal').textContent = (stats.originalSize / 1024).toFixed(2) + ' KB';
            document.getElementById('statMinified').textContent = (stats.minifiedSize / 1024).toFixed(2) + ' KB';
            document.getElementById('statSaved').textContent = (stats.saved / 1024).toFixed(2) + ' KB';
            document.getElementById('statReduction').textContent = stats.reduction + '%';
        } else {
            minifyStats.classList.add('d-none');
        }

        // Scroll to output
        outputContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function analyzeCode(code) {
        if (!code.trim()) return null;

        const stats = {
            functions: 0,
            classes: 0,
            vars: 0,
            asyncs: 0,
            es6: 0,
            depth: 0,
            complexity: 0
        };

        const suggestions = [];

        try {
            const ast = acorn.parse(code, { ecmaVersion: 'latest', sourceType: 'module', locations: true });
            
            const walker = window.walk || acorn.walk;
            if (!walker) {
                console.error("Acorn Walker not found");
                return null;
            }

            walker.simple(ast, {
                FunctionDeclaration(node) {
                    stats.functions++;
                    if (node.async) stats.asyncs++;
                    checkFunctionSize(node, suggestions);
                },
                FunctionExpression(node) {
                    stats.functions++;
                    if (node.async) stats.asyncs++;
                },
                ArrowFunctionExpression(node) {
                    stats.functions++;
                    stats.es6++;
                    if (node.async) stats.asyncs++;
                },
                ClassDeclaration(node) {
                    stats.classes++;
                    stats.es6++;
                },
                VariableDeclaration(node) {
                    stats.vars += node.declarations.length;
                    if (node.kind === 'let' || node.kind === 'const') stats.es6++;
                },
                TemplateLiteral() { stats.es6++; },
                ChainExpression() { stats.es6++; }, // Optional chaining
                LogicalExpression(node) {
                    if (node.operator === '??') stats.es6++;
                    stats.complexity++;
                },
                IfStatement() { stats.complexity++; },
                WhileStatement() { stats.complexity++; },
                ForStatement() { stats.complexity++; },
                ForInStatement() { stats.complexity++; },
                ForOfStatement() { stats.complexity++; stats.es6++; },
                SwitchCase() { stats.complexity++; }
            });

            // Update UI
            statFunctions.textContent = stats.functions;
            statClasses.textContent = stats.classes;
            statVars.textContent = stats.vars;
            statAsync.textContent = stats.asyncs;
            statES6.textContent = stats.es6;

            // Complexity Logic
            const score = Math.min(100, (stats.complexity * 5) + (stats.functions * 2));
            complexityProgress.style.width = score + '%';
            
            if (score < 30) {
                statComplexityLabel.textContent = 'Simple';
                statComplexityLabel.className = 'badge bg-success';
                complexityProgress.className = 'progress-bar bg-success';
                complexityDescription.textContent = 'Code structure is straightforward and easy to maintain.';
            } else if (score < 60) {
                statComplexityLabel.textContent = 'Moderate';
                statComplexityLabel.className = 'badge bg-info text-dark';
                complexityProgress.className = 'progress-bar bg-info';
                complexityDescription.textContent = 'Code has some logical branches. Still manageable.';
            } else if (score < 85) {
                statComplexityLabel.textContent = 'Complex';
                statComplexityLabel.className = 'badge bg-warning text-dark';
                complexityProgress.className = 'progress-bar bg-warning';
                complexityDescription.textContent = 'Code is getting complex. Consider refactoring large blocks.';
                suggestions.push('High cyclomatic complexity detected. Try breaking down logic into smaller functions.');
            } else {
                statComplexityLabel.textContent = 'Very Complex';
                statComplexityLabel.className = 'badge bg-danger';
                complexityProgress.className = 'progress-bar bg-danger';
                complexityDescription.textContent = 'High maintenance risk. Deep nesting and many logical paths.';
                suggestions.push('Critical: Code is extremely complex. Immediate refactoring recommended.');
            }

            // Optimization Suggestions
            renderSuggestions(suggestions, stats);

            return { stats, suggestions };

        } catch (e) {
            console.error("Analysis failed", e);
            return null;
        }
    }

    function checkFunctionSize(node, suggestions) {
        const lines = node.loc ? (node.loc.end.line - node.loc.start.line) : 0;
        if (lines > 50) {
            suggestions.push(`Function "${node.id ? node.id.name : 'anonymous'}" is quite long (${lines} lines).`);
        }
    }

    function renderSuggestions(suggestions, stats) {
        optimizationList.innerHTML = '';
        
        if (stats.vars > 50) {
            suggestions.push('Large number of variables detected. Consider grouping related data into objects.');
        }
        
        if (suggestions.length > 0) {
            optimizationCard.classList.remove('d-none');
            suggestions.forEach(s => {
                const item = document.createElement('div');
                item.className = 'list-group-item d-flex align-items-start';
                item.innerHTML = `<i class="bi bi-info-circle text-primary me-2 mt-1"></i><span>${s}</span>`;
                optimizationList.appendChild(item);
            });
        } else {
            optimizationCard.classList.add('d-none');
        }
    }
});
</script>
@endpush
@endsection
