@extends('layouts.app')

@push('styles')
<style nonce="{{ $cspNonce }}">
    /* Core Layout & UI Design */
    .tool-section-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 1rem;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .tool-section-card:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        border-color: rgba(37, 99, 235, 0.2);
    }
    .card-header-premium {
        background-color: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        padding: 1.25rem;
    }
    .textarea-custom {
        border-radius: 0.75rem;
        border: 1.5px solid #e2e8f0;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-size: 0.95rem;
        padding: 1rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        resize: vertical;
    }
    .textarea-custom:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        outline: none;
    }
    .btn-action {
        border-radius: 0.5rem;
        font-weight: 500;
        transition: transform 0.1s ease, box-shadow 0.15s ease;
    }
    .btn-action:active {
        transform: scale(0.98);
    }
    
    /* Difference Viewer Styling */
    .diff-wrapper {
        border: 1.5px dashed #cbd5e1;
        border-radius: 0.75rem;
        background-color: #f8fafc;
        overflow: hidden;
    }
    .diff-box-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05em;
        color: #64748b;
        background-color: #f1f5f9;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .diff-box {
        max-height: 250px;
        overflow-y: auto;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-size: 0.85rem;
        padding: 1rem;
        white-space: pre-wrap;
        word-break: break-all;
        background-color: #ffffff;
    }
    
    /* Highlighted Whitespaces */
    .ws-space {
        background-color: rgba(239, 68, 68, 0.15);
        color: #dc2626;
        border-radius: 2px;
        font-weight: bold;
        padding: 0 1px;
        user-select: none;
    }
    .ws-tab {
        background-color: rgba(59, 130, 246, 0.15);
        color: #2563eb;
        border-radius: 2px;
        padding: 0 2px;
        user-select: none;
    }
    .ws-newline {
        background-color: rgba(16, 185, 129, 0.15);
        color: #059669;
        border-radius: 2px;
        padding: 0 2px;
        user-select: none;
    }

    /* Option Item Hover */
    .cleanup-option-item {
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        transition: background-color 0.15s ease;
    }
    .cleanup-option-item:hover {
        background-color: #f1f5f9;
    }
    
    /* Focus Indicators & Accessibility */
    input[type="checkbox"]:focus,
    select:focus,
    button:focus {
        outline: 2px solid #2563eb !important;
        outline-offset: 2px;
    }
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Trust Alert Banner -->
    <div class="col-12">
        <x-ui-trust-indicator />
    </div>

    <!-- Main Workspace (Top Row: Input & Output Side by Side) -->
    <div class="col-12">
        <div class="row g-4">
            <!-- Input Text Card -->
            <div class="col-lg-6">
                <div class="card tool-section-card border-0 h-100">
                    <div class="card-header-premium d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h2 class="h5 fw-bold mb-0 text-dark">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>Source Text
                        </h2>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-action" id="btnLoadExample" aria-label="Load Example Text">
                                <i class="bi bi-magic me-1"></i>Example
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-action" id="btnClearInput" aria-label="Clear Input Text">
                                <i class="bi bi-trash3 me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Dropzone Wrapper -->
                        <div id="dropzone" class="mb-3 position-relative">
                            <textarea id="inputText" class="form-control textarea-custom" rows="12" placeholder="Paste or type your text here..." aria-label="Source text input"></textarea>
                        </div>
                        
                        <!-- File upload status & properties -->
                        <div id="fileStatusContainer" class="alert alert-light border border-light-subtle d-none mb-3 py-2 px-3 rounded-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span class="text-secondary small" id="fileDetailsText">
                                    <i class="bi bi-file-check-fill text-success me-1"></i>
                                    File: <strong class="text-dark" id="lblFileName">-</strong> (<span id="lblFileSize">-</span>)
                                </span>
                                <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none p-0" id="btnCancelFile" aria-label="Remove uploaded file">
                                    Remove File
                                </button>
                            </div>
                        </div>

                        <!-- Input Controls -->
                        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <input type="file" id="fileInput" accept=".txt,.csv,.log,.json,.xml,.html,.md" class="d-none">
                                <button type="button" class="btn btn-light border btn-action" id="btnUploadFile" aria-label="Upload Text File">
                                    <i class="bi bi-upload me-1"></i>Upload
                                </button>
                                <button type="button" class="btn btn-light border btn-action" id="btnCopyInput" aria-label="Copy input text to clipboard">
                                    <i class="bi bi-clipboard me-1"></i>Copy
                                </button>
                            </div>
                            <div class="text-muted small fw-semibold" id="inputCounts">
                                Chars: <span id="valInputChars">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Output Text Card -->
            <div class="col-lg-6">
                <div class="card tool-section-card border-0 h-100">
                    <div class="card-header-premium d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h2 class="h5 fw-bold mb-0 text-dark">
                            <i class="bi bi-file-earmark-check text-success me-2"></i>Cleaned Output
                        </h2>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-action" id="btnSwap" aria-label="Swap Input and Output text">
                            <i class="bi bi-arrow-left-right me-1"></i>Swap
                        </button>
                    </div>
                    
                    <div class="card-body p-4">
                        <textarea id="outputText" class="form-control textarea-custom mb-3 bg-light" rows="12" placeholder="Cleaned text will appear here..." readonly aria-label="Cleaned output text"></textarea>
                        
                        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success btn-action px-3" id="btnCopyOutput" aria-label="Copy cleaned text to clipboard">
                                    <i class="bi bi-clipboard-check me-1"></i>Copy Output
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-download me-1"></i>Export
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><button class="dropdown-item" type="button" id="btnDownloadText">Download (.txt)</button></li>
                                        <li><button class="dropdown-item" type="button" id="btnDownloadCsv">Download (.csv)</button></li>
                                        <li><button class="dropdown-item" type="button" id="btnDownloadJsonReport">JSON Report</button></li>
                                        <li><button class="dropdown-item" type="button" id="btnDownloadHtmlReport">HTML Report</button></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-muted small fw-semibold">
                                Chars: <span id="valOutputChars">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls & Settings (Bottom Row) -->
    <div class="col-lg-7">
        <div class="d-flex flex-column gap-4">
            <!-- Options Card -->
            <div class="card tool-section-card border-0">
                <div class="card-header-premium d-flex align-items-center justify-content-between">
                    <h2 class="h5 fw-bold mb-0 text-dark">
                        <i class="bi bi-sliders text-primary me-2"></i>Cleanup Settings
                    </h2>
                    <div class="form-check form-check-inline mb-0 me-0">
                        <input class="form-check-input" type="checkbox" id="chkAutoUpdate" checked>
                        <label class="form-check-label small fw-bold text-secondary text-uppercase" for="chkAutoUpdate">Auto-Run</label>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="cleanupForm">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Spaces options -->
                                <h3 class="h6 fw-bold text-secondary text-uppercase tracking-wider mb-2">Spaces & Tabs</h3>
                                <div class="mb-3 d-flex flex-column gap-1">
                                    <div class="form-check cleanup-option-item">
                                        <input class="form-check-input" type="checkbox" id="optRemoveAllSpaces" checked>
                                        <label class="form-check-label text-dark" for="optRemoveAllSpaces">Remove all spaces & tabs</label>
                                    </div>
                                    <div class="form-check cleanup-option-item">
                                        <input class="form-check-input" type="checkbox" id="optTrimEveryLine">
                                        <label class="form-check-label text-dark" for="optTrimEveryLine">Trim every line</label>
                                    </div>
                                    <div class="form-check cleanup-option-item">
                                        <input class="form-check-input" type="checkbox" id="optRemoveExtraSpaces">
                                        <label class="form-check-label text-dark" for="optRemoveExtraSpaces">Remove extra spaces</label>
                                    </div>
                                    <div class="form-check cleanup-option-item">
                                        <input class="form-check-input" type="checkbox" id="optRemoveTabs">
                                        <label class="form-check-label text-dark" for="optRemoveTabs">Remove all tabs</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Lines options -->
                                <h3 class="h6 fw-bold text-secondary text-uppercase tracking-wider mb-2">Lines & Paragraphs</h3>
                                <div class="mb-3 d-flex flex-column gap-1">
                                    <div class="form-check cleanup-option-item">
                                        <input class="form-check-input" type="checkbox" id="optRemoveLineBreaks" checked>
                                        <label class="form-check-label text-dark" for="optRemoveLineBreaks">Remove all line breaks</label>
                                    </div>
                                    <div class="form-check cleanup-option-item">
                                        <input class="form-check-input" type="checkbox" id="optRemoveEmptyLines">
                                        <label class="form-check-label text-dark" for="optRemoveEmptyLines">Remove empty lines</label>
                                    </div>
                                    <div class="form-check cleanup-option-item">
                                        <input class="form-check-input" type="checkbox" id="optRemoveDuplicateBlank">
                                        <label class="form-check-label text-dark" for="optRemoveDuplicateBlank">Remove duplicate blank lines</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 border-top pt-3">
                            <button class="btn btn-link btn-sm p-0 text-decoration-none fw-bold text-primary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#advancedOptions" aria-expanded="false" aria-controls="advancedOptions">
                                Show Advanced Options <i class="bi bi-chevron-down ms-1"></i>
                            </button>
                            <div class="collapse" id="advancedOptions">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="findInput" class="form-label small fw-bold text-secondary text-uppercase mb-1">Find</label>
                                        <input type="text" id="findInput" class="form-control form-control-sm" placeholder="Text to find...">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="replaceInput" class="form-label small fw-bold text-secondary text-uppercase mb-1">Replace</label>
                                        <input type="text" id="replaceInput" class="form-control form-control-sm" placeholder="Replace with...">
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <div class="form-check cleanup-option-item">
                                        <input class="form-check-input" type="checkbox" id="optConvertTabs">
                                        <label class="form-check-label text-dark" for="optConvertTabs">Convert tabs to spaces</label>
                                        <select class="form-select form-select-sm d-inline-block w-auto ms-2" id="tabSizeSelect">
                                            <option value="2">2</option>
                                            <option value="4" selected>4</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                                    <div class="form-check cleanup-option-item">
                                        <input class="form-check-input" type="checkbox" id="optConvertLineBreaksToSpaces">
                                        <label class="form-check-label text-dark" for="optConvertLineBreaksToSpaces">Convert line breaks to spaces</label>
                                    </div>
                                    <div class="form-check cleanup-option-item">
                                        <input class="form-check-input" type="checkbox" id="optNormalize">
                                        <label class="form-check-label text-dark" for="optNormalize">Normalize whitespace</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary w-100 btn-action py-2.5 mt-3" id="btnManualRun">
                            <i class="bi bi-play-fill me-1"></i>Run Cleaner
                        </button>
                    </form>
                </div>
            </div>

            <!-- Difference Viewer Card -->
            <div class="card tool-section-card border-0">
                <div class="card-header-premium d-flex align-items-center justify-content-between py-3">
                    <h2 class="h6 fw-bold mb-0 text-dark">
                        <i class="bi bi-file-earmark-diff text-primary me-2"></i>Visual Difference Preview
                    </h2>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch" id="toggleDiff" aria-label="Enable Difference Preview">
                        <label class="form-check-label small fw-bold text-secondary text-uppercase" for="toggleDiff">Show Preview</label>
                    </div>
                </div>
                <div class="card-body p-4 d-none" id="diffContainer">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="diff-wrapper">
                                <div class="diff-box-title">Before</div>
                                <div class="diff-box" id="diffBefore" tabindex="0"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="diff-wrapper">
                                <div class="diff-box-title">After</div>
                                <div class="diff-box" id="diffAfter" tabindex="0"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar (Right Column) -->
    <div class="col-lg-5">
        <div class="d-flex flex-column gap-4">
            <!-- Quick Presets Card -->
            <div class="card tool-section-card border-0">
                <div class="card-header-premium">
                    <h2 class="h5 fw-bold mb-0 text-dark">
                        <i class="bi bi-magic text-primary me-2"></i>One-Click Presets
                    </h2>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary text-start btn-action p-2" id="presetDeveloper">
                            <strong class="d-block text-dark small">Developer Cleanup</strong>
                        </button>
                        <button type="button" class="btn btn-outline-primary text-start btn-action p-2" id="presetCsv">
                            <strong class="d-block text-dark small">CSV Cleanup</strong>
                        </button>
                        <button type="button" class="btn btn-outline-primary text-start btn-action p-2" id="presetHtml">
                            <strong class="d-block text-dark small">HTML Cleanup</strong>
                        </button>
                        <button type="button" class="btn btn-outline-primary text-start btn-action p-2" id="presetJson">
                            <strong class="d-block text-dark small">JSON Cleanup</strong>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card tool-section-card border-0">
                <div class="card-header-premium d-flex align-items-center justify-content-between">
                    <h2 class="h5 fw-bold mb-0 text-dark">
                        <i class="bi bi-bar-chart-line text-primary me-2"></i>Statistics
                    </h2>
                </div>
                <div class="card-body p-4">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded-3 text-center">
                                <div class="text-secondary x-small mb-0">Original Chars</div>
                                <div class="fw-bold text-dark" id="statOriginalChars">0</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded-3 text-center">
                                <div class="text-secondary x-small mb-0">Cleaned Chars</div>
                                <div class="fw-bold text-dark" id="statProcessedChars">0</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded-3 text-center">
                                <div class="text-secondary x-small mb-0">Removed Chars</div>
                                <div class="fw-bold text-danger" id="statRemovedChars">0</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded-3 text-center">
                                <div class="text-secondary x-small mb-0">Removed Lines</div>
                                <div class="fw-bold text-danger" id="statRemovedLines">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    
    // Elements
    const inputText = document.getElementById('inputText');
    const outputText = document.getElementById('outputText');
    
    // File inputs & indicators
    const fileInput = document.getElementById('fileInput');
    const btnUploadFile = document.getElementById('btnUploadFile');
    const fileStatusContainer = document.getElementById('fileStatusContainer');
    const lblFileName = document.getElementById('lblFileName');
    const lblFileSize = document.getElementById('lblFileSize');
    const btnCancelFile = document.getElementById('btnCancelFile');
    const dropzone = document.getElementById('dropzone');
    
    // Buttons
    const btnLoadExample = document.getElementById('btnLoadExample');
    const btnClearInput = document.getElementById('btnClearInput');
    const btnCopyInput = document.getElementById('btnCopyInput');
    const btnCopyOutput = document.getElementById('btnCopyOutput');
    const btnSwap = document.getElementById('btnSwap');
    const btnManualRun = document.getElementById('btnManualRun');
    const btnCopyStats = document.getElementById('btnCopyStats');
    
    // Dropdowns / Export
    const btnDownloadText = document.getElementById('btnDownloadText');
    const btnDownloadCsv = document.getElementById('btnDownloadCsv');
    const btnDownloadJsonReport = document.getElementById('btnDownloadJsonReport');
    const btnDownloadHtmlReport = document.getElementById('btnDownloadHtmlReport');
    
    // Options
    const chkAutoUpdate = document.getElementById('chkAutoUpdate');
    const findInput = document.getElementById('findInput');
    const replaceInput = document.getElementById('replaceInput');
    
    // Accordion settings
    const toggleDiff = document.getElementById('toggleDiff');
    const diffContainer = document.getElementById('diffContainer');
    const diffBefore = document.getElementById('diffBefore');
    const diffAfter = document.getElementById('diffAfter');
    
    // Checkboxes
    const optTrimEveryLine = document.getElementById('optTrimEveryLine');
    const optRemoveLeading = document.getElementById('optRemoveLeading');
    const optRemoveTrailing = document.getElementById('optRemoveTrailing');
    const optRemoveExtraSpaces = document.getElementById('optRemoveExtraSpaces');
    const optRemoveTabs = document.getElementById('optRemoveTabs');
    const optConvertTabs = document.getElementById('optConvertTabs');
    const tabSizeSelect = document.getElementById('tabSizeSelect');
    const optRemoveAllSpaces = document.getElementById('optRemoveAllSpaces');
    const optRemoveEmptyLines = document.getElementById('optRemoveEmptyLines');
    const optRemoveDuplicateBlank = document.getElementById('optRemoveDuplicateBlank');
    const optPreserveParagraphs = document.getElementById('optPreserveParagraphs');
    const optRemoveLineBreaks = document.getElementById('optRemoveLineBreaks');
    const optConvertLineBreaksToSpaces = document.getElementById('optConvertLineBreaksToSpaces');
    const optNormalize = document.getElementById('optNormalize');
    
    // Presets
    const presetDeveloper = document.getElementById('presetDeveloper');
    const presetCsv = document.getElementById('presetCsv');
    const presetHtml = document.getElementById('presetHtml');
    const presetJson = document.getElementById('presetJson');
    const presetLog = document.getElementById('presetLog');
    
    // Stats Elements
    const statOriginalChars = document.getElementById('statOriginalChars');
    const statProcessedChars = document.getElementById('statProcessedChars');
    const statRemovedChars = document.getElementById('statRemovedChars');
    const statRemovedLines = document.getElementById('statRemovedLines');
    const statRemovedSpaces = document.getElementById('statRemovedSpaces');
    const statRemovedTabs = document.getElementById('statRemovedTabs');
    const statRemovedEmptyLines = document.getElementById('statRemovedEmptyLines');
    const statProcessingTime = document.getElementById('statProcessingTime');
    
    // Counter values
    const valInputChars = document.getElementById('valInputChars');
    const valInputWords = document.getElementById('valInputWords');
    const valInputLines = document.getElementById('valInputLines');
    const valOutputChars = document.getElementById('valOutputChars');
    const valOutputWords = document.getElementById('valOutputWords');
    const valOutputLines = document.getElementById('valOutputLines');

    let processingStats = null;
    let uploadedFile = null;

    // Example Library
    const examples = {
        simple: "   Hello   World!   Welcome   to   Toolzy.   ",
        messy: "   This is   a messy   text\twith mixed tabs   and\n\n\nmultiple blank lines.\n\n   It needs serious\t\tcleaning.",
        pdf: "Lorem ipsum dolor sit amet,\nconsectetur adipiscing elit.\nSed do eiusmod tempor\nincididunt ut labore.",
        csv: "ID, Name, Role, Location\t\n1,\tJohn Doe,\tDeveloper,\tUS\n2, Jane Smith, Manager,\tUK\t\n\n3, Bob Johnson, Designer, CA",
        html: "  <div class=\"container\">  \n\t<h1>  Title  </h1>\n\n\n\t<p>   Some paragraph  </p>   \n  </div>  ",
        json: "{\n\t\"name\":   \"Toolzy\",\n\t\"features\":   [\n\t\t\"fast\",\n\t\t\"secure\"\n\t]\n}",
        log: "2026-06-13 10:00:00 [INFO]  Starting service...\n\n\n2026-06-13 10:00:05 [DEBUG]   Database connection established.   \n\n2026-06-13 10:00:10 [WARN]  Slow response time detected.\t\t",
        markdown: " #  Markdown Title  \n\n\n##  Sub-title\n\n  This is some *formatted* content.  \n\n-  Item 1\n-  Item 2  \n"
    };
    
    // Example cycle counter
    let exampleIndex = 0;
    const exampleKeys = Object.keys(examples);

    // Event Listeners
    inputText.addEventListener('input', handleInputChange);
    findInput.addEventListener('input', handleInputChange);
    replaceInput.addEventListener('input', handleInputChange);
    
    // Checkbox Listeners
    const optionCheckboxes = [
        optTrimEveryLine, optRemoveLeading, optRemoveTrailing, optRemoveExtraSpaces,
        optRemoveTabs, optConvertTabs, optRemoveAllSpaces, optRemoveEmptyLines,
        optRemoveDuplicateBlank, optPreserveParagraphs, optRemoveLineBreaks,
        optConvertLineBreaksToSpaces, optNormalize, tabSizeSelect
    ].filter(el => el !== null);
    
    optionCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            // Option dependency conflicts resolutions
            if (cb === optTrimEveryLine && optTrimEveryLine.checked) {
                optRemoveLeading.checked = false;
                optRemoveTrailing.checked = false;
            } else if ((cb === optRemoveLeading || cb === optRemoveTrailing) && cb.checked) {
                optTrimEveryLine.checked = false;
            }
            
            if (cb === optRemoveTabs && optRemoveTabs.checked) {
                optConvertTabs.checked = false;
            } else if (cb === optConvertTabs && optConvertTabs.checked) {
                optRemoveTabs.checked = false;
            }

            if (cb === optRemoveLineBreaks && optRemoveLineBreaks.checked) {
                optConvertLineBreaksToSpaces.checked = false;
            } else if (cb === optConvertLineBreaksToSpaces && optConvertLineBreaksToSpaces.checked) {
                optRemoveLineBreaks.checked = false;
            }

            if (cb === optRemoveAllSpaces && optRemoveAllSpaces.checked) {
                optRemoveExtraSpaces.checked = false;
                optNormalize.checked = false;
            } else if ((cb === optRemoveExtraSpaces || cb === optNormalize) && cb.checked) {
                optRemoveAllSpaces.checked = false;
            }

            handleInputChange();
        });
    });

    // Form submission
    if (cleanupForm) {
        cleanupForm.addEventListener('submit', (e) => e.preventDefault());
    }

    // Preset button listeners
    if (presetDeveloper) presetDeveloper.addEventListener('click', () => applyPreset('developer'));
    if (presetCsv) presetCsv.addEventListener('click', () => applyPreset('csv'));
    if (presetHtml) presetHtml.addEventListener('click', () => applyPreset('html'));
    if (presetJson) presetJson.addEventListener('click', () => applyPreset('json'));

    if (btnManualRun) btnManualRun.addEventListener('click', runCleaner);
    
    // Clipboard copies
    if (btnCopyInput) {
        btnCopyInput.addEventListener('click', () => {
            copyTextToClipboard(inputText.value, 'Input copied!');
            dispatchAnalyticsEvent('Output Copied', { source: 'input' });
        });
    }
    
    if (btnCopyOutput) {
        btnCopyOutput.addEventListener('click', () => {
            copyTextToClipboard(outputText.value, 'Output copied!');
            dispatchAnalyticsEvent('Output Copied', { source: 'output' });
        });
    }
    
    if (btnCopyStats) {
        btnCopyStats.addEventListener('click', () => {
            if (!processingStats) return;
            const report = `Toolzy Cleanup Statistics:
Original Characters: ${processingStats.originalChars}
Cleaned Characters: ${processingStats.processedChars}
Characters Removed: ${processingStats.removedChars}
Lines Removed: ${processingStats.removedLines}
Spaces Removed: ${processingStats.spacesRemoved}
Tabs Removed: ${processingStats.tabsRemoved}
Empty Lines Removed: ${processingStats.emptyLinesRemoved}
Processing Time: ${processingStats.timeMs}ms`;
            copyTextToClipboard(report, 'Statistics copied!');
            dispatchAnalyticsEvent('Output Copied', { source: 'statistics' });
        });
    }

    // Clear and Swap
    btnClearInput.addEventListener('click', () => {
        if (inputText.value.trim() === '' || confirm('Are you sure you want to clear your input?')) {
            inputText.value = '';
            clearUploadedFile();
            handleInputChange();
        }
    });

    btnSwap.addEventListener('click', () => {
        const temp = inputText.value;
        inputText.value = outputText.value;
        outputText.value = temp;
        clearUploadedFile();
        handleInputChange();
    });

    // Difference preview switch
    toggleDiff.addEventListener('change', () => {
        if (toggleDiff.checked) {
            diffContainer.classList.remove('d-none');
            renderDiff();
        } else {
            diffContainer.classList.add('d-none');
        }
    });

    // Example Loader
    btnLoadExample.addEventListener('click', () => {
        const key = exampleKeys[exampleIndex];
        inputText.value = examples[key];
        clearUploadedFile();
        handleInputChange();
        
        showToast(`Loaded ${key.toUpperCase()} example!`, 'info');
        dispatchAnalyticsEvent('Example Loaded', { example: key });

        // Cycle examples
        exampleIndex = (exampleIndex + 1) % exampleKeys.length;
    });

    // Upload File trigger
    btnUploadFile.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', handleFileSelect);
    if (btnCancelFile) btnCancelFile.addEventListener('click', clearUploadedFile);

    // Drag & Drop
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('bg-light');
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('bg-light');
    });
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('bg-light');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            processFile(files[0]);
        }
    });

    // Download exports
    btnDownloadText.addEventListener('click', () => {
        triggerDownload(outputText.value, getCleanedFileName('.txt'), 'text/plain');
        dispatchAnalyticsEvent('Download Action', { type: 'txt' });
    });

    btnDownloadCsv.addEventListener('click', () => {
        triggerDownload(outputText.value, getCleanedFileName('.csv'), 'text/csv');
        dispatchAnalyticsEvent('Download Action', { type: 'csv' });
    });

    btnDownloadJsonReport.addEventListener('click', () => {
        if (!processingStats) return;
        const report = {
            application: "Toolzy Text Whitespace Remover",
            timestamp: new Date().toISOString(),
            fileName: uploadedFile ? uploadedFile.name : 'pasted-text',
            statistics: processingStats,
            summary: `Cleaned ${processingStats.originalChars} characters into ${processingStats.processedChars} characters.`
        };
        triggerDownload(JSON.stringify(report, null, 2), getCleanedFileName('-report.json'), 'application/json');
        dispatchAnalyticsEvent('Download Action', { type: 'json' });
    });

    btnDownloadHtmlReport.addEventListener('click', () => {
        if (!processingStats) return;
        const htmlContent = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Toolzy Whitespace Cleanup Report</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; color: #1e293b; line-height: 1.5; }
        .card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto; }
        h1 { color: #2563eb; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e2e8f0; }
        th { background-color: #f8fafc; font-weight: 600; }
        .success { color: #10b981; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Toolzy Whitespace Cleanup Report</h1>
        <p>Report generated securely at: <strong>${new Date().toLocaleString()}</strong></p>
        <table>
            <tr><th>Metric</th><th>Value</th></tr>
            <tr><td>Original Characters</td><td>${processingStats.originalChars}</td></tr>
            <tr><td>Processed Characters</td><td>${processingStats.processedChars}</td></tr>
            <tr class="success"><td>Characters Removed</td><td>${processingStats.removedChars}</td></tr>
            <tr class="success"><td>Lines Removed</td><td>${processingStats.removedLines}</td></tr>
            <tr><td>Spaces Removed</td><td>${processingStats.spacesRemoved}</td></tr>
            <tr><td>Tabs Removed</td><td>${processingStats.tabsRemoved}</td></tr>
            <tr><td>Empty Lines Removed</td><td>${processingStats.emptyLinesRemoved}</td></tr>
            <tr><td>Time Taken</td><td>${processingStats.timeMs} ms</td></tr>
        </table>
    </div>
</body>
</html>`;
        triggerDownload(htmlContent, getCleanedFileName('-report.html'), 'text/html');
        dispatchAnalyticsEvent('Download Action', { type: 'html' });
    });

    // Helper functions

    function handleInputChange() {
        // Compute input stats instantly
        const text = inputText.value;
        const chars = text.length;
        const words = chars > 0 ? text.trim().split(/\s+/).filter(Boolean).length : 0;
        const lines = chars > 0 ? text.split(/\r?\n/).length : 0;

        if (valInputChars) valInputChars.textContent = formatNum(chars);
        if (valInputWords) valInputWords.textContent = formatNum(words);
        if (valInputLines) valInputLines.textContent = formatNum(lines);

        if (chkAutoUpdate.checked) {
            runCleaner();
        }
    }

    function runCleaner() {
        const text = inputText.value;
        if (text.length === 0) {
            outputText.value = '';
            if (valOutputChars) valOutputChars.textContent = '0';
            if (valOutputWords) valOutputWords.textContent = '0';
            if (valOutputLines) valOutputLines.textContent = '0';
            resetStats();
            if (toggleDiff.checked) renderDiff();
            return;
        }

        const cleanResult = processTextCore(text);
        outputText.value = cleanResult.result;
        processingStats = cleanResult.stats;

        // Update output stats
        const outText = cleanResult.result;
        const outChars = outText.length;
        const outWords = outChars > 0 ? outText.trim().split(/\s+/).filter(Boolean).length : 0;
        const outLines = outChars > 0 ? outText.split(/\r?\n/).length : 0;

        if (valOutputChars) valOutputChars.textContent = formatNum(outChars);
        if (valOutputWords) valOutputWords.textContent = formatNum(outWords);
        if (valOutputLines) valOutputLines.textContent = formatNum(outLines);

        // Update stats card UI
        if (statOriginalChars) statOriginalChars.textContent = formatNum(processingStats.originalChars);
        if (statProcessedChars) statProcessedChars.textContent = formatNum(processingStats.processedChars);
        if (statRemovedChars) statRemovedChars.textContent = formatNum(processingStats.removedChars);
        if (statRemovedLines) statRemovedLines.textContent = formatNum(processingStats.removedLines);
        if (statRemovedSpaces) statRemovedSpaces.textContent = formatNum(processingStats.spacesRemoved);
        if (statRemovedTabs) statRemovedTabs.textContent = formatNum(processingStats.tabsRemoved);
        if (statRemovedEmptyLines) statRemovedEmptyLines.textContent = formatNum(processingStats.emptyLinesRemoved);
        if (statProcessingTime) statProcessingTime.textContent = `${processingStats.timeMs}ms`;

        // Render diff visualizer if enabled
        if (toggleDiff.checked) {
            renderDiff();
        }

        dispatchAnalyticsEvent('Text Cleaned', { length: text.length });
    }

    function processTextCore(text) {
        let result = text;
        const startTime = performance.now();

        // Count initial layout parameters
        const initialSpaces = (result.match(/ /g) || []).length;
        const initialTabs = (result.match(/\t/g) || []).length;
        const initialLines = result.split(/\r?\n/);
        const initialEmptyLines = initialLines.filter(line => line.replace(/\r/g, '') === '').length;

        // 1. Search & Replace (XSS Safe - all regex is handled internally and values are parsed purely as text)
        const findStr = findInput.value;
        const replaceStr = replaceInput.value;
        if (findStr) {
            result = result.replaceAll(findStr, replaceStr);
        }

        // 2. Options pipelines
        let lines = result.split(/\r?\n/);

        // Trim every line
        if (optTrimEveryLine.checked) {
            lines = lines.map(l => l.trim());
        } else {
            if (optRemoveLeading && optRemoveLeading.checked) {
                lines = lines.map(l => l.replace(/^\s+/, ''));
            }
            if (optRemoveTrailing && optRemoveTrailing.checked) {
                lines = lines.map(l => l.replace(/\s+$/, ''));
            }
        }

        result = lines.join('\n');

        // Remove Tabs
        if (optRemoveTabs.checked) {
            result = result.replace(/\t/g, '');
        }

        // Convert Tabs to Spaces
        if (optConvertTabs.checked) {
            const size = parseInt(tabSizeSelect.value) || 4;
            result = result.replace(/\t/g, ' '.repeat(size));
        }

        // Remove Duplicate Blank Lines
        if (optRemoveDuplicateBlank.checked) {
            result = result.replace(/\n{3,}/g, '\n\n');
        }

        // Remove Empty Lines
        if (optRemoveEmptyLines.checked) {
            let tempLines = result.split('\n');
            tempLines = tempLines.filter(l => l.replace(/\r/g, '').trim() !== '');
            result = tempLines.join('\n');
        }

        // Preserve Paragraph breaks (Normalize spacing within double newlines)
        if (optPreserveParagraphs && optPreserveParagraphs.checked) {
            let paragraphs = result.split(/\n{2,}/);
            paragraphs = paragraphs.map(p => p.replace(/\s+/g, ' ').trim());
            result = paragraphs.join('\n\n');
        }

        // Remove Extra Spaces
        if (optRemoveExtraSpaces.checked) {
            result = result.replace(/ {2,}/g, ' ');
        }

        // Remove All Line Breaks
        if (optRemoveLineBreaks.checked) {
            result = result.replace(/\r?\n/g, '');
        } else if (optConvertLineBreaksToSpaces.checked) {
            result = result.replace(/\r?\n/g, ' ');
        }

        // Remove All Spaces
        if (optRemoveAllSpaces.checked) {
            result = result.replace(/[ \t]/g, '');
        }

        // Normalize Whitespace
        if (optNormalize && optNormalize.checked) {
            result = result.replace(/\s+/g, ' ').trim();
        }

        const endTime = performance.now();

        // Recount final parameters
        const finalSpaces = (result.match(/ /g) || []).length;
        const finalTabs = (result.match(/\t/g) || []).length;
        const finalLines = result.split(/\r?\n/);
        const finalEmptyLines = finalLines.filter(line => line.replace(/\r/g, '') === '').length;

        const stats = {
            originalChars: text.length,
            originalLines: text.split(/\r?\n/).length,
            processedChars: result.length,
            processedLines: finalLines.length,
            removedChars: Math.max(0, text.length - result.length),
            removedLines: Math.max(0, text.split(/\r?\n/).length - finalLines.length),
            spacesRemoved: Math.max(0, initialSpaces - finalSpaces),
            tabsRemoved: Math.max(0, initialTabs - finalTabs),
            emptyLinesRemoved: Math.max(0, initialEmptyLines - finalEmptyLines),
            timeMs: (endTime - startTime).toFixed(2)
        };

        return { result, stats };
    }

    function applyPreset(name) {
        // Clear conflicting options first
        optionCheckboxes.forEach(cb => {
            if (cb !== tabSizeSelect) cb.checked = false;
        });

        switch (name) {
            case 'developer':
                optTrimEveryLine.checked = true;
                optConvertTabs.checked = true;
                tabSizeSelect.value = "4";
                optRemoveDuplicateBlank.checked = true;
                break;
            case 'csv':
                optTrimEveryLine.checked = true;
                optRemoveExtraSpaces.checked = true;
                optRemoveEmptyLines.checked = true;
                break;
            case 'html':
                optTrimEveryLine.checked = true;
                optRemoveEmptyLines.checked = true;
                optRemoveExtraSpaces.checked = true;
                optRemoveDuplicateBlank.checked = true;
                break;
            case 'json':
                optTrimEveryLine.checked = true;
                optConvertTabs.checked = true;
                tabSizeSelect.value = "2";
                optRemoveExtraSpaces.checked = true;
                optRemoveEmptyLines.checked = true;
                break;
        }

        showToast(`Applied ${name.toUpperCase()} Preset!`, 'success');
        dispatchAnalyticsEvent('Preset Used', { preset: name });
        handleInputChange();
    }

    // Difference Rendering
    function renderDiff() {
        const textBefore = inputText.value;
        const textAfter = outputText.value;

        // Truncate to avoid browser crash/lag on huge datasets
        const limit = 5000;
        let htmlBefore = '';
        let htmlAfter = '';

        if (textBefore.length > 0) {
            const truncatedBefore = textBefore.substring(0, limit);
            htmlBefore = escapeHtmlAndHighlight(truncatedBefore);
            if (textBefore.length > limit) {
                htmlBefore += '<div class="text-danger small mt-2 fw-semibold">[Truncated for performance...]</div>';
            }
        } else {
            htmlBefore = '<span class="text-muted">No text to display.</span>';
        }

        if (textAfter.length > 0) {
            const truncatedAfter = textAfter.substring(0, limit);
            htmlAfter = escapeHtmlAndHighlight(truncatedAfter);
            if (textAfter.length > limit) {
                htmlAfter += '<div class="text-danger small mt-2 fw-semibold">[Truncated for performance...]</div>';
            }
        } else {
            htmlAfter = '<span class="text-muted">No text to display.</span>';
        }

        diffBefore.innerHTML = htmlBefore;
        diffAfter.innerHTML = htmlAfter;
    }

    function escapeHtmlAndHighlight(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;")
            .replace(/ /g, '<span class="ws-space" title="Space">·</span>')
            .replace(/\t/g, '<span class="ws-tab" title="Tab">→&nbsp;&nbsp;&nbsp;&nbsp;</span>')
            .replace(/\r?\n/g, '<span class="ws-newline" title="Line Break">↵</span><br>');
    }

    // File processing
    function handleFileSelect(e) {
        const file = e.target.files[0];
        if (file) {
            processFile(file);
        }
    }

    function processFile(file) {
        // Validate File Size (10MB limit)
        const sizeMb = file.size / (1024 * 1024);
        if (sizeMb > 10) {
            showToast('File size exceeds 10MB limit!', 'danger');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            inputText.value = e.target.result;
            uploadedFile = file;

            // Update UI elements
            if (lblFileName) lblFileName.textContent = file.name;
            if (lblFileSize) lblFileSize.textContent = formatBytes(file.size);
            if (fileStatusContainer) fileStatusContainer.classList.remove('d-none');

            showToast(`Uploaded ${file.name} successfully!`, 'success');
            dispatchAnalyticsEvent('File Uploaded', { name: file.name, size: file.size });
            handleInputChange();
        };
        reader.readAsText(file);
    }

    function clearUploadedFile() {
        uploadedFile = null;
        fileInput.value = '';
        if (fileStatusContainer) fileStatusContainer.classList.add('d-none');
    }

    // Export Trigger
    function triggerDownload(content, filename, contentType) {
        const blob = new Blob([content], { type: contentType });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast(`Downloaded ${filename}!`, 'success');
    }

    function getCleanedFileName(extension) {
        if (uploadedFile) {
            const dotIdx = uploadedFile.name.lastIndexOf('.');
            const baseName = dotIdx !== -1 ? uploadedFile.name.substring(0, dotIdx) : uploadedFile.name;
            return `${baseName}-cleaned${extension}`;
        }
        return `cleaned-text${extension}`;
    }

    // Utilities
    function formatNum(num) {
        return num.toLocaleString();
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function resetStats() {
        if (statOriginalChars) statOriginalChars.textContent = '0';
        if (statProcessedChars) statProcessedChars.textContent = '0';
        if (statRemovedChars) statRemovedChars.textContent = '0';
        if (statRemovedLines) statRemovedLines.textContent = '0';
        if (statRemovedSpaces) statRemovedSpaces.textContent = '0';
        if (statRemovedTabs) statRemovedTabs.textContent = '0';
        if (statRemovedEmptyLines) statRemovedEmptyLines.textContent = '0';
        if (statProcessingTime) statProcessingTime.textContent = '0ms';
        processingStats = null;
    }

    function copyTextToClipboard(text, successMsg) {
        if (!text) {
            showToast('No text available to copy!', 'warning');
            return;
        }
        navigator.clipboard.writeText(text).then(() => {
            showToast(successMsg, 'success');
        }).catch(err => {
            showToast('Failed to copy to clipboard', 'danger');
        });
    }

    // Analytics Hook
    function dispatchAnalyticsEvent(eventName, params = {}) {
        const event = new CustomEvent('toolzy-analytics', {
            detail: { eventName, params }
        });
        window.dispatchEvent(event);
    }
});
</script>
@endpush
