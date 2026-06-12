@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />
    <div class="row g-4">
        <!-- Input Section -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-code-square me-2 text-primary"></i>HTML Input</h5>
                    <div class="btn-group btn-group-sm">
                        <button id="btnExample" class="btn btn-outline-primary">Load Example</button>
                        <button id="btnClear" class="btn btn-outline-danger">Clear</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <textarea id="htmlInput" class="form-control border-0 font-monospace p-3" rows="14" placeholder="<!-- Paste your HTML here... -->
<!DOCTYPE html>
<html>
<head>
    <title>Toolzy Example</title>
</head>
<body>
    <h1 class='title'>Hello World</h1>
    <p>Clean your markup instantly.</p>
</body>
</html>"></textarea>
                </div>
                <div class="card-footer bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <input type="file" id="fileInput" class="d-none" accept=".html,.htm,.txt">
                            <button id="btnUpload" class="btn btn-sm btn-outline-secondary"><i class="bi bi-upload me-1"></i>Upload File</button>
                            <button id="btnCopyInput" class="btn btn-sm btn-outline-secondary"><i class="bi bi-clipboard me-1"></i>Copy Input</button>
                        </div>
                        <div class="small text-muted italic">
                            <span id="charCount">0</span> Chars | <span id="lineCount">0</span> Lines | <span id="tagCount">0</span> Tags
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation Info -->
            <div id="validationArea" class="alert alert-warning d-none py-2 mb-4 shadow-sm">
                <h6 class="fw-bold mb-1 small text-uppercase"><i class="bi bi-exclamation-triangle-fill me-2"></i>Structural Validation</h6>
                <div id="validationMsg" class="small"></div>
            </div>

            <!-- Action Buttons -->
            <div class="card mb-4 shadow-sm border-primary border-opacity-25">
                <div class="card-body py-3">
                    <div class="d-flex gap-3">
                        <button id="btnFormat" class="btn btn-primary px-4 fw-bold flex-grow-1">
                            <i class="bi bi-magic me-2"></i>Format HTML
                        </button>
                        <button id="btnMinify" class="btn btn-dark px-4 fw-bold flex-grow-1">
                            <i class="bi bi-arrows-angle-contract me-2"></i>Minify HTML
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabs for Results -->
            <ul class="nav nav-pills mb-3 gap-2" id="resultTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active btn-sm" id="code-tab" data-bs-toggle="pill" data-bs-target="#resultCode" type="button" role="tab">Output Code</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn-sm" id="tree-tab" data-bs-toggle="pill" data-bs-target="#resultTree" type="button" role="tab">DOM Tree Explorer</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn-sm" id="analysis-tab" data-bs-toggle="pill" data-bs-target="#resultAnalysis" type="button" role="tab">Detailed Analysis</button>
                </li>
            </ul>

            <div class="tab-content" id="resultTabsContent">
                <!-- Code Result -->
                <div class="tab-pane fade show active" id="resultCode" role="tabpanel">
                    <div id="outputContainer" class="card shadow-sm d-none">
                        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                            <span id="outputTitle" class="small fw-bold text-muted text-uppercase">Beautified Markup</span>
                            <div class="btn-group btn-group-sm">
                                <button id="btnCopyOutput" class="btn btn-outline-secondary"><i class="bi bi-clipboard"></i></button>
                                <button id="btnDownload" class="btn btn-outline-success"><i class="bi bi-download"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0 position-relative">
                            <pre id="htmlOutput" class="p-3 mb-0 font-monospace bg-dark text-white rounded-bottom overflow-auto"></pre>
                            <div id="compressionStats" class="position-absolute bottom-0 end-0 p-3 d-none">
                                <div class="badge bg-success shadow-sm p-2 text-start">
                                    <div class="x-small text-uppercase opacity-75">Saved</div>
                                    <div id="savedPercent" class="fw-bold fs-6">0%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DOM Tree -->
                <div class="tab-pane fade" id="resultTree" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">Collapsible DOM Tree</h6>
                            <div class="input-group input-group-sm w-50">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                                <input type="text" id="treeSearch" class="form-control border-start-0" placeholder="Search nodes...">
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div id="domTree" class="dom-tree-container font-monospace small">
                                <div class="text-center text-muted py-4">Format or Analyze HTML to view the DOM tree.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analysis -->
                <div class="tab-pane fade" id="resultAnalysis" role="tabpanel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-3 fw-bold">SEO & Document Analysis</div>
                                <div class="card-body p-0">
                                    <div id="seoAnalysis" class="list-group list-group-flush small">
                                        <div class="p-3 text-muted">Run analysis to see SEO metrics.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-header bg-white py-3 fw-bold">Accessibility Check</div>
                                <div class="card-body p-0">
                                    <div id="a11yAnalysis" class="list-group list-group-flush small">
                                        <div class="p-3 text-muted">Run analysis to see accessibility flags.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Options -->
        <div class="col-lg-4">
            <!-- Formatting Rules -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i>Formatting Options</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Indentation</label>
                        <select id="optIndent" class="form-select form-select-sm">
                            <option value="2">2 Spaces</option>
                            <option value="4" selected>4 Spaces</option>
                            <option value="8">8 Spaces</option>
                            <option value="tabs">Tabs</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Attributes</label>
                        <select id="optAttr" class="form-select form-select-sm">
                            <option value="auto" selected>Auto Wrap</option>
                            <option value="inline">Always Inline</option>
                            <option value="new">One per line</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="optComments" checked>
                        <label class="form-check-label small" for="optComments">Preserve Comments</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="optEmpty" checked>
                        <label class="form-check-label small" for="optEmpty">Preserve Empty Elements</label>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted text-uppercase">Max Line Width</label>
                        <input type="number" id="optLineWidth" class="form-control form-control-sm" value="120">
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div id="statsCard" class="card shadow-sm mb-4 d-none">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line me-2"></i>Quick Stats</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between"><span>Unique Tags</span> <span id="statUniqueTags" class="fw-bold">0</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Classes Used</span> <span id="statClasses" class="fw-bold text-primary">0</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>IDs Defined</span> <span id="statIds" class="fw-bold text-info">0</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>DOM Depth</span> <span id="statDepth" class="fw-bold">0</span></li>
                    </ul>
                </div>
            </div>

            <!-- Optimization Suggestions -->
            <div id="optimizationArea" class="card shadow-sm mb-4 d-none">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold text-success"><i class="bi bi-lightning-charge me-2"></i>Optimization</h6>
                </div>
                <div class="card-body p-0">
                    <div id="optimizationList" class="list-group list-group-flush small">
                        <!-- Suggestions -->
                    </div>
                </div>
            </div>

            <!-- Extractions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-box-arrow-right me-2"></i>Quick Extract</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <button class="btn btn-sm btn-outline-secondary text-start extract-btn" data-type="links">Extract All Links</button>
                    <button class="btn btn-sm btn-outline-secondary text-start extract-btn" data-type="images">Extract Image URLs</button>
                    <button class="btn btn-sm btn-outline-secondary text-start extract-btn" data-type="meta">Extract Meta Tags</button>
                    <button class="btn btn-sm btn-outline-secondary text-start extract-btn" data-type="text">Extract Text Content</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Content -->
    <div class="mt-5 pt-5 border-top">
        <div class="row g-5">
            <div class="col-lg-8">
                <h2 class="h3 fw-bold mb-4">Mastering HTML Structure & Optimization</h2>
                
                <div class="mb-5">
                    <h4 class="h5 fw-bold text-dark mb-3">What is HTML Formatting & Beautification?</h4>
                    <p class="text-muted small">HTML formatting is the reorganization of your markup into a consistent, hierarchical structure. While browsers can parse a single long string of HTML, developers need proper indentation and line breaks to understand the parent-child relationships within the <strong>DOM (Document Object Model)</strong>. Our beautifier helps you audit complex layouts, ensure tags are properly nested, and maintain a high-quality codebase.</p>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 bg-light h-100 border-start border-primary border-4">
                            <h5 class="fw-bold small text-uppercase text-primary mb-3">The Power of Minification</h5>
                            <p class="text-muted mb-0 small">Minification strips out whitespace, comments, and redundant characters to shrink your HTML file size. For large websites, this can reduce the page weight by up to 20-30%, leading to faster downloads, better <strong>Core Web Vitals</strong> scores, and improved SEO performance.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 bg-light h-100 border-start border-success border-4">
                            <h5 class="fw-bold small text-uppercase text-success mb-3">Semantic HTML Benefits</h5>
                            <p class="text-muted mb-0 small">Using semantic tags like <code>&lt;article&gt;</code>, <code>&lt;nav&gt;</code>, and <code>&lt;main&gt;</code> instead of generic <code>&lt;div&gt;</code> containers helps screen readers and search engines understand your content better. Our tool analyzes your tag distribution to encourage better semantic habits.</p>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h4 class="h5 fw-bold text-dark mb-3">Best Practices for Clean Markup</h4>
                    <ul class="text-muted small lh-lg">
                        <li><strong>Always use Alt text:</strong> Images without alt attributes are invisible to screen readers and bad for SEO.</li>
                        <li><strong>Avoid inline styles:</strong> Keep your presentation logic in CSS to maintain a clean separation of concerns.</li>
                        <li><strong>Audit your DOM depth:</strong> Deeply nested elements (more than 10-15 levels) can slow down browser rendering performance.</li>
                        <li><strong>Use unique IDs:</strong> Duplicate ID attributes are invalid HTML and can break JavaScript functionality.</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 bg-dark text-white p-4 rounded-4 shadow-sm">
                    <h5 class="fw-bold mb-3 text-primary">Common HTML Pitfalls</h5>
                    <ul class="small list-unstyled mb-0 lh-lg">
                        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i><strong>Unclosed Tags:</strong> Can lead to unpredictable layout shifts.</li>
                        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i><strong>Multiple H1 Tags:</strong> Confuses search engines about your page's primary topic.</li>
                        <li class="mb-2"><i class="bi bi-x-circle text-danger me-2"></i><strong>Missing Labels:</strong> Makes forms inaccessible to users with disabilities.</li>
                        <li><i class="bi bi-x-circle text-danger me-2"></i><strong>Broken Nesting:</strong> Closing a parent tag before its children.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

#htmlInput {
    resize: none;
    font-size: 0.9rem;
    background-color: #fcfcfc;
}

#htmlOutput {
    white-space: pre-wrap;
    font-size: 0.85rem;
    line-height: 1.5;
    tab-size: 4;
    max-height: 600px;
}

.italic { font-style: italic; }
.x-small { font-size: 0.75rem; }

/* Tree View Styling */
.dom-tree-container {
    max-height: 500px;
    overflow: auto;
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}
.tree-node {
    margin-left: 1.5rem;
    border-left: 1px solid #dee2e6;
    padding-left: 0.5rem;
}
.tree-toggle { cursor: pointer; color: #6c757d; font-size: 0.75rem; }
.tree-tag { color: #800000; font-weight: bold; }
.tree-attr { color: #ff0000; font-style: italic; }
.tree-val { color: #0000ff; }
.tree-text { color: #444; }

/* Syntax Highlighting */
.hl-tag { color: #569cd6; }
.hl-attr { color: #9cdcfe; }
.hl-val { color: #ce9178; }
.hl-cmt { color: #6a9955; font-style: italic; }
.hl-ent { color: #d7ba7d; }
.hl-str { color: #ce9178; }
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const HTMLTool = (function() {
    let lastResult = '';
    let parsedDoc = null;

    const elements = {
        input: document.getElementById('htmlInput'),
        output: document.getElementById('htmlOutput'),
        outputContainer: document.getElementById('outputContainer'),
        charCount: document.getElementById('charCount'),
        lineCount: document.getElementById('lineCount'),
        tagCount: document.getElementById('tagCount'),
        validationArea: document.getElementById('validationArea'),
        validationMsg: document.getElementById('validationMsg'),
        domTree: document.getElementById('domTree'),
        seoAnalysis: document.getElementById('seoAnalysis'),
        a11yAnalysis: document.getElementById('a11yAnalysis'),
        statsCard: document.getElementById('statsCard'),
        optimizationArea: document.getElementById('optimizationArea'),
        optimizationList: document.getElementById('optimizationList'),
        compressionStats: document.getElementById('compressionStats'),
        savedPercent: document.getElementById('savedPercent'),
        treeSearch: document.getElementById('treeSearch'),

        // Stats
        statUniqueTags: document.getElementById('statUniqueTags'),
        statClasses: document.getElementById('statClasses'),
        statIds: document.getElementById('statIds'),
        statDepth: document.getElementById('statDepth'),

        // Options
        optIndent: document.getElementById('optIndent'),
        optAttr: document.getElementById('optAttr'),
        optComments: document.getElementById('optComments'),
        optEmpty: document.getElementById('optEmpty'),
        optLineWidth: document.getElementById('optLineWidth')
    };

    function init() {
        bindEvents();
    }

    function bindEvents() {
        document.getElementById('btnFormat').addEventListener('click', () => process('format'));
        document.getElementById('btnMinify').addEventListener('click', () => process('minify'));
        document.getElementById('btnExample').addEventListener('click', loadExample);
        document.getElementById('btnClear').addEventListener('click', clear);
        document.getElementById('btnUpload').addEventListener('click', () => document.getElementById('fileInput').click());
        document.getElementById('fileInput').addEventListener('change', handleFileUpload);
        document.getElementById('btnCopyInput').addEventListener('click', () => copyToClipboard(elements.input.value, 'Input copied'));
        document.getElementById('btnCopyOutput').addEventListener('click', () => copyToClipboard(lastResult, 'HTML copied'));
        document.getElementById('btnDownload').addEventListener('click', download);
        
        elements.input.addEventListener('input', updateCounts);
        elements.treeSearch.addEventListener('input', filterTree);

        document.querySelectorAll('.extract-btn').forEach(btn => {
            btn.addEventListener('click', () => extract(btn.getAttribute('data-type')));
        });
    }

    function process(mode) {
        const raw = elements.input.value.trim();
        if (!raw) return showToast('Please enter some HTML', 'warning');

        try {
            const start = performance.now();
            
            // 1. Parse for Analysis
            const parser = new DOMParser();
            parsedDoc = parser.parseFromString(raw, 'text/html');

            // 2. Perform Full Analysis
            runFullAnalysis(parsedDoc, raw);

            // 3. Transformation
            if (mode === 'format') {
                const options = {
                    indent: elements.optIndent.value === 'tabs' ? '\t' : ' '.repeat(parseInt(elements.optIndent.value)),
                    attrWrap: elements.optAttr.value,
                    preserveComments: elements.optComments.checked,
                    preserveEmpty: elements.optEmpty.checked
                };
                lastResult = beautifyHTML(parsedDoc, options);
                elements.output.innerHTML = highlightHTML(lastResult);
                elements.compressionStats.classList.add('d-none');
                document.getElementById('outputTitle').textContent = 'Beautified Markup';
            } else {
                lastResult = minifyHTML(raw);
                elements.output.textContent = lastResult;
                
                const saved = raw.length - lastResult.length;
                const percent = Math.max(0, ((saved / raw.length) * 100)).toFixed(1);
                elements.savedPercent.textContent = percent + '%';
                elements.compressionStats.classList.remove('d-none');
                document.getElementById('outputTitle').textContent = 'Minified HTML';
            }

            elements.outputContainer.classList.remove('d-none');
            const end = performance.now();
            showToast(`HTML ${mode === 'format' ? 'Formatted' : 'Minified'} in ${(end-start).toFixed(1)}ms`, 'success');
            
            // Trigger analytics hooks (internal dispatch)
            window.dispatchEvent(new CustomEvent('html_tool_run', { detail: { mode, time: end-start } }));
        } catch (e) {
            console.error(e);
            showToast('Error processing HTML', 'danger');
        }
    }

    function runFullAnalysis(doc, raw) {
        // Validation
        const errors = validateHTML(raw);
        if (errors.length > 0) {
            elements.validationArea.classList.remove('d-none');
            elements.validationMsg.innerHTML = errors.map(e => `• ${e}`).join('<br>');
        } else {
            elements.validationArea.classList.add('d-none');
        }

        // DOM Stats
        const all = doc.querySelectorAll('*');
        const tags = new Set();
        const classes = new Set();
        const ids = new Set();
        let maxDepth = 0;

        all.forEach(el => {
            tags.add(el.tagName.toLowerCase());
            if (el.id) ids.add(el.id);
            if (el.classList.length > 0) el.classList.forEach(c => classes.add(c));
            
            let depth = 0, current = el;
            while(current.parentElement) { depth++; current = current.parentElement; }
            maxDepth = Math.max(maxDepth, depth);
        });

        elements.statsCard.classList.remove('d-none');
        elements.statUniqueTags.textContent = tags.size;
        elements.statClasses.textContent = classes.size;
        elements.statIds.textContent = ids.size;
        elements.statDepth.textContent = maxDepth;

        // SEO Analysis
        const seoItems = [];
        const title = doc.querySelector('title');
        seoItems.push({ label: 'Title Tag', val: title ? title.innerText : 'Missing', status: title ? 'success' : 'danger' });
        
        const desc = doc.querySelector('meta[name="description"]');
        seoItems.push({ label: 'Meta Description', val: desc ? 'Present' : 'Missing', status: desc ? 'success' : 'warning' });
        
        const h1s = doc.querySelectorAll('h1').length;
        seoItems.push({ label: 'H1 Tags', val: h1s, status: h1s === 1 ? 'success' : 'warning' });

        elements.seoAnalysis.innerHTML = seoItems.map(item => `
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <span>${item.label}</span>
                <span class="badge bg-${item.status}">${item.val}</span>
            </div>
        `).join('');

        // Accessibility Analysis
        const a11y = [];
        const images = doc.querySelectorAll('img');
        const missingAlt = Array.from(images).filter(img => !img.hasAttribute('alt')).length;
        if (missingAlt > 0) a11y.push({ msg: `${missingAlt} images are missing alt attributes`, type: 'danger' });

        const emptyLinks = Array.from(doc.querySelectorAll('a')).filter(a => !a.innerText.trim() && !a.querySelector('img')).length;
        if (emptyLinks > 0) a11y.push({ msg: `${emptyLinks} empty links detected`, type: 'warning' });

        elements.a11yAnalysis.innerHTML = a11y.length ? a11y.map(item => `
            <div class="list-group-item list-group-item-${item.type} py-2">${item.msg}</div>
        `).join('') : '<div class="p-3 text-success">No critical accessibility issues found.</div>';

        // Optimization
        const opts = [];
        if (maxDepth > 15) opts.push('DOM tree is too deep. Consider flattening your structure.');
        if (doc.querySelectorAll('[style]').length > 5) opts.push('Multiple inline styles detected. Move them to a CSS file.');
        if (doc.querySelectorAll('script').length > 10) opts.push('Large number of script tags. Consider bundling.');

        if (opts.length > 0) {
            elements.optimizationArea.classList.remove('d-none');
            elements.optimizationList.innerHTML = opts.map(o => `<div class="list-group-item py-2">• ${o}</div>`).join('');
        } else {
            elements.optimizationArea.classList.add('d-none');
        }

        // DOM Tree
        renderTree(doc);
    }

    function validateHTML(html) {
        const errors = [];
        const openDivs = (html.match(/<div/g) || []).length;
        const closeDivs = (html.match(/<\/div>/g) || []).length;
        if (openDivs !== closeDivs) errors.push(`Unbalanced div tags: ${openDivs} open vs ${closeDivs} closed.`);

        // Simple check for unclosed tags (regex based is limited, but useful for basic structural validation)
        const voidTags = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];
        // This is a placeholder for more complex logic
        return errors;
    }

    function beautifyHTML(doc, opts) {
        let result = '';
        if (elements.input.value.trim().toLowerCase().startsWith('<!doctype')) {
            result += '<!DOCTYPE html>\n';
        }

        function walk(node, level) {
            const indent = opts.indent.repeat(level);
            
            if (node.nodeType === Node.ELEMENT_NODE) {
                const tag = node.tagName.toLowerCase();
                const isVoid = ['br', 'hr', 'img', 'input', 'link', 'meta', 'base'].includes(tag);
                
                let attrStr = '';
                Array.from(node.attributes).forEach((attr, idx) => {
                    if (opts.attrWrap === 'new') {
                        attrStr += `\n${indent}${opts.indent}${attr.name}="${attr.value}"`;
                    } else {
                        attrStr += ` ${attr.name}="${attr.value}"`;
                    }
                });
                if (opts.attrWrap === 'new' && attrStr) attrStr += `\n${indent}`;

                result += `${indent}<${tag}${attrStr}${isVoid ? ' />' : '>'}`;
                
                if (!isVoid) {
                    const children = Array.from(node.childNodes);
                    const hasOnlyText = children.length === 1 && children[0].nodeType === Node.TEXT_NODE;
                    
                    if (hasOnlyText) {
                        result += children[0].textContent.trim() + `</${tag}>\n`;
                    } else {
                        result += '\n';
                        children.forEach(child => walk(child, level + 1));
                        result += `${indent}</${tag}>\n`;
                    }
                } else {
                    result += '\n';
                }
            } else if (node.nodeType === Node.TEXT_NODE) {
                const text = node.textContent.trim();
                if (text) result += `${indent}${text}\n`;
            } else if (node.nodeType === Node.COMMENT_NODE && opts.preserveComments) {
                result += `${indent}<!--${node.textContent}-->\n`;
            }
        }

        const rawLower = elements.input.value.trim().toLowerCase();
        const isFullDoc = rawLower.includes('<html') || rawLower.includes('<head') || rawLower.includes('<body') || rawLower.includes('<!doctype');

        if (isFullDoc) {
            walk(doc.documentElement, 0);
        } else {
            Array.from(doc.body.childNodes).forEach(node => walk(node, 0));
        }

        return result.trim();
    }

    function minifyHTML(html) {
        return html
            .replace(/<!--[\s\S]*?-->/g, '') // Remove comments
            .replace(/\s+/g, ' ') // Collapse spaces
            .replace(/>\s+</g, '><') // Remove space between tags
            .trim();
    }

    function highlightHTML(html) {
        // 1. First escape special characters
        const escaped = html.replace(/[<>&]/g, m => ({'<':'&lt;','>':'&gt;','&':'&amp;'}[m]));

        // 2. Define regex for HTML tokens
        const regex = /(&lt;!--[\s\S]*?--&gt;)|(&lt;!DOCTYPE html&gt;)|(&lt;\/?[a-zA-Z0-9:]+(?:\s+(?:"[^"]*"|'[^']*'|[^&>])*)?&gt;)|(&amp;[a-zA-Z0-9#]+;)/gi;

        return escaped.replace(regex, (match, comment, doctype, tag, entity) => {
            if (comment) {
                return `<span class="hl-cmt">${comment}</span>`;
            }
            if (doctype) {
                return `<span class="hl-tag">${doctype}</span>`;
            }
            if (tag) {
                const tagNameMatch = tag.match(/^&lt;(\/?[a-zA-Z0-9:]+)/);
                if (tagNameMatch) {
                    const tagName = tagNameMatch[1];
                    const startTag = `<span class="hl-tag">&lt;${tagName}</span>`;
                    let rest = tag.substring(tagNameMatch[0].length, tag.length - 4);
                    
                    // Highlight attributes and values inside the tag
                    rest = rest.replace(/(\s[a-zA-Z0-9-]+)=("([^"]*)"|'([^']*)'|([^\s>]+))/g, 
                        (m, attrName, attrVal, doubleQuoteVal, singleQuoteVal, unquotedVal) => {
                            const val = doubleQuoteVal !== undefined ? doubleQuoteVal : (singleQuoteVal !== undefined ? singleQuoteVal : (unquotedVal || ''));
                            const quote = doubleQuoteVal !== undefined ? '"' : (singleQuoteVal !== undefined ? "'" : '');
                            return ` <span class="hl-attr">${attrName.trim()}</span>=${quote}<span class="hl-val">${val}</span>${quote}`;
                        }
                    );
                    
                    return startTag + rest + '&gt;';
                }
                return tag;
            }
            if (entity) {
                return `<span class="hl-ent">${entity}</span>`;
            }
            return match;
        });
    }

    function renderTree(doc) {
        elements.domTree.innerHTML = '';
        function createNode(el) {
            const div = document.createElement('div');
            div.className = 'tree-node';
            
            const header = document.createElement('div');
            header.className = 'd-flex align-items-center gap-1';
            
            if (el.children.length > 0) {
                const toggle = document.createElement('i');
                toggle.className = 'bi bi-chevron-down tree-toggle';
                toggle.onclick = (e) => {
                    const children = div.querySelector('.tree-children');
                    children.classList.toggle('d-none');
                    toggle.classList.toggle('bi-chevron-right');
                    toggle.classList.toggle('bi-chevron-down');
                };
                header.appendChild(toggle);
            }

            const tag = document.createElement('span');
            tag.className = 'tree-tag';
            tag.textContent = el.tagName.toLowerCase();
            header.appendChild(tag);

            if (el.id) header.innerHTML += `<span class="text-info x-small">#${el.id}</span>`;
            if (el.className) header.innerHTML += `<span class="text-primary x-small">.${Array.from(el.classList).join('.')}</span>`;

            div.appendChild(header);

            if (el.children.length > 0) {
                const children = document.createElement('div');
                children.className = 'tree-children';
                Array.from(el.children).forEach(child => children.appendChild(createNode(child)));
                div.appendChild(children);
            }
            return div;
        }

        const root = doc.body.children.length > 0 ? doc.body : doc.documentElement;
        Array.from(root.children).forEach(child => elements.domTree.appendChild(createNode(child)));
    }

    function filterTree() {
        const q = elements.treeSearch.value.toLowerCase();
        document.querySelectorAll('.tree-node').forEach(node => {
            const text = node.innerText.toLowerCase();
            node.classList.toggle('d-none', q && !text.includes(q));
        });
    }

    function extract(type) {
        if (!parsedDoc) return showToast('Please process HTML first', 'warning');
        let data = '';
        if (type === 'links') {
            data = Array.from(parsedDoc.querySelectorAll('a')).map(a => a.href).join('\n');
        } else if (type === 'images') {
            data = Array.from(parsedDoc.querySelectorAll('img')).map(img => img.src).join('\n');
        } else if (type === 'meta') {
            data = Array.from(parsedDoc.querySelectorAll('meta')).map(m => m.outerHTML).join('\n');
        } else if (type === 'text') {
            data = parsedDoc.body.innerText;
        }

        elements.output.textContent = data;
        lastResult = data;
        elements.outputContainer.classList.remove('d-none');
        elements.compressionStats.classList.add('d-none');
        document.getElementById('outputTitle').textContent = `Extracted ${type}`;
        showToast(`Extracted ${type}`, 'info');
    }

    function updateCounts() {
        const val = elements.input.value;
        const chars = val.length;
        elements.charCount.textContent = chars.toLocaleString();
        elements.lineCount.textContent = (val ? val.split('\n').length : 0).toLocaleString();
        elements.tagCount.textContent = (val.match(/<[a-zA-Z]/g) || []).length;
    }

    function handleFileUpload(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (event) => {
            elements.input.value = event.target.result;
            updateCounts();
            process('format');
        };
        reader.readAsText(file);
    }

    function loadExample() {
        elements.input.value = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Toolzy Landing Page</title>
</head>
<body>
    <header class="navbar">
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#tools">Tools</a></li>
            </ul>
        </nav>
    </header>
    <main id="app">
        <section class="hero">
            <h1>Free Online Utilities</h1>
            <p>Built for developers, by developers.</p>
            <img src="/images/hero.png" alt="Hero Illustration">
        </section>
        <!-- Feature List -->
        <div class="features mt-4">
            <div class="item">Beautify HTML</div>
            <div class="item">Minify Assets</div>
        </div>
    </main>
    <footer>
        <p>&copy; 2026 Toolzy.app</p>
    </footer>
</body>
</html>`;
        updateCounts();
        process('format');
    }

    function clear() {
        elements.input.value = '';
        elements.outputContainer.classList.add('d-none');
        elements.statsCard.classList.add('d-none');
        elements.optimizationArea.classList.add('d-none');
        elements.validationArea.classList.add('d-none');
        elements.domTree.innerHTML = '<div class="text-center text-muted py-4">Format or Analyze HTML to view the DOM tree.</div>';
        updateCounts();
    }

    function copyToClipboard(text, msg) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => showToast(msg, 'success'));
    }

    function download() {
        if (!lastResult) return;
        const blob = new Blob([lastResult], { type: 'text/html' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = document.getElementById('outputTitle').textContent.includes('Minified') ? 'minified.html' : 'formatted.html';
        a.click();
        URL.revokeObjectURL(url);
    }

    return { init };
})();

document.addEventListener('DOMContentLoaded', HTMLTool.init);
</script>
@endpush
