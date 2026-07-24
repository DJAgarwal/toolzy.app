/**
 * Laravel Eloquent Query Analyzer & Optimizer - UI & Controller
 * Toolzy (c) 2026 - Client-Side App Script
 */

document.addEventListener('DOMContentLoaded', function () {
    // State management
    let editor = null;
    let isMonacoLoaded = false;
    let currentAnalysis = null;
    let activeSeverityFilter = 'all';

    // DOM Elements
    const monacoContainer = document.getElementById('monacoEditorContainer');
    const fallbackTextarea = document.getElementById('codeFallback');
    const versionSelect = document.getElementById('frameworkVersion');
    const databaseSelect = document.getElementById('databaseEngine');
    const btnAnalyze = document.getElementById('btnAnalyze');
    const btnClear = document.getElementById('btnClear');
    const btnCopyCode = document.getElementById('btnCopyCode');
    const btnCopyOptimized = document.getElementById('btnCopyOptimized');
    const btnDownloadPhp = document.getElementById('btnDownloadPhp');
    const sampleDropdown = document.getElementById('sampleDropdown');
    const themeToggleBtn = document.getElementById('btnToggleTheme');
    const resultsArea = document.getElementById('resultsArea');
    const emptyState = document.getElementById('emptyState');
    const loadingState = document.getElementById('loadingState');

    // Samples library
    const SAMPLES = {
        n1: `<?php
namespace App\\Services;

use App\\Models\\User;

class UserService
{
    public function getActiveUserPosts()
    {
        $users = User::all();

        foreach ($users as $user) {
            echo "User: " . $user->name . "\\n";
            foreach ($user->posts as $post) {
                echo " - Post: " . $post->title . "\\n";
            }
        }
    }
}`,
        overfetching: `<?php
namespace App\\Http\\Controllers;

use App\\Models\\Product;

class ProductController extends Controller
{
    public function index()
    {
        // Unbounded retrieval of all columns and rows
        $products = Product::all();

        // In-memory collection filtering instead of SQL WHERE clause
        $activeProducts = $products->where('status', 'active');

        // Counting records in PHP memory
        $totalCount = Product::get()->count();

        return view('products.index', compact('activeProducts', 'totalCount'));
    }
}`,
        pagination: `<?php
namespace App\\Http\\Controllers;

use App\\Models\\Order;
use DB;

class OrderReportController extends Controller
{
    public function exportRecentOrders()
    {
        // Fetching heavy list without pagination or chunking
        $orders = Order::where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            // Repeated queries inside loop
            $user = DB::table('users')->where('id', $order->user_id)->first();
            $orderTotal = Order::where('id', $order->id)->get()->first();
        }

        return $orders;
    }
}`,
        collection_misuse: `<?php
use App\\Models\\User;

// Anti-pattern 1: Using count() > 0 instead of exists()
if (User::where('email', $userEmail)->get()->count() > 0) {
    // Record exists logic
}

// Anti-pattern 2: Using get()->first() instead of first()
$user = User::where('status', 'active')->get()->first();

// Anti-pattern 3: Pluck -> first instead of value()
$name = User::where('id', 1)->pluck('name')->first();
`,
        mongodb: `<?php
namespace App\\Services;

use App\\Models\\MongoLog;

class MongoService
{
    public function fetchLogs($category)
    {
        // Missing projection: returns huge BSON documents
        $logs = MongoLog::where('category', $category)->get();

        // Dangerous $where server-side JS injection risk
        $recent = MongoLog::whereRaw('$where', "this.created_at > '" . $category . "'")->get();

        return $logs;
    }
}`
    };

    // Initialize Monaco Editor with CDN or fallback
    initEditor();

    function initEditor() {
        if (window.monaco) {
            createMonacoInstance();
        } else {
            // Load Monaco from CDN
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js';
            script.onload = () => {
                window.require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' } });
                window.require(['vs/editor/editor.main'], function () {
                    createMonacoInstance();
                });
            };
            script.onerror = () => {
                showFallbackEditor();
            };
            document.head.appendChild(script);
        }
    }

    function createMonacoInstance() {
        try {
            fallbackTextarea.classList.add('d-none');
            monacoContainer.classList.remove('d-none');

            editor = monaco.editor.create(monacoContainer, {
                value: SAMPLES.n1,
                language: 'php',
                theme: 'vs-dark',
                automaticLayout: true,
                fontSize: 14,
                minimap: { enabled: false },
                lineNumbers: 'on',
                scrollBeyondLastLine: false,
                padding: { top: 12, bottom: 12 }
            });

            isMonacoLoaded = true;

            // Trigger auto analysis after typing stops (debounce)
            let debounceTimer;
            editor.onDidChangeModelContent(() => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    runAnalysis(false);
                }, 600);
            });

            // Initial analysis
            runAnalysis(false);
        } catch (e) {
            showFallbackEditor();
        }
    }

    function showFallbackEditor() {
        isMonacoLoaded = false;
        monacoContainer.classList.add('d-none');
        fallbackTextarea.classList.remove('d-none');
        fallbackTextarea.value = SAMPLES.n1;

        fallbackTextarea.addEventListener('input', () => {
            runAnalysis(false);
        });

        runAnalysis(false);
    }

    function getCode() {
        if (isMonacoLoaded && editor) {
            return editor.getValue();
        }
        return fallbackTextarea.value;
    }

    function setCode(val) {
        if (isMonacoLoaded && editor) {
            editor.setValue(val);
        } else {
            fallbackTextarea.value = val;
        }
        runAnalysis(true);
    }

    // Event Listeners
    if (btnAnalyze) {
        btnAnalyze.addEventListener('click', () => {
            runAnalysis(true);
            if (typeof trackEvent === 'function') {
                trackEvent('eloquent_analyzer_analysis_started', {
                    version: versionSelect.value,
                    database: databaseSelect.value
                });
            }
        });
    }

    if (btnClear) {
        btnClear.addEventListener('click', () => {
            setCode('');
            resultsArea.classList.add('d-none');
            emptyState.classList.remove('d-none');
        });
    }

    if (btnCopyCode) {
        btnCopyCode.addEventListener('click', () => {
            const code = getCode();
            if (code && typeof copyToClipboard === 'function') {
                copyToClipboard(code, btnCopyCode, 'Original Code Copied!');
                if (typeof trackEvent === 'function') {
                    trackEvent('eloquent_analyzer_code_copied', { type: 'original' });
                }
            }
        });
    }

    if (btnCopyOptimized) {
        btnCopyOptimized.addEventListener('click', () => {
            const code = document.getElementById('optimizedCodeText')?.textContent || '';
            if (code && typeof copyToClipboard === 'function') {
                copyToClipboard(code, btnCopyOptimized, 'Optimized Code Copied!');
                if (typeof trackEvent === 'function') {
                    trackEvent('eloquent_analyzer_code_copied', { type: 'optimized' });
                }
            }
        });
    }

    if (btnDownloadPhp) {
        btnDownloadPhp.addEventListener('click', () => {
            const code = document.getElementById('optimizedCodeText')?.textContent || '';
            if (!code) return;
            const blob = new Blob([code], { type: 'text/x-php;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'OptimizedEloquentQueries.php';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            if (typeof trackEvent === 'function') {
                trackEvent('eloquent_analyzer_code_downloaded');
            }
        });
    }

    // Theme toggle
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);

            if (isMonacoLoaded && editor) {
                monaco.editor.setTheme(newTheme === 'dark' ? 'vs-dark' : 'vs');
            }
        });
    }

    // Dropdown sample selection
    document.querySelectorAll('.sample-item').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const key = item.getAttribute('data-sample');
            if (SAMPLES[key]) {
                setCode(SAMPLES[key]);
                if (typeof trackEvent === 'function') {
                    trackEvent('eloquent_analyzer_example_loaded', { sample: key });
                }
            }
        });
    });

    // Version & Database change listeners
    if (versionSelect) versionSelect.addEventListener('change', () => runAnalysis(true));
    if (databaseSelect) databaseSelect.addEventListener('change', () => runAnalysis(true));

    // Category Toggles
    document.querySelectorAll('.category-toggle').forEach(chk => {
        chk.addEventListener('change', () => runAnalysis(true));
    });

    // Severity Filters
    document.querySelectorAll('.severity-filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.severity-filter-btn').forEach(b => b.classList.remove('active', 'btn-primary'));
            document.querySelectorAll('.severity-filter-btn').forEach(b => b.classList.add('btn-outline-secondary'));

            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('active', 'btn-primary');

            activeSeverityFilter = btn.getAttribute('data-severity');
            renderIssues(currentAnalysis ? currentAnalysis.issues : []);
        });
    });

    // Core Analysis Trigger
    function runAnalysis(showLoading = true) {
        const code = getCode();
        if (!code.trim()) {
            emptyState.classList.remove('d-none');
            resultsArea.classList.add('d-none');
            return;
        }

        if (showLoading) {
            loadingState.classList.remove('d-none');
        }

        // Selected options
        const categories = Array.from(document.querySelectorAll('.category-toggle:checked')).map(c => c.value);
        const options = {
            version: versionSelect.value,
            database: databaseSelect.value,
            categories: categories
        };

        // Asynchronous non-blocking analysis execution
        setTimeout(() => {
            currentAnalysis = window.EloquentAnalyzerEngine.analyze(code, options);

            loadingState.classList.add('d-none');
            emptyState.classList.add('d-none');
            resultsArea.classList.remove('d-none');

            renderScores(currentAnalysis.scores, currentAnalysis.countsBySeverity);
            renderIssues(currentAnalysis.issues);
            renderOptimizedCode(currentAnalysis.optimizedCode, currentAnalysis.diffLines);

            if (showLoading && typeof trackEvent === 'function') {
                trackEvent('eloquent_analyzer_analysis_completed', {
                    score: currentAnalysis.scores.overall,
                    issueCount: currentAnalysis.totalIssues
                });
            }
        }, 150);
    }

    // Score Dashboard Renderer
    function renderScores(scores, counts) {
        // Overall Gauge
        const overallElem = document.getElementById('overallScoreText');
        const overallCircle = document.getElementById('overallScoreCircle');
        if (overallElem && overallCircle) {
            overallElem.textContent = scores.overall;

            const radius = 60;
            const circumference = 2 * Math.PI * radius;
            const offset = circumference - (scores.overall / 100) * circumference;
            overallCircle.style.strokeDasharray = `${circumference} ${circumference}`;
            overallCircle.style.strokeDashoffset = offset;

            // Color status
            if (scores.overall >= 85) overallCircle.style.stroke = '#10b981';
            else if (scores.overall >= 60) overallCircle.style.stroke = '#f59e0b';
            else overallCircle.style.stroke = '#ef4444';
        }

        // Subcategory bars
        updateProgressBar('perfBar', 'perfScore', scores.performance);
        updateProgressBar('memoryBar', 'memoryScore', scores.memory);
        updateProgressBar('dbBar', 'dbScore', scores.database);
        updateProgressBar('readabilityBar', 'readabilityScore', scores.readability);
        updateProgressBar('maintainabilityBar', 'maintainabilityScore', scores.maintainability);

        // Severity count badges
        setElementText('badgeCountCritical', counts.critical);
        setElementText('badgeCountHigh', counts.high);
        setElementText('badgeCountMedium', counts.medium);
        setElementText('badgeCountLow', counts.low);
        setElementText('badgeCountInfo', counts.info);
    }

    function updateProgressBar(barId, textId, score) {
        const bar = document.getElementById(barId);
        const txt = document.getElementById(textId);
        if (bar && txt) {
            bar.style.width = `${score}%`;
            txt.textContent = `${score}/100`;

            bar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
            if (score >= 85) bar.classList.add('bg-success');
            else if (score >= 60) bar.classList.add('bg-warning');
            else bar.classList.add('bg-danger');
        }
    }

    // Issues Renderer
    function renderIssues(issues) {
        const container = document.getElementById('issuesListContainer');
        if (!container) return;

        container.innerHTML = '';

        const filtered = activeSeverityFilter === 'all'
            ? issues
            : issues.filter(i => i.severity === activeSeverityFilter);

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-check-circle-fill text-success display-4 mb-2"></i>
                    <p class="mb-0 fw-semibold">No issues detected for this severity filter!</p>
                </div>
            `;
            return;
        }

        filtered.forEach((issue, index) => {
            const card = document.createElement('div');
            card.className = `card shadow-sm mb-3 issue-card severity-${issue.severity}`;
            card.innerHTML = `
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge severity-badge-${issue.severity} text-uppercase me-2">${issue.severity}</span>
                            <span class="badge bg-secondary text-capitalize me-2">${issue.category}</span>
                            <span class="text-muted small"><i class="bi bi-code-square me-1"></i>Line ${issue.line}</span>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark dark:text-light mb-2">${escapeHtml(issue.title)}</h6>
                    <p class="text-secondary small mb-3">${escapeHtml(issue.message)}</p>

                    <div class="bg-light dark:bg-dark p-3 rounded mb-3 border font-monospace small">
                        <span class="text-muted small me-2">Line ${issue.line}:</span>
                        <code>${escapeHtml(issue.codeSnippet)}</code>
                    </div>

                    <div class="accordion accordion-flush" id="issueAcc_${index}">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed px-0 py-2 small fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIssue_${index}">
                                    <i class="bi bi-book me-2"></i>Why it matters & How to fix
                                </button>
                            </h2>
                            <div id="collapseIssue_${index}" class="accordion-collapse collapse">
                                <div class="accordion-body px-0 py-3 text-muted small">
                                    <div class="mb-2">
                                        <strong class="text-dark dark:text-light">Impact:</strong> ${escapeHtml(issue.whyItMatters)}
                                    </div>
                                    <div class="mb-3">
                                        <strong class="text-dark dark:text-light">Suggested Fix:</strong> ${escapeHtml(issue.fix)}
                                    </div>
                                    ${issue.educational ? `<div class="alert alert-info py-2 px-3 small mb-0"><i class="bi bi-info-circle me-2"></i>${escapeHtml(issue.educational)}</div>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    }

    // Optimized Code Panel Renderer
    function renderOptimizedCode(code, diffLines) {
        const codeTextElem = document.getElementById('optimizedCodeText');
        const diffContainer = document.getElementById('diffViewerContainer');

        if (codeTextElem) {
            codeTextElem.textContent = code;
        }

        if (diffContainer && diffLines) {
            diffContainer.innerHTML = '';
            diffLines.forEach(line => {
                const div = document.createElement('div');
                div.className = `px-3 py-1 font-monospace small diff-line-${line.type}`;
                const prefix = line.type === 'added' ? '+ ' : (line.type === 'removed' ? '- ' : '  ');
                div.textContent = `${prefix}${line.text}`;
                diffContainer.appendChild(div);
            });
        }
    }

    // Helper functions
    function setElementText(id, text) {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
});
