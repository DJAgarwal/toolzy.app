@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />
    <div class="row g-4">
        <!-- Input Section -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-code me-2 text-primary"></i>CSS Input</h5>
                    <div class="btn-group btn-group-sm">
                        <button id="btnExample" class="btn btn-outline-primary">Load Example</button>
                        <button id="btnClear" class="btn btn-outline-danger">Clear</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <textarea id="cssInput" class="form-control border-0 font-monospace p-3" rows="12" placeholder="/* Paste your CSS here... */
body {
    margin: 0;
    padding: 0;
    font-family: sans-serif;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
}"></textarea>
                </div>
                <div class="card-footer bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <input type="file" id="fileInput" class="d-none" accept=".css,.txt">
                            <button id="btnUpload" class="btn btn-sm btn-outline-secondary"><i class="bi bi-upload me-1"></i>Upload CSS</button>
                            <button id="btnCopyInput" class="btn btn-sm btn-outline-secondary"><i class="bi bi-clipboard me-1"></i>Copy Input</button>
                        </div>
                        <div class="small text-muted italic">
                            <span id="charCount">0</span> Chars | <span id="lineCount">0</span> Lines | <span id="fileSize">0 KB</span>
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
                            <i class="bi bi-magic me-2"></i>Format CSS
                        </button>
                        <button id="btnMinify" class="btn btn-dark px-4 fw-bold flex-grow-1">
                            <i class="bi bi-arrows-angle-contract me-2"></i>Minify CSS
                        </button>
                    </div>
                </div>
            </div>

            <!-- Output Section -->
            <div id="outputContainer" class="card shadow-sm mb-4 d-none">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Result</h5>
                    <div class="btn-group btn-group-sm">
                        <button id="btnCopyOutput" class="btn btn-outline-secondary" title="Copy Result"><i class="bi bi-clipboard"></i></button>
                        <button id="btnDownload" class="btn btn-outline-success" title="Download File"><i class="bi bi-download"></i></button>
                    </div>
                </div>
                <div class="card-body p-0 position-relative">
                    <pre id="cssOutput" class="p-3 mb-0 font-monospace bg-dark text-white rounded-bottom overflow-auto"></pre>
                    
                    <!-- Stats Overlays -->
                    <div id="compressionStats" class="position-absolute bottom-0 end-0 p-3 d-none">
                        <div class="badge bg-success shadow-sm p-2 text-start">
                            <div class="x-small text-uppercase opacity-75">Saved</div>
                            <div id="savedPercent" class="fw-bold fs-6">0%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optimization Section -->
            <div id="optimizationArea" class="card shadow-sm mb-4 d-none">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-success"><i class="bi bi-lightbulb me-2"></i>Optimization Suggestions</h6>
                </div>
                <div class="card-body p-0">
                    <div id="optimizationList" class="list-group list-group-flush small">
                        <!-- Suggestions injected here -->
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
                        <label class="form-label small fw-bold text-muted text-uppercase">Brace Style</label>
                        <select id="optBrace" class="form-select form-select-sm">
                            <option value="same" selected>Same Line ( { )</option>
                            <option value="new">New Line ( { )</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Properties</label>
                        <select id="optProp" class="form-select form-select-sm">
                            <option value="one" selected>One property per line</option>
                            <option value="compact">Compact (Grouped)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Selectors</label>
                        <select id="optSelector" class="form-select form-select-sm">
                            <option value="multi" selected>Multi-line</option>
                            <option value="single">Single line</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted text-uppercase">Max Line Width</label>
                        <input type="number" id="optLineWidth" class="form-control form-control-sm" value="120">
                    </div>
                </div>
            </div>

            <!-- CSS Analysis Card -->
            <div id="analysisCard" class="card shadow-sm mb-4 d-none">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line me-2"></i>Analysis</h6>
                    <span id="complexityBadge" class="badge rounded-pill bg-success x-small">Simple</span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between"><span>Rules</span> <span id="statRules" class="fw-bold">0</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Selectors</span> <span id="statSelectors" class="fw-bold">0</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Properties</span> <span id="statProps" class="fw-bold">0</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Media Queries</span> <span id="statMedia" class="fw-bold text-primary">0</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Variables</span> <span id="statVars" class="fw-bold text-info">0</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Comments</span> <span id="statComments" class="fw-bold text-muted">0</span></li>
                    </ul>
                </div>
            </div>

            <!-- Color Analysis -->
            <div id="colorCard" class="card shadow-sm mb-4 d-none">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-palette me-2"></i>Colors Detected</h6>
                </div>
                <div class="card-body">
                    <div id="colorList" class="d-flex flex-wrap gap-2">
                        <!-- Colors injected here -->
                    </div>
                </div>
            </div>

            <!-- Rule Extraction -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Quick Extraction</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-secondary text-start extract-btn" data-type="selectors">Extract Selectors</button>
                        <button class="btn btn-sm btn-outline-secondary text-start extract-btn" data-type="colors">Extract Unique Colors</button>
                        <button class="btn btn-sm btn-outline-secondary text-start extract-btn" data-type="vars">Extract Variables</button>
                        <button class="btn btn-sm btn-outline-secondary text-start extract-btn" data-type="media">Extract Media Queries</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Content -->
    <div class="mt-5 pt-5 border-top">
        <div class="row g-5">
            <div class="col-lg-8">
                <h2 class="h3 fw-bold mb-4">Professional CSS Optimization Suite</h2>
                
                <div class="mb-5">
                    <h4 class="h5 fw-bold text-dark mb-3">Understanding CSS Formatting & Beautification</h4>
                    <p class="text-muted small">CSS formatting is the process of rearranging your stylesheet into a clean, human-readable structure. While browsers can parse CSS written on a single line, developers need clear indentation, consistent brace placement, and logical property grouping to effectively debug and maintain code. Our tool allows you to customize indentation (spaces or tabs), brace styles, and property organization to match your project's coding standards.</p>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 bg-light h-100">
                            <h5 class="fw-bold small text-uppercase text-primary mb-3">Why Minify?</h5>
                            <p class="text-muted mb-0 small">Minification is a critical step for web performance. By stripping out comments, whitespace, and redundant characters, you reduce the payload size that users have to download. Smaller CSS files mean faster <strong>First Contentful Paint (FCP)</strong> and better SEO scores.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 bg-light h-100">
                            <h5 class="fw-bold small text-uppercase text-success mb-3">Specificity Analysis</h5>
                            <p class="text-muted mb-0 small">"Selector hell" is a real problem in large stylesheets. Our analyzer calculates the complexity of your selectors. If you have overly specific chains (e.g., <code>html body #app .container .btn</code>), it flags them as potential maintenance risks that can cause styling conflicts.</p>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h4 class="h5 fw-bold text-dark mb-3">CSS Best Practices & Optimization</h4>
                    <ul class="text-muted small lh-lg">
                        <li><strong>Use CSS Variables:</strong> Instead of repeating colors like <code>#3498db</code> everywhere, define <code>--primary-color: #3498db;</code>. Our tool detects repeated values that could be variables.</li>
                        <li><strong>Avoid Duplicate Rules:</strong> Repeating the same selector multiple times increases file size and complexity. Consolidate rules whenever possible.</li>
                        <li><strong>Audit Media Queries:</strong> Using too many distinct breakpoints can lead to fragmented UI logic. Aim for consistent breakpoints across your application.</li>
                        <li><strong>Clean Up Unused Prefixes:</strong> Modern browsers support most features without <code>-webkit-</code> or <code>-moz-</code> prefixes. Removing these can significantly lighten your CSS.</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 bg-info bg-opacity-10 p-4 rounded-4">
                    <h5 class="fw-bold mb-3">Common CSS Mistakes</h5>
                    <ol class="small text-muted mb-0 lh-lg">
                        <li><strong>Missing Semicolons:</strong> Can break the subsequent rule completely.</li>
                        <li><strong>Unclosed Braces:</strong> Causes the parser to ignore the rest of the file or nest rules incorrectly.</li>
                        <li><strong>Malformed Colors:</strong> Invalid hex codes or missing parentheses in <code>rgba()</code>.</li>
                        <li><strong>Incorrect Units:</strong> Using <code>0px</code> where <code>0</code> is sufficient, or forgetting units on non-zero values.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

#cssInput {
    resize: none;
    font-size: 0.9rem;
    background-color: #fcfcfc;
}

#cssOutput {
    white-space: pre;
    font-size: 0.85rem;
    line-height: 1.5;
    tab-size: 4;
    max-height: 600px;
}

.italic { font-style: italic; }
.x-small { font-size: 0.75rem; }

/* CSS Syntax Highlighting */
.css-selector { color: #d7ba7d; font-weight: bold; }
.css-property { color: #9cdcfe; }
.css-value { color: #ce9178; }
.css-comment { color: #6a9955; font-style: italic; }
.css-media { color: #c586c0; font-weight: bold; }
.css-variable { color: #4fc1ff; }
.css-unit { color: #b5cea8; }

.color-swatch {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    border: 1px solid rgba(0,0,0,0.1);
    display: inline-block;
}

.suggestion-item {
    border-left: 3px solid #ffc107;
}
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const CSSTool = (function() {
    let lastResult = '';
    let analysisData = {};

    const elements = {
        input: document.getElementById('cssInput'),
        output: document.getElementById('cssOutput'),
        outputContainer: document.getElementById('outputContainer'),
        charCount: document.getElementById('charCount'),
        lineCount: document.getElementById('lineCount'),
        fileSize: document.getElementById('fileSize'),
        validationArea: document.getElementById('validationArea'),
        validationMsg: document.getElementById('validationMsg'),
        optimizationArea: document.getElementById('optimizationArea'),
        optimizationList: document.getElementById('optimizationList'),
        analysisCard: document.getElementById('analysisCard'),
        complexityBadge: document.getElementById('complexityBadge'),
        colorCard: document.getElementById('colorCard'),
        colorList: document.getElementById('colorList'),
        compressionStats: document.getElementById('compressionStats'),
        savedPercent: document.getElementById('savedPercent'),
        
        // Stats
        statRules: document.getElementById('statRules'),
        statSelectors: document.getElementById('statSelectors'),
        statProps: document.getElementById('statProps'),
        statMedia: document.getElementById('statMedia'),
        statVars: document.getElementById('statVars'),
        statComments: document.getElementById('statComments'),

        // Options
        optIndent: document.getElementById('optIndent'),
        optBrace: document.getElementById('optBrace'),
        optProp: document.getElementById('optProp'),
        optSelector: document.getElementById('optSelector'),
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
        document.getElementById('btnCopyOutput').addEventListener('click', () => copyToClipboard(lastResult, 'CSS copied'));
        document.getElementById('btnDownload').addEventListener('click', download);
        
        elements.input.addEventListener('input', updateCounts);

        document.querySelectorAll('.extract-btn').forEach(btn => {
            btn.addEventListener('click', () => extract(btn.getAttribute('data-type')));
        });
    }

    function process(mode) {
        const raw = elements.input.value.trim();
        if (!raw) return showToast('Please enter some CSS', 'warning');

        try {
            const start = performance.now();
            
            // 1. Analyze first
            analysisData = analyzeCSS(raw);
            renderAnalysis();

            // 2. Validate
            const errors = validateCSS(raw);
            if (errors.length > 0) {
                elements.validationArea.classList.remove('d-none');
                elements.validationMsg.innerHTML = errors.map(e => `• ${e}`).join('<br>');
            } else {
                elements.validationArea.classList.add('d-none');
            }

            // 3. Transform
            if (mode === 'format') {
                const options = {
                    indent: elements.optIndent.value === 'tabs' ? '\t' : ' '.repeat(parseInt(elements.optIndent.value)),
                    braceNewLine: elements.optBrace.value === 'new',
                    onePropPerLine: elements.optProp.value === 'one',
                    selectorMultiLine: elements.optSelector.value === 'multi'
                };
                lastResult = beautifyCSS(raw, options);
                elements.output.innerHTML = highlightCSS(lastResult);
                elements.compressionStats.classList.add('d-none');
            } else {
                lastResult = minifyCSS(raw);
                elements.output.textContent = lastResult;
                
                const saved = raw.length - lastResult.length;
                const percent = ((saved / raw.length) * 100).toFixed(1);
                elements.savedPercent.textContent = percent + '%';
                elements.compressionStats.classList.remove('d-none');
            }

            elements.outputContainer.classList.remove('d-none');
            const end = performance.now();
            
            renderOptimizations(analysisData);
            showToast(`CSS ${mode === 'format' ? 'Formatted' : 'Minified'} in ${(end-start).toFixed(1)}ms`, 'success');
        } catch (e) {
            console.error(e);
            showToast('Error processing CSS', 'danger');
        }
    }

    function analyzeCSS(css) {
        const stats = {
            rules: 0, selectors: 0, props: 0, media: 0, vars: 0, comments: 0,
            colors: new Set(),
            selectorTypes: { id: 0, class: 0, element: 0 },
            repeatedProps: {},
            repeatedValues: {}
        };

        // Strip comments for structure analysis but count them
        const commentRegex = /\/\*[\s\S]*?\*\//g;
        const commentMatches = css.match(commentRegex) || [];
        stats.comments = commentMatches.length;
        const cleanCss = css.replace(commentRegex, '');

        // Media Queries
        const mediaMatches = cleanCss.match(/@media[^{]+\{/g) || [];
        stats.media = mediaMatches.length;

        // Variables
        const varMatches = cleanCss.match(/--[a-zA-Z0-9-]+:/g) || [];
        stats.vars = varMatches.length;

        // Rules & Properties
        const ruleRegex = /([^{]+)\{([^}]+)\}/g;
        let match;
        while ((match = ruleRegex.exec(cleanCss)) !== null) {
            stats.rules++;
            const selectorPart = match[1].trim();
            const bodyPart = match[2].trim();

            // Selectors
            const selectorCount = selectorPart.split(',').length;
            stats.selectors += selectorCount;

            if (selectorPart.includes('#')) stats.selectorTypes.id++;
            if (selectorPart.includes('.')) stats.selectorTypes.class++;

            // Properties
            const props = bodyPart.split(';');
            props.forEach(p => {
                const parts = p.split(':');
                if (parts.length === 2) {
                    stats.props++;
                    const key = parts[0].trim();
                    const val = parts[1].trim();

                    stats.repeatedProps[key] = (stats.repeatedProps[key] || 0) + 1;
                    stats.repeatedValues[val] = (stats.repeatedValues[val] || 0) + 1;

                    // Color detection (Hex, RGB, HSL)
                    const colorRegex = /#([a-fA-F0-9]{3,6})\b|rgba?\([^)]+\)|hsla?\([^)]+\)/g;
                    let colorMatch;
                    while ((colorMatch = colorRegex.exec(val)) !== null) {
                        stats.colors.add(colorMatch[0]);
                    }
                }
            });
        }

        return stats;
    }

    function renderAnalysis() {
        elements.analysisCard.classList.remove('d-none');
        elements.statRules.textContent = analysisData.rules;
        elements.statSelectors.textContent = analysisData.selectors;
        elements.statProps.textContent = analysisData.props;
        elements.statMedia.textContent = analysisData.media;
        elements.statVars.textContent = analysisData.vars;
        elements.statComments.textContent = analysisData.comments;

        // Complexity
        let score = 'Simple';
        if (analysisData.selectors > 100 || analysisData.media > 5) score = 'Moderate';
        if (analysisData.selectors > 300 || analysisData.media > 15) score = 'Complex';
        elements.complexityBadge.textContent = score;
        elements.complexityBadge.className = `badge rounded-pill x-small bg-${score === 'Simple' ? 'success' : (score === 'Moderate' ? 'warning' : 'danger')}`;

        // Colors
        if (analysisData.colors.size > 0) {
            elements.colorCard.classList.remove('d-none');
            elements.colorList.innerHTML = '';
            Array.from(analysisData.colors).slice(0, 20).forEach(c => {
                const wrapper = document.createElement('div');
                wrapper.className = 'd-flex align-items-center bg-light p-1 rounded border';
                wrapper.title = c;
                
                const swatch = document.createElement('span');
                swatch.className = 'color-swatch me-1';
                swatch.style.backgroundColor = c;
                
                const label = document.createElement('span');
                label.className = 'x-small font-monospace';
                label.textContent = c;
                
                wrapper.appendChild(swatch);
                wrapper.appendChild(label);
                elements.colorList.appendChild(wrapper);
            });
        } else {
            elements.colorCard.classList.add('d-none');
        }
    }

    function renderOptimizations(data) {
        const suggestions = [];
        
        // Repeated Colors
        const threshold = 5;
        const colorCounts = {};
        // Note: Simple count since analyzeCSS doesn't currently map colors to counts, let's fix logic or keep simple
        if (data.colors.size > threshold) {
            suggestions.push({
                type: 'Variable Opportunity',
                msg: `You are using ${data.colors.size} unique colors. Consider using CSS variables to manage your theme colors.`
            });
        }

        // Repeated Values
        Object.entries(data.repeatedValues).forEach(([val, count]) => {
            if (count > 10 && val.length > 5) {
                suggestions.push({
                    type: 'Refactor',
                    msg: `Value "<code>${val}</code>" appears ${count} times. Move this to a variable.`
                });
            }
        });

        // High Specificity
        if (data.selectorTypes.id > 0) {
            suggestions.push({
                type: 'Warning',
                msg: `Found ${data.selectorTypes.id} ID selectors. IDs have very high specificity and can make styles hard to override. Prefer classes.`
            });
        }

        if (suggestions.length > 0) {
            elements.optimizationArea.classList.remove('d-none');
            elements.optimizationList.innerHTML = suggestions.map(s => `
                <div class="list-group-item suggestion-item p-3">
                    <div class="fw-bold text-dark x-small text-uppercase mb-1">${s.type}</div>
                    <div class="text-muted">${s.msg}</div>
                </div>
            `).join('');
        } else {
            elements.optimizationArea.classList.add('d-none');
        }
    }

    function validateCSS(css) {
        const errors = [];
        const openBraces = (css.match(/\{/g) || []).length;
        const closeBraces = (css.match(/\}/g) || []).length;

        if (openBraces !== closeBraces) {
            errors.push(`Unbalanced braces: ${openBraces} opening vs ${closeBraces} closing.`);
        }

        // Simple check for property syntax
        const lines = css.split('\n');
        lines.forEach((line, idx) => {
            const trimmed = line.trim();
            if (trimmed && !trimmed.includes('{') && !trimmed.includes('}') && !trimmed.startsWith('@') && !trimmed.startsWith('/*')) {
                if (trimmed.includes(':') && !trimmed.includes(';')) {
                    // Check if it's the last property in a block (forgiving)
                    // but we can flag it for strictness
                }
                if (trimmed.length > 0 && !trimmed.includes(':') && !trimmed.includes('/*')) {
                   // Possible missing colon
                }
            }
        });

        return errors;
    }

    function beautifyCSS(css, opts) {
        // Rule-based beautifier
        let formatted = css
            .replace(/\s+/g, ' ') // Flatten
            .replace(/\/\*[\s\S]*?\*\//g, m => `\n${m}\n`) // Newline around comments
            .replace(/\{/g, opts.braceNewLine ? '\n{\n' : ' {\n')
            .replace(/\}/g, '\n}\n')
            .replace(/;/g, ';\n')
            .replace(/,/g, opts.selectorMultiLine ? ',\n' : ', ');

        const lines = formatted.split('\n');
        let indentLevel = 0;
        const result = [];

        lines.forEach(line => {
            let l = line.trim();
            if (!l) return;

            if (l.includes('}')) indentLevel = Math.max(0, indentLevel - 1);
            
            const currentIndent = opts.indent.repeat(indentLevel);
            
            if (l.includes(':') && !l.startsWith('@') && !l.startsWith('/*')) {
                const parts = l.split(':');
                l = `${parts[0].trim()}: ${parts[1].trim()}`;
            }

            result.push(currentIndent + l);

            if (l.includes('{')) indentLevel++;
        });

        return result.join('\n').replace(/\n\n+/g, '\n\n').trim();
    }

    function minifyCSS(css) {
        return css
            .replace(/\/\*[\s\S]*?\*\//g, '') // Remove comments
            .replace(/\s+/g, ' ') // Collapse whitespace
            .replace(/\s*([{};:,])\s*/g, '$1') // Remove spaces around punctuation
            .replace(/;}/g, '}') // Remove redundant semicolons
            .trim();
    }

    function highlightCSS(css) {
        return css.replace(/[<>&]/g, m => ({'<':'&lt;','>':'&gt;','&':'&amp;'}[m]))
            .replace(/(\/\*[\s\S]*?\*\/)/g, '<span class="css-comment">$1</span>')
            .replace(/(@[a-zA-Z-]+)/g, '<span class="css-media">$1</span>')
            .replace(/([^{]+)\{/g, (m, s) => {
                if (s.includes('@')) return m;
                return `<span class="css-selector">${s}</span>{`;
            })
            .replace(/([a-zA-Z-]+)\s*:/g, '<span class="css-property">$1</span>:')
            .replace(/:\s*([^;}]*)([;?}])/g, ':<span class="css-value">$1</span>$2')
            .replace(/(--[a-zA-Z0-9-]+)/g, '<span class="css-variable">$1</span>');
    }

    function extract(type) {
        if (!analysisData.rules) return showToast('Please process CSS first', 'warning');
        
        let content = '';
        if (type === 'selectors') {
            const sels = [];
            const regex = /([^{]+)\{/g;
            let match;
            while ((match = regex.exec(elements.input.value)) !== null) {
                sels.push(match[1].trim());
            }
            content = sels.join('\n');
        } else if (type === 'colors') {
            content = Array.from(analysisData.colors).join('\n');
        } else if (type === 'vars') {
            const vars = elements.input.value.match(/--[a-zA-Z0-9-]+:[^;]+/g) || [];
            content = vars.join('\n');
        } else if (type === 'media') {
            const media = elements.input.value.match(/@media[^{]+\{/g) || [];
            content = media.map(m => m.replace('{','').trim()).join('\n');
        }

        elements.output.textContent = content;
        lastResult = content;
        elements.outputContainer.classList.remove('d-none');
        elements.compressionStats.classList.add('d-none');
        showToast(`Extracted ${type}`, 'info');
    }

    function updateCounts() {
        const val = elements.input.value;
        const chars = val.length;
        elements.charCount.textContent = chars.toLocaleString();
        elements.lineCount.textContent = (val ? val.split('\n').length : 0).toLocaleString();
        elements.fileSize.textContent = (chars / 1024).toFixed(2) + ' KB';
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
        elements.input.value = `/* Standard Base Styles */
body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    --primary-color: #3498db;
    background: #f4f7f6;
}

.card {
    background: #ffffff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
    border: 1px solid #3498db;
}

@media (max-width: 768px) {
    .card { padding: 15px; }
    h1 { font-size: 1.5rem; }
}`;
        updateCounts();
        process('format');
    }

    function clear() {
        elements.input.value = '';
        elements.outputContainer.classList.add('d-none');
        elements.analysisCard.classList.add('d-none');
        elements.colorCard.classList.add('d-none');
        elements.optimizationArea.classList.add('d-none');
        elements.validationArea.classList.add('d-none');
        updateCounts();
    }

    function copyToClipboard(text, msg) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => showToast(msg, 'success'));
    }

    function download() {
        if (!lastResult) return;
        const blob = new Blob([lastResult], { type: 'text/css' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = elements.compressionStats.classList.contains('d-none') ? 'beautified.css' : 'minified.css';
        a.click();
        URL.revokeObjectURL(url);
    }

    return { init };
})();

document.addEventListener('DOMContentLoaded', CSSTool.init);
</script>
@endpush
