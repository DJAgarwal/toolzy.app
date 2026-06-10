@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />

    <div class="row g-4">
        <!-- Input Section -->
        <div class="col-lg-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-database-fill-gear me-2 text-primary"></i>SQL Input</h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary btn-example">Example</button>
                        <button class="btn btn-outline-danger btn-clear">Clear</button>
                    </div>
                </div>
                <div class="card-body p-0 position-relative">
                    <textarea id="sqlInput" class="form-control border-0 font-monospace p-3" rows="12" placeholder="-- Paste your SQL here...
SELECT id, name FROM users WHERE active = 1 ORDER BY name;"></textarea>
                </div>
                <div class="card-footer bg-light py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <input type="file" id="fileInput" class="d-none" accept=".sql,.txt">
                            <button class="btn btn-sm btn-outline-secondary btn-upload-trigger" data-target="fileInput"><i class="bi bi-upload me-1"></i>Upload SQL</button>
                        </div>
                        <div class="small text-muted italic">
                            <span id="charCount">0</span> Chars | <span id="wordCount">0</span> Words
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card mb-4 shadow-sm border-primary border-opacity-25">
                <div class="card-body py-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <select id="dialect" class="form-select">
                                <option value="sql">Standard SQL (ANSI)</option>
                                <option value="mysql">MySQL / MariaDB</option>
                                <option value="postgresql">PostgreSQL</option>
                                <option value="sqlite">SQLite</option>
                                <option value="sqlserver">T-SQL (SQL Server)</option>
                                <option value="oracle">Oracle SQL</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex gap-2">
                                <button id="btnFormat" class="btn btn-primary px-4 fw-bold flex-grow-1">
                                    <i class="bi bi-magic me-2"></i>Format SQL
                                </button>
                                <button id="btnMinify" class="btn btn-outline-secondary px-3">
                                    <i class="bi bi-arrows-angle-contract me-2"></i>Minify
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Output Section -->
            <div id="outputContainer" class="card mb-4 shadow-sm d-none">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Formatted Result</h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary btn-copy" title="Copy"><i class="bi bi-clipboard"></i></button>
                        <button class="btn btn-outline-success btn-download" title="Download"><i class="bi bi-download"></i></button>
                    </div>
                </div>
                <div class="card-body p-0 position-relative">
                    <pre id="sqlOutput" class="p-3 mb-0 font-monospace bg-dark text-white rounded-bottom"></pre>
                </div>
            </div>

            <!-- Query Analysis & Explanation -->
            <div id="analysisArea" class="d-none">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-info border-opacity-25">
                            <div class="card-header bg-info bg-opacity-10 py-3">
                                <h6 class="mb-0 fw-bold text-info"><i class="bi bi-info-circle me-2"></i>Query Explanation</h6>
                            </div>
                            <div class="card-body small text-muted" id="queryExplain">
                                <!-- Explanation text -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-activity me-2 text-primary"></i>Structural Validation</h6>
                            </div>
                            <div class="card-body small" id="validationResult">
                                <!-- Validation info -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Options -->
        <div class="col-lg-4">
            <!-- Formatting Controls -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i>Formatting Rules</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Indentation</label>
                        <select id="indentSize" class="form-select form-select-sm">
                            <option value="2">2 Spaces</option>
                            <option value="4" selected>4 Spaces</option>
                            <option value="tabs">Tabs</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Keyword Casing</label>
                        <select id="keywordCase" class="form-select form-select-sm">
                            <option value="upper" selected>UPPERCASE</option>
                            <option value="lower">lowercase</option>
                            <option value="preserve">Preserve</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Identifier Casing</label>
                        <select id="identifierCase" class="form-select form-select-sm">
                            <option value="preserve" selected>Preserve</option>
                            <option value="lower">lowercase</option>
                            <option value="upper">UPPERCASE</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="commaLeading">
                        <label class="form-check-label small" for="commaLeading">Leading Commas</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="denseConditions" checked>
                        <label class="form-check-label small" for="denseConditions">Dense Conditions</label>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div id="statsCard" class="card mb-4 shadow-sm d-none">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2"></i>Query Stats</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Statement Type</span>
                            <span id="statType" class="badge bg-primary rounded-pill">SELECT</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Tables Found</span>
                            <span id="statTables" class="fw-bold">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Joins</span>
                            <span id="statJoins" class="fw-bold">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Complexity</span>
                            <span id="statComplexity" class="text-success fw-bold">Simple</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Style Guide Tips -->
            <div class="card mb-4 shadow-sm border-warning border-opacity-25 bg-warning bg-opacity-10">
                <div class="card-body py-3">
                    <h6 class="fw-bold text-warning-emphasis mb-2 small text-uppercase">SQL Tip</h6>
                    <p class="small text-muted mb-0" id="sqlTip">Use UPPERCASE for keywords like SELECT, FROM, and WHERE to make them stand out from table and column names.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Content -->
    <div class="mt-5 border-top pt-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <h2 class="h4 fw-bold mb-4">Mastering SQL Readability & Formatting</h2>
                <div class="row g-4 text-muted small">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Why Format Your SQL?</h6>
                        <p>SQL is a declarative language, but it often ends up as a "wall of text" in codebases or database managers. Consistent formatting makes it easier to spot missing joins, logical errors in WHERE clauses, and performance bottlenecks. A well-formatted query is essentially self-documenting code.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Dangerous Query Detection</h6>
                        <p>Our tool automatically flags potentially dangerous operations. For example, if you provide a <code>DELETE</code> or <code>UPDATE</code> statement without a <code>WHERE</code> clause, we'll show a prominent warning. This is a common mistake that can lead to unintended data loss.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">Indentation Standards</h6>
                        <p>While some prefer 2 spaces and others prefer tabs, the key is consistency. Standard practice is to indent columns under <code>SELECT</code> and conditions under <code>JOIN</code> or <code>WHERE</code> to visually represent the hierarchy of the query.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark">SQL Minification</h6>
                        <p>While formatting is for humans, minification is for machines. Removing comments and whitespace can reduce payload size when sending queries over a network or embedding them in application configuration files.</p>
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

#sqlInput {
    resize: none;
    font-size: 0.9rem;
    line-height: 1.5;
    background-color: #fcfcfc;
}

#sqlOutput {
    white-space: pre-wrap;
    word-break: break-all;
    font-size: 0.9rem;
    line-height: 1.6;
    border-radius: 0 0 0.375rem 0.375rem;
}

.italic { font-style: italic; }

.sql-keyword { color: #569cd6; font-weight: bold; }
.sql-string { color: #ce9178; }
.sql-number { color: #b5cea8; }
.sql-function { color: #dcdcaa; }
.sql-comment { color: #6a9955; }
.sql-operator { color: #d4d4d4; }

.dangerous-warning {
    border-left: 4px solid #dc3545;
    padding-left: 10px;
    color: #dc3545;
    font-weight: bold;
}
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const SQLTool = (function() {
    const keywords = [
        'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'FROM', 'WHERE', 'AND', 'OR', 'JOIN', 'LEFT', 'RIGHT', 'INNER', 'OUTER', 
        'GROUP BY', 'ORDER BY', 'HAVING', 'LIMIT', 'OFFSET', 'UNION', 'ALL', 'AS', 'ON', 'IN', 'INTO', 'VALUES', 
        'SET', 'CREATE', 'TABLE', 'ALTER', 'DROP', 'TRUNCATE', 'INDEX', 'DATABASE', 'USE', 'DISTINCT', 'CASE', 'WHEN', 
        'THEN', 'ELSE', 'END', 'WITH', 'BY', 'ASC', 'DESC', 'NULL', 'IS', 'NOT', 'LIKE', 'BETWEEN', 'EXISTS', 'COUNT', 
        'SUM', 'MIN', 'MAX', 'AVG', 'CAST', 'COALESCE', 'IF', 'PROCEDURE', 'FUNCTION', 'BEGIN', 'COMMIT', 'ROLLBACK'
    ];

    const elements = {
        input: document.getElementById('sqlInput'),
        output: document.getElementById('sqlOutput'),
        outputContainer: document.getElementById('outputContainer'),
        analysisArea: document.getElementById('analysisArea'),
        queryExplain: document.getElementById('queryExplain'),
        validationResult: document.getElementById('validationResult'),
        statsCard: document.getElementById('statsCard'),
        statType: document.getElementById('statType'),
        statTables: document.getElementById('statTables'),
        statJoins: document.getElementById('statJoins'),
        statComplexity: document.getElementById('statComplexity'),
        charCount: document.getElementById('charCount'),
        wordCount: document.getElementById('wordCount')
    };

    function init() {
        bindEvents();
    }

    function bindEvents() {
        document.getElementById('btnFormat').addEventListener('click', format);
        document.getElementById('btnMinify').addEventListener('click', minify);
        elements.input.addEventListener('input', updateCounts);
        document.getElementById('fileInput').addEventListener('change', handleFileUpload);
        
        // Ctrl + Enter shortcut
        elements.input.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                format();
            }
        });

        // Example button
        document.querySelectorAll('.btn-example').forEach(btn => {
            btn.addEventListener('click', loadExample);
        });

        // Clear button
        document.querySelectorAll('.btn-clear').forEach(btn => {
            btn.addEventListener('click', clear);
        });

        // Upload triggers
        document.querySelectorAll('.btn-upload-trigger').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById(btn.getAttribute('data-target')).click();
            });
        });

        // Copy button
        document.querySelectorAll('.btn-copy').forEach(btn => {
            btn.addEventListener('click', copyOutput);
        });

        // Download button
        document.querySelectorAll('.btn-download').forEach(btn => {
            btn.addEventListener('click', download);
        });
    }

    function format() {
        const raw = elements.input.value.trim();
        if (!raw) return showToast('Please enter some SQL', 'warning');

        const options = {
            indent: document.getElementById('indentSize').value,
            keywordCase: document.getElementById('keywordCase').value,
            identifierCase: document.getElementById('identifierCase').value,
            commaLeading: document.getElementById('commaLeading').checked,
            dense: document.getElementById('denseConditions').checked
        };

        const formatted = beautifySQL(raw, options);
        elements.output.innerHTML = highlightSQL(formatted);
        elements.outputContainer.classList.remove('d-none');
        
        analyze(raw);
        showToast('SQL Formatted successfully', 'success');
    }

    function minify() {
        const raw = elements.input.value.trim();
        if (!raw) return;

        const minified = raw
            .replace(/\/\*[\s\S]*?\*\/|([^:]|^)\/\/.*|(--.*)/g, '') // Remove comments
            .replace(/\s+/g, ' ') // Collapse whitespace
            .trim();

        elements.output.textContent = minified;
        elements.outputContainer.classList.remove('d-none');
        analyze(minified);
        showToast('SQL Minified', 'info');
    }

    function beautifySQL(sql, opts) {
        let formatted = sql;
        
        // 1. Remove extra whitespace and normalized comments
        formatted = formatted.replace(/\s+/g, ' ').trim();

        // 2. Apply line breaks and indentation
        const breakKeywords = ['SELECT', 'FROM', 'WHERE', 'AND', 'OR', 'JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'INNER JOIN', 'GROUP BY', 'ORDER BY', 'HAVING', 'LIMIT', 'INSERT', 'UPDATE', 'DELETE', 'SET', 'VALUES', 'UNION'];
        const indentStr = opts.indent === 'tabs' ? '\t' : ' '.repeat(parseInt(opts.indent));
        
        let lines = [];
        const parts = formatted.split(/\b(SELECT|FROM|WHERE|AND|OR|JOIN|LEFT JOIN|RIGHT JOIN|INNER JOIN|GROUP BY|ORDER BY|HAVING|LIMIT|INSERT|UPDATE|DELETE|SET|VALUES|UNION)\b/gi);
        
        for (let i = 0; i < parts.length; i++) {
            let part = parts[i] ? parts[i].trim() : '';
            if (!part) continue;

            const upperPart = part.toUpperCase();
            if (breakKeywords.includes(upperPart)) {
                let kw = opts.keywordCase === 'upper' ? upperPart : (opts.keywordCase === 'lower' ? part.toLowerCase() : part);
                lines.push(kw);
            } else {
                // Content between keywords
                if (part.includes(',') && !opts.dense) {
                    const columns = part.split(',');
                    columns.forEach((col, idx) => {
                        let c = col.trim();
                        if (opts.identifierCase === 'upper') c = c.toUpperCase();
                        else if (opts.identifierCase === 'lower') c = c.toLowerCase();

                        if (opts.commaLeading && idx > 0) {
                            lines.push(indentStr + ', ' + c);
                        } else {
                            lines.push(indentStr + c + (idx < columns.length - 1 && !opts.commaLeading ? ',' : ''));
                        }
                    });
                } else {
                    let c = part;
                    if (opts.identifierCase === 'upper') c = c.toUpperCase();
                    else if (opts.identifierCase === 'lower') c = c.toLowerCase();
                    lines.push(indentStr + c);
                }
            }
        }

        return lines.join('\n');
    }

    function highlightSQL(sql) {
        let highlighted = sql.replace(/[<>&]/g, m => ({'<':'&lt;','>':'&gt;','&':'&amp;'}[m]));
        
        // Match keywords (case insensitive)
        keywords.forEach(kw => {
            const regex = new RegExp(`\\b${kw}\\b`, 'gi');
            highlighted = highlighted.replace(regex, (match) => `<span class="sql-keyword">${match}</span>`);
        });

        return highlighted
            .replace(/'([^']*)'/g, '<span class="sql-string">\'$1\'</span>')
            .replace(/\b(\d+)\b/g, '<span class="sql-number">$1</span>')
            .replace(/(--.*)/g, '<span class="sql-comment">$1</span>')
            .replace(/(\/\*[\s\S]*?\*\/)/g, '<span class="sql-comment">$1</span>');
    }

    function analyze(sql) {
        elements.analysisArea.classList.remove('d-none');
        elements.statsCard.classList.remove('d-none');

        // 1. Detect Type
        const typeMatch = sql.match(/^\s*(SELECT|INSERT|UPDATE|DELETE|CREATE|DROP|ALTER|TRUNCATE)/i);
        const type = typeMatch ? typeMatch[1].toUpperCase() : 'OTHER';
        elements.statType.textContent = type;

        // 2. Count Tables & Joins
        const tables = [...new Set(sql.matchAll(/\bFROM\s+([a-zA-Z0-9_]+)|\bJOIN\s+([a-zA-Z0-9_]+)/gi))].length;
        const joins = (sql.match(/\bJOIN\b/gi) || []).length;
        elements.statTables.textContent = tables;
        elements.statJoins.textContent = joins;

        // 3. Complexity
        let complexity = 'Simple';
        if (joins > 3 || sql.includes('WITH') || sql.includes('OVER')) complexity = 'Complex';
        else if (joins > 0 || sql.includes('GROUP BY') || sql.includes('SUBSELECT')) complexity = 'Moderate';
        
        elements.statComplexity.textContent = complexity;
        elements.statComplexity.className = complexity === 'Simple' ? 'text-success fw-bold' : (complexity === 'Moderate' ? 'text-warning fw-bold' : 'text-danger fw-bold');

        // 4. Generate Explanation
        generateExplanation(type, tables, joins, sql);

        // 5. Structural Validation
        validateStructure(sql);
    }

    function generateExplanation(type, tables, joins, sql) {
        let text = `This is a <strong>${type}</strong> statement. `;
        
        if (type === 'SELECT') {
            text += `It retrieves data from ${tables} table(s)${joins > 0 ? ` using ${joins} join operation(s)` : ''}. `;
            if (sql.match(/\bWHERE\b/i)) text += "The results are filtered using a WHERE clause. ";
            if (sql.match(/\bORDER BY\b/i)) text += "The final output will be sorted. ";
        } else if (type === 'DELETE' || type === 'UPDATE') {
            if (!sql.match(/\bWHERE\b/i)) {
                text += `<br><span class="text-danger">⚠ WARNING: This statement has no WHERE clause and will affect ALL rows in the table.</span>`;
            } else {
                text += `It modifies specific rows defined by the WHERE condition.`;
            }
        } else if (type === 'DROP' || type === 'TRUNCATE') {
            text += `<br><span class="text-danger">⚠ CRITICAL: This is a data definition operation that will permanently remove data or structures.</span>`;
        }

        elements.queryExplain.innerHTML = text;
    }

    function validateStructure(sql) {
        const issues = [];
        
        // Simple balance checks
        const openParen = (sql.match(/\(/g) || []).length;
        const closeParen = (sql.match(/\)/g) || []).length;
        if (openParen !== closeParen) issues.push(`Unbalanced parentheses: found ${openParen} opening and ${closeParen} closing.`);

        const quotes = (sql.match(/'/g) || []).length;
        if (quotes % 2 !== 0) issues.push("Unclosed single quote detected.");

        if (issues.length === 0) {
            elements.validationResult.innerHTML = '<div class="text-success"><i class="bi bi-check-circle-fill me-2"></i>Structural validation passed. No obvious syntax issues found.</div>';
        } else {
            elements.validationResult.innerHTML = `<div class="text-danger fw-bold mb-2">Potential issues identified:</div><ul class="mb-0">${issues.map(i => `<li>${i}</li>`).join('')}</ul>`;
        }
    }

    function updateCounts() {
        const val = elements.input.value;
        elements.charCount.textContent = val.length;
        elements.wordCount.textContent = val ? val.trim().split(/\s+/).length : 0;
    }

    function handleFileUpload(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (event) => {
            elements.input.value = event.target.result;
            updateCounts();
            showToast('File loaded: ' + file.name, 'info');
        };
        reader.readAsText(file);
    }

    function loadExample() {
        elements.input.value = "select u.id, u.name, p.profile_pic from users as u join profiles p on u.id = p.user_id where u.active = 1 and p.verified = 'yes' order by u.created_at desc limit 10;";
        updateCounts();
        format();
    }

    function clear() {
        elements.input.value = '';
        elements.outputContainer.classList.add('d-none');
        elements.analysisArea.classList.add('d-none');
        elements.statsCard.classList.add('d-none');
        updateCounts();
    }

    function copyOutput() {
        const text = elements.output.innerText || elements.output.textContent;
        navigator.clipboard.writeText(text).then(() => {
            showToast('Formatted SQL copied!', 'success');
        });
    }

    function download() {
        const content = elements.output.innerText || elements.output.textContent;
        if (!content) return;
        const blob = new Blob([content], { type: 'text/sql' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'formatted-query.sql';
        a.click();
        URL.revokeObjectURL(url);
    }

    return { init, loadExample, clear, copyOutput, download };
})();

document.addEventListener('DOMContentLoaded', () => {
    SQLTool.init();
});
</script>
@endpush
