@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Input Section -->
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-database-fill-gear me-2 text-primary"></i>SQL Query Optimizer & Indexer
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" id="btnExample">Example</button>
                        <button class="btn btn-outline-danger" id="btnClear">Clear</button>
                    </div>
                </div>
                <div class="card-body p-0 position-relative">
                    <textarea id="sqlInput" class="form-control border-0 font-monospace p-4" rows="10" placeholder="-- Paste your SQL query here for analysis...
SELECT * FROM users WHERE email = 'user@example.com' AND status = 'active' ORDER BY created_at DESC;"></textarea>
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="small text-muted mb-0">
                                <i class="bi bi-info-circle me-1"></i> 
                                We support MySQL, MariaDB, and PostgreSQL. The analyzer detects JOINs, WHERE clauses, ORDER BY, and more.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button id="btnAnalyze" class="btn btn-primary px-5 fw-bold">
                                <i class="bi bi-cpu me-2"></i>Analyze Query
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="col-12 d-none">
            <div class="text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="text-muted">Analyzing your query patterns...</h5>
            </div>
        </div>

        <!-- Results Section -->
        <div id="resultsArea" class="col-12 d-none">
            <div class="row g-4">
                <!-- Scores Row -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center py-4">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Query Complexity Score</h6>
                            <div class="display-4 fw-bold mb-1" id="complexityScore">0</div>
                            <div class="h5 mb-3" id="complexityLevel">Simple</div>
                            <div class="progress progress-custom">
                                <div id="complexityBar" class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="small text-muted mt-3 mb-0">Based on joins, subqueries, and filter conditions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center py-4">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Performance Risk Score</h6>
                            <div class="display-4 fw-bold mb-1" id="riskScore">0</div>
                            <div class="h5 mb-3" id="riskLevel">Low</div>
                            <div class="progress progress-custom">
                                <div id="riskBar" class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="small text-muted mt-3 mb-0">Detects anti-patterns that cause full table scans.</p>
                        </div>
                    </div>
                </div>

                <!-- Recommended Indexes -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Recommended Indexes</h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="indexList" class="list-group list-group-flush">
                                <!-- Indexes will be injected here -->
                            </div>
                        </div>
                    </div>

                    <!-- Anti-Patterns -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Query Anti-Patterns Detected</h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="antiPatternList" class="list-group list-group-flush">
                                <!-- Anti-patterns will be injected here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Optimization Sidebar -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
                        <div class="card-body py-4">
                            <h5 class="fw-bold mb-3"><i class="bi bi-check-circle-fill me-2"></i>Actionable Advice</h5>
                            <ul id="suggestionList" class="list-unstyled mb-0 d-flex flex-column gap-3">
                                <!-- Suggestions injected here -->
                            </ul>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-table me-2"></i>Tables & Columns</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush small" id="querySummaryList">
                                <!-- Summary injected here -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Content -->
    <div class="mt-5 border-top pt-5">
        <div class="row g-4">
            <div class="col-lg-12">
                <h2 class="h3 fw-bold mb-4">Understanding SQL Performance Optimization</h2>
                <div class="row g-4 text-muted">
                    <div class="col-md-12">
                        <p class="lead">Optimizing a SQL query is one of the most impactful things a developer can do to improve application scalability and user experience.</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold text-dark">The Power of Composite Indexes</h5>
                        <p>A single-column index is good, but a composite index is often better. If your query has <code>WHERE status = 'active' AND user_id = 5</code>, a composite index on <code>(status, user_id)</code> allows the database to find the exact rows in a single pass. The order of columns in the index should usually match the selectivity (most unique columns first).</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold text-dark">Why "SELECT *" is a Performance Killer</h5>
                        <p>Retrieving all columns increases the data volume transferred between the DB and your app. More importantly, it prevents **Covering Indexes**. A covering index is one that contains all the data needed for the query, allowing the DB to skip reading the table heap entirely.</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold text-dark">Index SARGability</h5>
                        <p>Search ARGument Able (SARGable) queries are those that can use indexes. Using functions like <code>WHERE YEAR(date) = 2025</code> or <code>WHERE name LIKE '%john'</code> makes a query non-SARGable, forcing a full table scan because the index cannot be searched efficiently.</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold text-dark">The Cost of Sorting (ORDER BY)</h5>
                        <p>Sorting large result sets in memory (FileSort) is expensive. An index that includes the columns in your <code>ORDER BY</code> clause allows the database to retrieve rows in the correct order pre-sorted, eliminating the need for a separate sort operation.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
.font-monospace {
    font-family: 'Fira Code', 'Cascadia Code', 'Source Code Pro', Menlo, Monaco, Consolas, "Courier New", monospace !important;
}
#sqlInput {
    background-color: #f8f9fa;
    resize: vertical;
    font-size: 1rem;
    line-height: 1.6;
    min-height: 250px;
    outline: none;
    box-shadow: none;
}
#sqlInput:focus {
    background-color: #fff;
    border-color: #0d6efd;
}
.index-sql {
    background-color: #212529;
    color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    font-size: 0.85rem;
    margin-bottom: 0;
    overflow-x: auto;
}
.severity-high { border-left: 4px solid #dc3545; }
.severity-medium { border-left: 4px solid #ffc107; }
.severity-low { border-left: 4px solid #0dcaf0; }

.card { transition: transform 0.2s ease; }
.card:hover { transform: translateY(-2px); }

.action-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.action-icon {
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    padding: 4px;
    display: inline-flex;
}
.progress-custom {
    height: 10px;
}
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    const elements = {
        input: document.getElementById('sqlInput'),
        btnAnalyze: document.getElementById('btnAnalyze'),
        btnExample: document.getElementById('btnExample'),
        btnClear: document.getElementById('btnClear'),
        resultsArea: document.getElementById('resultsArea'),
        loadingState: document.getElementById('loadingState'),
        
        complexityScore: document.getElementById('complexityScore'),
        complexityLevel: document.getElementById('complexityLevel'),
        complexityBar: document.getElementById('complexityBar'),
        
        riskScore: document.getElementById('riskScore'),
        riskLevel: document.getElementById('riskLevel'),
        riskBar: document.getElementById('riskBar'),
        
        indexList: document.getElementById('indexList'),
        antiPatternList: document.getElementById('antiPatternList'),
        suggestionList: document.getElementById('suggestionList'),
        querySummaryList: document.getElementById('querySummaryList')
    };

    elements.btnAnalyze.addEventListener('click', analyzeQuery);
    elements.btnExample.addEventListener('click', loadExample);
    elements.btnClear.addEventListener('click', clearAll);

    async function analyzeQuery() {
        const sql = elements.input.value.trim();
        if (!sql) {
            showToast('Please enter a SQL query first.', 'warning');
            return;
        }

        elements.resultsArea.classList.add('d-none');
        elements.loadingState.classList.remove('d-none');

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            const response = await fetch('/api/sql-optimizer/analyze', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                },
                body: JSON.stringify({ sql })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Failed to analyze query.');
            }

            renderResults(data);
            showToast('Query analyzed successfully!', 'success');
        } catch (error) {
            showToast(error.message, 'danger');
        } finally {
            elements.loadingState.classList.add('d-none');
        }
    }

    function renderResults(data) {
        // Complexity Score
        const comp = data.scoring.complexity;
        elements.complexityScore.textContent = comp.score;
        elements.complexityLevel.textContent = comp.level;
        elements.complexityBar.style.width = comp.score + '%';
        elements.complexityBar.className = 'progress-bar ' + getBarColor(comp.score, false);

        // Risk Score
        const risk = data.scoring.performanceRisk;
        elements.riskScore.textContent = risk.score;
        elements.riskLevel.textContent = risk.level;
        elements.riskBar.style.width = risk.score + '%';
        elements.riskBar.className = 'progress-bar ' + getBarColor(risk.score, true);

        // Recommended Indexes
        elements.indexList.innerHTML = data.recommendedIndexes.length > 0 
            ? data.recommendedIndexes.map(idx => `
                <div class="list-group-item border-0 border-bottom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-light text-primary border border-primary-subtle">${idx.type}</span>
                        <span class="badge ${idx.impact === 'Very High' ? 'bg-success' : 'bg-info'}">Impact: ${idx.impact}</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">Recommendation for table: <code>${idx.table}</code></h6>
                    <p class="small text-muted mb-3">${idx.why}</p>
                    <div class="position-relative">
                        <pre class="index-sql"><code>${idx.sql}</code></pre>
                        <button class="btn btn-sm btn-link text-white position-absolute top-0 end-0 m-2 text-decoration-none" onclick="copyToClipboard('${idx.sql.replace(/'/g, "\\'")}')">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>
                </div>
            `).join('')
            : '<div class="p-4 text-center text-muted">No specific index recommendations for this query. It might be already optimal or too simple.</div>';

        // Anti-Patterns
        elements.antiPatternList.innerHTML = data.antiPatterns.length > 0
            ? data.antiPatterns.map(ap => `
                <div class="list-group-item border-0 border-bottom p-4 severity-${ap.severity.toLowerCase()}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-dark mb-0">${ap.title}</h6>
                        <span class="badge ${ap.severity === 'High' ? 'bg-danger' : 'bg-warning text-dark'}">${ap.severity} Severity</span>
                    </div>
                    <p class="small text-muted mb-2">${ap.description}</p>
                    <div class="small fw-bold text-primary"><i class="bi bi-arrow-right-circle me-1"></i> Recommendation: ${ap.recommendation}</div>
                </div>
            `).join('')
            : '<div class="p-4 text-center text-success"><i class="bi bi-check-circle-fill me-2"></i>No major anti-patterns detected. Great job!</div>';

        // Actionable Advice
        elements.suggestionList.innerHTML = data.optimizationSuggestions.map(s => `
            <li class="action-item">
                <span class="action-icon"><i class="bi bi-check2"></i></span>
                <span class="small fw-semibold">${s}</span>
            </li>
        `).join('');

        // Query Summary
        const summary = data.querySummary;
        elements.querySummaryList.innerHTML = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Tables</span>
                <span class="fw-bold">${summary.tables.length}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Filter Columns</span>
                <span class="fw-bold">${summary.whereColumns.length}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Sorting Columns</span>
                <span class="fw-bold">${summary.orderByColumns.length}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Join Count</span>
                <span class="fw-bold">${summary.joins.length}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>Has Limit</span>
                <span class="fw-bold">${summary.hasLimit ? 'Yes' : 'No'}</span>
            </li>
        `;

        elements.resultsArea.classList.remove('d-none');
        elements.resultsArea.scrollIntoView({ behavior: 'smooth' });
    }

    function getBarColor(score, isRisk) {
        if (isRisk) {
            if (score < 25) return 'bg-success';
            if (score < 55) return 'bg-warning';
            return 'bg-danger';
        } else {
            if (score < 40) return 'bg-success';
            if (score < 75) return 'bg-info';
            return 'bg-primary';
        }
    }

    function loadExample() {
        elements.input.value = `SELECT o.order_id, o.customer_id, c.name, o.total_amount
FROM orders o
JOIN customers c ON o.customer_id = c.customer_id
WHERE o.status = 'shipped'
  AND o.order_date > '2025-01-01'
  AND c.country = 'USA'
ORDER BY o.total_amount DESC
LIMIT 50;`;
        analyzeQuery();
    }

    function clearAll() {
        elements.input.value = '';
        elements.resultsArea.classList.add('d-none');
    }

    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Copied to clipboard!', 'success');
        });
    }
});
</script>
@endpush
