@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/eloquent-analyzer.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />
    <!-- Page Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8 col-lg-9 mb-3 mb-md-0">
            <h1 class="h3 fw-bold mb-1">Laravel Eloquent Query Analyzer & Optimizer</h1>
            <p class="text-muted mb-0">
                Statically analyze Eloquent models, Query Builder queries, and MongoDB calls for performance bottlenecks, N+1 risks, missing eager loading, and memory bloat.
            </p>
        </div>
        <div class="col-md-4 col-lg-3">
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-semibold" type="button" id="sampleDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-code-slash me-1"></i> Example Snippets
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="sampleDropdown">
                    <li><a class="dropdown-item sample-item" data-sample="n1" href="#">N+1 Query Loop Problem</a></li>
                    <li><a class="dropdown-item sample-item" data-sample="overfetching" href="#">Over-fetching & Model::all()</a></li>
                    <li><a class="dropdown-item sample-item" data-sample="pagination" href="#">Inefficient Pagination & Loop Lookups</a></li>
                    <li><a class="dropdown-item sample-item" data-sample="collection_misuse" href="#">Collection Misuse (count, exists, first)</a></li>
                    <li><a class="dropdown-item sample-item" data-sample="mongodb" href="#">MongoDB Projection & JS Injection</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Workspace Row -->
    <div class="row g-4 mb-4">
        <!-- Left: Code Input & Settings -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white dark:bg-dark py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-file-earmark-code me-2 text-primary"></i>Pasted Laravel Code Snippet
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" id="btnCopyCode" title="Copy Original Code">
                            <i class="bi bi-clipboard me-1"></i>Copy
                        </button>
                        <button class="btn btn-outline-danger" id="btnClear" title="Clear Code">
                            <i class="bi bi-trash me-1"></i>Clear
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <!-- Monaco Container -->
                    <div id="monacoEditorContainer" class="monaco-editor-container"></div>
                    <!-- Fallback Textarea -->
                    <textarea id="codeFallback" class="form-control monaco-editor-fallback d-none" rows="14" spellcheck="false" placeholder="// Paste your Laravel Eloquent or Query Builder PHP code here..."></textarea>

                    <!-- Configurations Row -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="frameworkVersion" class="form-label small fw-bold text-muted mb-1">Laravel Version</label>
                            <select id="frameworkVersion" class="form-select form-select-sm">
                                <option value="v12" selected>Laravel 12 (Latest)</option>
                                <option value="v11">Laravel 11</option>
                                <option value="v10">Laravel 10</option>
                                <option value="v9">Laravel 9</option>
                                <option value="v8">Laravel 8</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="databaseEngine" class="form-label small fw-bold text-muted mb-1">Database Engine</label>
                            <select id="databaseEngine" class="form-select form-select-sm">
                                <option value="mysql" selected>MySQL</option>
                                <option value="mariadb">MariaDB</option>
                                <option value="postgresql">PostgreSQL</option>
                                <option value="sqlite">SQLite</option>
                                <option value="sqlsrv">SQL Server (MS SQL)</option>
                                <option value="mongodb">MongoDB (Laravel MongoDB)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Analysis Toggles -->
                    <div class="mt-3">
                        <label class="form-label small fw-bold text-muted mb-2">Enabled Analysis Rules</label>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-check form-check-inline me-0">
                                <input class="form-check-input category-toggle" type="checkbox" id="catPerf" value="performance" checked>
                                <label class="form-check-label small" for="catPerf">Performance</label>
                            </div>
                            <div class="form-check form-check-inline me-0">
                                <input class="form-check-input category-toggle" type="checkbox" id="catMem" value="memory" checked>
                                <label class="form-check-label small" for="catMem">Memory Usage</label>
                            </div>
                            <div class="form-check form-check-inline me-0">
                                <input class="form-check-input category-toggle" type="checkbox" id="catDb" value="database" checked>
                                <label class="form-check-label small" for="catDb">Database Design</label>
                            </div>
                            <div class="form-check form-check-inline me-0">
                                <input class="form-check-input category-toggle" type="checkbox" id="catSec" value="security" checked>
                                <label class="form-check-label small" for="catSec">Security</label>
                            </div>
                            <div class="form-check form-check-inline me-0">
                                <input class="form-check-input category-toggle" type="checkbox" id="catRead" value="readability" checked>
                                <label class="form-check-label small" for="catRead">Readability</label>
                            </div>
                            <div class="form-check form-check-inline me-0">
                                <input class="form-check-input category-toggle" type="checkbox" id="catMaint" value="maintainability" checked>
                                <label class="form-check-label small" for="catMaint">Maintainability</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light dark:bg-dark p-3">
                    <button id="btnAnalyze" class="btn btn-primary w-100 fw-bold py-2">
                        <i class="bi bi-cpu me-2"></i>Run Static Query Analysis
                    </button>
                </div>
            </div>
        </div>

        <!-- Right: Results & Dashboard -->
        <div class="col-lg-6">
            <!-- Loading state -->
            <div id="loadingState" class="card shadow-sm border-0 h-100 d-none">
                <div class="card-body text-center py-5 d-flex flex-column justify-content-center align-items-center">
                    <div class="spinner-border text-primary mb-3 spinner-3rem" role="status">
                        <span class="visually-hidden">Analyzing...</span>
                    </div>
                    <h5 class="fw-bold">Running Client-Side Static Analysis...</h5>
                    <p class="text-muted small">Tokenizing Eloquent queries, checking N+1 patterns, and building performance metrics.</p>
                </div>
            </div>

            <!-- Empty state -->
            <div id="emptyState" class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-5 d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-code-slash text-muted display-1 mb-3"></i>
                    <h5 class="fw-bold text-dark dark:text-light">No Query Analysis Yet</h5>
                    <p class="text-muted small max-w-md mx-auto">
                        Paste your Laravel Eloquent or Query Builder code on the left or select an example snippet above to see instant local performance analysis.
                    </p>
                </div>
            </div>

            <!-- Results Dashboard -->
            <div id="resultsArea" class="d-none">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <!-- Overall Circular Gauge -->
                            <div class="col-md-5 text-center mb-3 mb-md-0 border-end">
                                <div class="score-circle-wrapper">
                                    <svg class="score-circle-svg" width="140" height="140" viewBox="0 0 140 140">
                                        <circle class="score-circle-bg" cx="70" cy="70" r="60"></circle>
                                        <circle id="overallScoreCircle" class="score-circle-val" cx="70" cy="70" r="60"></circle>
                                    </svg>
                                    <div class="score-circle-text">
                                        <div id="overallScoreText" class="score-number">100</div>
                                        <div class="score-label">Score</div>
                                    </div>
                                </div>
                                <h6 class="fw-bold mt-2 mb-0">Query Health Grade</h6>
                                <p class="text-muted small mb-0">Based on client-side static rules</p>
                            </div>

                            <!-- Detailed Metrics Bars -->
                            <div class="col-md-7">
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                                        <span>Performance Score</span>
                                        <span id="perfScore">100/100</span>
                                    </div>
                                    <div class="progress progress-6px">
                                        <div id="perfBar" class="progress-bar bg-success w-100"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                                        <span>Memory Score</span>
                                        <span id="memoryScore">100/100</span>
                                    </div>
                                    <div class="progress progress-6px">
                                        <div id="memoryBar" class="progress-bar bg-success w-100"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                                        <span>Database Optimization</span>
                                        <span id="dbScore">100/100</span>
                                    </div>
                                    <div class="progress progress-6px">
                                        <div id="dbBar" class="progress-bar bg-success w-100"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                                        <span>Readability Score</span>
                                        <span id="readabilityScore">100/100</span>
                                    </div>
                                    <div class="progress progress-6px">
                                        <div id="readabilityBar" class="progress-bar bg-success w-100"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                                        <span>Maintainability Score</span>
                                        <span id="maintainabilityScore">100/100</span>
                                    </div>
                                    <div class="progress progress-6px">
                                        <div id="maintainabilityBar" class="progress-bar bg-success w-100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Severity Chips Filters -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="btn btn-primary btn-sm severity-filter-btn active" data-severity="all">
                        All Issues
                    </button>
                    <button class="btn btn-outline-secondary btn-sm severity-filter-btn" data-severity="critical">
                        Critical <span id="badgeCountCritical" class="badge bg-danger rounded-pill ms-1">0</span>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm severity-filter-btn" data-severity="high">
                        High <span id="badgeCountHigh" class="badge bg-warning text-dark rounded-pill ms-1">0</span>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm severity-filter-btn" data-severity="medium">
                        Medium <span id="badgeCountMedium" class="badge bg-info text-dark rounded-pill ms-1">0</span>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm severity-filter-btn" data-severity="low">
                        Low <span id="badgeCountLow" class="badge bg-secondary rounded-pill ms-1">0</span>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm severity-filter-btn" data-severity="info">
                        Info <span id="badgeCountInfo" class="badge bg-light text-dark rounded-pill ms-1">0</span>
                    </button>
                </div>

                <!-- Detected Issues List -->
                <div id="issuesListContainer">
                    <!-- Issue cards injected by app.js -->
                </div>
            </div>
        </div>
    </div>

    <!-- Optimized Code Comparison Panel -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white dark:bg-dark py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-magic me-2 text-success"></i>Optimized Code Alternatives & Diff
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" id="btnCopyOptimized">
                            <i class="bi bi-clipboard me-1"></i>Copy Optimized
                        </button>
                        <button class="btn btn-outline-success" id="btnDownloadPhp">
                            <i class="bi bi-download me-1"></i>Download .php
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-tabs px-3 pt-3 border-bottom-0" id="codePanelTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-item nav-link active fw-semibold" id="tab-diff" data-bs-toggle="tab" data-bs-target="#panel-diff" type="button" role="tab">
                                <i class="bi bi-file-diff me-1"></i>Line Diff View
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-item nav-link fw-semibold" id="tab-full" data-bs-toggle="tab" data-bs-target="#panel-full" type="button" role="tab">
                                <i class="bi bi-code-square me-1"></i>Full Optimized PHP
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content border-top" id="codePanelTabContent">
                        <div class="tab-pane fade show active p-3" id="panel-diff" role="tabpanel">
                            <div id="diffViewerContainer" class="code-diff-container p-3">
                                <span class="text-muted small">No diff available. Run analysis on code to generate diff.</span>
                            </div>
                        </div>
                        <div class="tab-pane fade p-3" id="panel-full" role="tabpanel">
                            <pre class="bg-dark text-light p-3 rounded mb-0 font-monospace small pre-scrollable-400"><code id="optimizedCodeText">// Optimized PHP query code will appear here...</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Explanations & Best Practices Library -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white dark:bg-dark py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Eloquent Performance Best Practices Library
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active text-start py-3 fw-semibold" id="v-pills-n1-tab" data-bs-toggle="pill" data-bs-target="#v-pills-n1" type="button" role="tab">
                                    <i class="bi bi-arrow-repeat me-2"></i>1. Eager Loading & N+1 Prevention
                                </button>
                                <button class="nav-link text-start py-3 fw-semibold" id="v-pills-pagination-tab" data-bs-toggle="pill" data-bs-target="#v-pills-pagination" type="button" role="tab">
                                    <i class="bi bi-journal-text me-2"></i>2. Offset vs Cursor Pagination
                                </button>
                                <button class="nav-link text-start py-3 fw-semibold" id="v-pills-chunking-tab" data-bs-toggle="pill" data-bs-target="#v-pills-chunking" type="button" role="tab">
                                    <i class="bi bi-stack me-2"></i>3. Chunking & Cursor Iteration
                                </button>
                                <button class="nav-link text-start py-3 fw-semibold" id="v-pills-indexing-tab" data-bs-toggle="pill" data-bs-target="#v-pills-indexing" type="button" role="tab">
                                    <i class="bi bi-key-fill me-2"></i>4. Indexing Fundamentals
                                </button>
                                <button class="nav-link text-start py-3 fw-semibold" id="v-pills-mongo-tab" data-bs-toggle="pill" data-bs-target="#v-pills-mongo" type="button" role="tab">
                                    <i class="bi bi-database-gear me-2"></i>5. MongoDB Query Optimization
                                </button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="tab-content" id="v-pills-tabContent">
                                <!-- N+1 Guide -->
                                <div class="tab-pane fade show active" id="v-pills-n1" role="tabpanel">
                                    <h5 class="fw-bold text-primary">Mastering Eager Loading in Laravel</h5>
                                    <p class="text-muted">
                                        The N+1 query problem is the single most common cause of slow web applications in Laravel. It occurs when a query fetches parent records, and child models are loaded on-demand in a loop.
                                    </p>
                                    <div class="bg-light dark:bg-dark p-3 rounded mb-3 border font-monospace small">
                                        <div class="text-danger mb-1">// Inefficient N+1: 101 database queries executed</div>
                                        <div>$users = User::all();</div>
                                        <div>foreach ($users as $user) { echo $user->profile->avatar; }</div>
                                        <div class="text-success mt-2 mb-1">// Optimized Eager Loading: 2 database queries total</div>
                                        <div>$users = User::with('profile')->get();</div>
                                    </div>
                                    <p class="small text-muted">
                                        <strong>When NOT to eager load:</strong> Do not eager load massive, unbounded relationships (e.g. 50,000 comments) unless you select specific columns or paginate the relationship.
                                    </p>
                                </div>

                                <!-- Pagination Guide -->
                                <div class="tab-pane fade" id="v-pills-pagination" role="tabpanel">
                                    <h5 class="fw-bold text-primary">Offset vs Cursor Pagination</h5>
                                    <p class="text-muted">
                                        Standard pagination (<code>paginate()</code>) uses SQL <code>OFFSET</code>. As page numbers grow, <code>OFFSET 100000</code> forces the database engine to scan 100,000 discarded rows.
                                    </p>
                                    <ul class="small text-muted mb-3">
                                        <li><strong>paginate():</strong> Generates total page count & total item count. Best for small UI tables (< 5,000 rows).</li>
                                        <li><strong>simplePaginate():</strong> Displays "Next" and "Previous" buttons only. Skips total count query.</li>
                                        <li><strong>cursorPaginate():</strong> Uses index pointers (<code>WHERE id > cursor LIMIT 15</code>). Delivers constant-time O(1) performance for infinite scroll & massive tables.</li>
                                    </ul>
                                </div>

                                <!-- Chunking Guide -->
                                <div class="tab-pane fade" id="v-pills-chunking" role="tabpanel">
                                    <h5 class="fw-bold text-primary">Chunking & Low-Memory Generators</h5>
                                    <p class="text-muted">
                                        Processing thousands of models with <code>Model::get()</code> causes fatal Out-Of-Memory (OOM) errors in background jobs and CLI tasks.
                                    </p>
                                    <div class="bg-light dark:bg-dark p-3 rounded mb-3 border font-monospace small">
                                        <div>User::chunk(500, function ($users) {</div>
                                        <div>&nbsp;&nbsp;&nbsp;&nbsp;foreach ($users as $user) { $user->update([...]); }</div>
                                        <div>});</div>
                                    </div>
                                </div>

                                <!-- Indexing Guide -->
                                <div class="tab-pane fade" id="v-pills-indexing" role="tabpanel">
                                    <h5 class="fw-bold text-primary">Database Indexing & Foreign Keys</h5>
                                    <p class="text-muted">
                                        Without indexes, MySQL and PostgreSQL perform full table scans for every <code>where()</code>, <code>join()</code>, and <code>orderBy()</code> clause.
                                    </p>
                                    <p class="small text-muted">
                                        Always add indexes in your Laravel migrations for foreign keys (<code>user_id</code>), status flags, and frequently filtered date columns.
                                    </p>
                                </div>

                                <!-- Mongo Guide -->
                                <div class="tab-pane fade" id="v-pills-mongo" role="tabpanel">
                                    <h5 class="fw-bold text-primary">MongoDB Optimization for Laravel</h5>
                                    <p class="text-muted">
                                        When using the MongoDB package for Laravel, always project specific document fields to avoid transmitting massive nested BSON payloads across the wire.
                                    </p>
                                </div>
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
    <script nonce="{{ $cspNonce }}" src="{{ asset('js/eloquent-analyzer/rules.js') }}?v={{ time() }}"></script>
    <script nonce="{{ $cspNonce }}" src="{{ asset('js/eloquent-analyzer/engine.js') }}?v={{ time() }}"></script>
    <script nonce="{{ $cspNonce }}" src="{{ asset('js/eloquent-analyzer/app.js') }}?v={{ time() }}"></script>
@endpush
