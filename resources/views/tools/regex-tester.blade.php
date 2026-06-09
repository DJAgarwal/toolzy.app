@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />

    <div class="row g-4">
        <!-- Main Tool Area -->
        <div class="col-lg-8">
            <!-- Pattern Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-regex me-2"></i>Regex Pattern</h5>
                </div>
                <div class="card-body">
                    <div class="input-group input-group-lg mb-3">
                        <span class="input-group-text bg-light text-muted font-monospace">/</span>
                        <input type="text" id="regexPattern" class="form-control font-monospace" placeholder="^[a-zA-Z0-9]+$" aria-label="Regex Pattern">
                        <span class="input-group-text bg-light text-muted font-monospace">/</span>
                        <button class="btn btn-outline-secondary" type="button" id="copyPattern" title="Copy Pattern"><i class="bi bi-clipboard"></i></button>
                        <button class="btn btn-outline-secondary" type="button" id="clearPattern" title="Clear Pattern"><i class="bi bi-x-lg"></i></button>
                    </div>
                    
                    <div id="patternError" class="alert alert-danger d-none py-2 px-3 mb-3 small">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><span id="patternErrorMessage"></span>
                    </div>

                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <span class="fw-semibold small text-muted text-uppercase">Flags:</span>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input regex-flag" type="checkbox" id="flagG" value="g" checked>
                            <label class="form-check-label small" for="flagG" title="Global search">g</label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input regex-flag" type="checkbox" id="flagI" value="i">
                            <label class="form-check-label small" for="flagI" title="Ignore case">i</label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input regex-flag" type="checkbox" id="flagM" value="m">
                            <label class="form-check-label small" for="flagM" title="Multiline search">m</label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input regex-flag" type="checkbox" id="flagS" value="s">
                            <label class="form-check-label small" for="flagS" title="Dotall (dot matches newline)">s</label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input regex-flag" type="checkbox" id="flagU" value="u">
                            <label class="form-check-label small" for="flagU" title="Unicode">u</label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input regex-flag" type="checkbox" id="flagY" value="y">
                            <label class="form-check-label small" for="flagY" title="Sticky">y</label>
                        </div>
                        <div class="form-check form-check-inline mb-0" id="flagDContainer">
                            <input class="form-check-input regex-flag" type="checkbox" id="flagD" value="d">
                            <label class="form-check-label small" for="flagD" title="Indices (hasIndices)">d</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Text Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-file-text me-2"></i>Test Text</h5>
                    <button class="btn btn-sm btn-outline-danger" id="clearText"><i class="bi bi-trash me-1"></i>Clear</button>
                </div>
                <div class="card-body p-0 position-relative">
                    <div id="backdrop" class="highlight-backdrop">
                        <div id="highlights" class="highlight-content"></div>
                    </div>
                    <textarea id="testText" class="form-control highlight-textarea" rows="10" placeholder="Paste or type text here..."></textarea>
                </div>
                <div class="card-footer bg-light py-2 d-flex justify-content-between small text-muted">
                    <div>
                        <span class="me-3">Chars: <span id="charCount">0</span></span>
                        <span class="me-3">Lines: <span id="lineCount">0</span></span>
                        <span>Words: <span id="wordCount">0</span></span>
                    </div>
                    <div id="executionTime" class="d-none">Time: <span>0</span>ms</div>
                </div>
            </div>

            <!-- Results & Details -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold">Match Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="p-3 border rounded bg-light h-100 d-flex flex-column justify-content-center">
                                        <div class="h3 mb-0 fw-bold text-primary" id="totalMatches">0</div>
                                        <div class="small text-muted text-uppercase">Matches</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 border rounded bg-light h-100 d-flex flex-column justify-content-center">
                                        <div class="h3 mb-0 fw-bold text-success" id="patternLength">0</div>
                                        <div class="small text-muted text-uppercase">Pattern Len</div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button id="exportJSON" class="btn btn-sm btn-outline-primary"><i class="bi bi-filetype-json me-1"></i>Export JSON</button>
                                <button id="exportCSV" class="btn btn-sm btn-outline-primary"><i class="bi bi-filetype-csv me-1"></i>Export CSV</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold">Share Test Case</h6>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted mb-3">Generate a link to share this regex and test text with others.</p>
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" id="shareUrl" class="form-control" readonly>
                                <button class="btn btn-primary" id="copyShareUrl" type="button"><i class="bi bi-link-45deg"></i></button>
                            </div>
                            <div id="shareStatus" class="small text-success d-none">Link updated automatically!</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i>Match Details</h5>
                    <div class="btn-group btn-group-sm">
                        <button id="copyMatches" class="btn btn-outline-secondary" title="Copy All Matches"><i class="bi bi-clipboard"></i></button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive match-details-container">
                        <table class="table table-hover mb-0 small" id="matchTable">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Match</th>
                                    <th width="80">Start</th>
                                    <th width="80">End</th>
                                    <th width="80">Length</th>
                                </tr>
                            </thead>
                            <tbody id="matchTableBody">
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted italic">No matches found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm d-none" id="captureGroupsCard">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-collection me-2"></i>Capture Groups</h5>
                    <button id="copyGroups" class="btn btn-sm btn-outline-secondary" title="Copy Groups"><i class="bi bi-clipboard"></i></button>
                </div>
                <div class="card-body" id="captureGroupsContent">
                    <!-- Groups will be injected here -->
                </div>
            </div>
        </div>

        <!-- Sidebar Area -->
        <div class="col-lg-4">
            <!-- Regex Explanation -->
            <div class="card mb-4 shadow-sm border-primary border-opacity-25">
                <div class="card-header bg-opacity-10 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-info-circle me-2"></i>Explanation</h6>
                    <button id="copyExplanation" class="btn btn-sm btn-outline-primary" title="Copy Explanation"><i class="bi bi-clipboard"></i></button>
                </div>
                <div class="card-body small" id="regexExplanation">
                    <div class="text-muted italic">Enter a valid regex pattern to see the explanation.</div>
                </div>
            </div>

            <!-- Common Examples -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-journal-code me-2"></i>Common Examples</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush small overflow-auto examples-container" id="examplesList">
                        <!-- Examples will be injected here -->
                    </div>
                </div>
            </div>

            <!-- Cheat Sheet -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-lightning-charge me-2"></i>Quick Cheat Sheet</h6>
                </div>
                <div class="card-body p-0">
                    <div class="accordion accordion-flush small" id="cheatSheet">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2 px-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#cs-anchors">
                                    Anchors
                                </button>
                            </h2>
                            <div id="cs-anchors" class="accordion-collapse collapse" data-bs-parent="#cheatSheet">
                                <div class="accordion-body p-0">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td><code>^</code></td><td>Start of line/string</td></tr>
                                        <tr><td><code>$</code></td><td>End of line/string</td></tr>
                                        <tr><td><code>\b</code></td><td>Word boundary</td></tr>
                                        <tr><td><code>\B</code></td><td>Non-word boundary</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2 px-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#cs-classes">
                                    Character Classes
                                </button>
                            </h2>
                            <div id="cs-classes" class="accordion-collapse collapse" data-bs-parent="#cheatSheet">
                                <div class="accordion-body p-0">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td><code>.</code></td><td>Any character except newline</td></tr>
                                        <tr><td><code>\d</code></td><td>Digit [0-9]</td></tr>
                                        <tr><td><code>\D</code></td><td>Non-digit</td></tr>
                                        <tr><td><code>\w</code></td><td>Word char [a-zA-Z0-9_]</td></tr>
                                        <tr><td><code>\W</code></td><td>Non-word char</td></tr>
                                        <tr><td><code>\s</code></td><td>Whitespace</td></tr>
                                        <tr><td><code>\S</code></td><td>Non-whitespace</td></tr>
                                        <tr><td><code>[abc]</code></td><td>Any of a, b, or c</td></tr>
                                        <tr><td><code>[^abc]</code></td><td>Not a, b, or c</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2 px-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#cs-quantifiers">
                                    Quantifiers
                                </button>
                            </h2>
                            <div id="cs-quantifiers" class="accordion-collapse collapse" data-bs-parent="#cheatSheet">
                                <div class="accordion-body p-0">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td><code>*</code></td><td>0 or more</td></tr>
                                        <tr><td><code>+</code></td><td>1 or more</td></tr>
                                        <tr><td><code>?</code></td><td>0 or 1</td></tr>
                                        <tr><td><code>{n}</code></td><td>Exactly n times</td></tr>
                                        <tr><td><code>{n,}</code></td><td>n or more times</td></tr>
                                        <tr><td><code>{n,m}</code></td><td>Between n and m times</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2 px-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#cs-groups">
                                    Groups & Assertions
                                </button>
                            </h2>
                            <div id="cs-groups" class="accordion-collapse collapse" data-bs-parent="#cheatSheet">
                                <div class="accordion-body p-0">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td><code>(abc)</code></td><td>Capture group</td></tr>
                                        <tr><td><code>(?:abc)</code></td><td>Non-capture group</td></tr>
                                        <tr><td><code>(?=abc)</code></td><td>Positive lookahead</td></tr>
                                        <tr><td><code>(?!abc)</code></td><td>Negative lookahead</td></tr>
                                        <tr><td><code>(?<=abc)</code></td><td>Positive lookbehind</td></tr>
                                        <tr><td><code>(?<!abc)</code></td><td>Negative lookbehind</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Content Section -->
    <div class="mt-5 border-top pt-5">
        <h2 class="h4 fw-bold mb-4">Regex Performance & Best Practices</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-4">
                    <h5 class="fw-bold"><i class="bi bi-speedometer2 me-2 text-primary"></i>Avoid Backtracking</h5>
                    <p class="text-muted">Nested quantifiers like <code>(a+)*</code> can cause exponential time complexity (Catastrophic Backtracking) when given near-matching inputs. Keep patterns specific and avoid unnecessary nesting of quantifiers.</p>
                </div>
                <div class="mb-4">
                    <h5 class="fw-bold"><i class="bi bi-check-circle me-2 text-success"></i>Be Specific</h5>
                    <p class="text-muted">Instead of <code>.*</code>, use specific character classes like <code>[a-zA-Z0-9]+</code> to limit the engine's search space and prevent over-matching. Specificity is your friend in regex performance.</p>
                </div>
                <div class="mb-4">
                    <h5 class="fw-bold"><i class="bi bi-bug me-2 text-danger"></i>Debugging Techniques</h5>
                    <p class="text-muted">Break complex expressions into smaller, manageable parts. Test each component individually before combining them. Use our "Regex Explanation" panel to understand how each token is being interpreted by the engine.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <h5 class="fw-bold"><i class="bi bi-flag me-2 text-warning"></i>Use the Right Flags</h5>
                    <p class="text-muted">Use <code>i</code> for case-insensitivity to keep patterns readable, and <code>g</code> when you need to find all occurrences. The <code>u</code> flag is essential for correctly handling Unicode characters.</p>
                </div>
                <div class="mb-4">
                    <h5 class="fw-bold"><i class="bi bi-shield-check me-2 text-info"></i>Validate Inputs Safely</h5>
                    <p class="text-muted">Regex is great for validation, but for complex formats (like HTML), consider using dedicated parsers. Don't use regex to parse HTML tags unless for very simple, controlled cases.</p>
                </div>
                <div class="mb-4">
                    <h5 class="fw-bold"><i class="bi bi-question-diamond me-2 text-secondary"></i>When Not to Use Regex</h5>
                    <p class="text-muted">If a task can be accomplished with simple string methods (like <code>String.includes()</code> or <code>String.startsWith()</code>), prefer those over regex. They are often faster and much easier for other developers to read and maintain.</p>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h2 class="h4 fw-bold mb-4">Common Regex Pitfalls</h2>
            <ul class="text-muted">
                <li class="mb-2"><strong>Forgetting to escape:</strong> Characters like <code>.</code>, <code>+</code>, <code>?</code>, <code>*</code>, <code>(</code>, <code>)</code>, <code>[</code>, <code>]</code>, <code>{</code>, <code>}</code>, <code>^</code>, <code>$</code>, <code>|</code>, and <code>\</code> have special meanings and must be escaped with a backslash if you mean their literal value.</li>
                <li class="mb-2"><strong>Greedy vs. Lazy:</strong> By default, quantifiers are greedy (match as much as possible). Add a <code>?</code> (e.g., <code>.*?</code>) to make them lazy (match as little as possible), which is often what you actually want when parsing content between tags.</li>
                <li class="mb-2"><strong>Newline Handling:</strong> The dot <code>.</code> usually doesn't match newlines. Use the <code>s</code> flag (dotall) if you need it to match everything, including newlines, in a multiline string.</li>
                <li class="mb-2"><strong>Capture Group Overuse:</strong> Using parentheses <code>()</code> creates a capture group by default. If you only need grouping for logical purposes without capturing, use non-capturing groups <code>(?:...)</code> to save memory and improve performance.</li>
            </ul>
        </div>
    </div>
</div>

<style nonce="{{ $cspNonce }}">
.match-details-container {
    max-height: 400px;
}
.examples-container {
    max-height: 350px;
}

/* Highlighter CSS */
.position-relative {
    position: relative;
}

.highlight-textarea, .highlight-backdrop {
    width: 100%;
    min-height: 250px;
    padding: 1rem;
    font-family: var(--bs-font-monospace);
    font-size: 0.9rem;
    line-height: 1.5;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-sizing: border-box;
}

.highlight-textarea {
    position: relative;
    z-index: 2;
    background: transparent !important;
    color: #212529;
    resize: vertical;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.highlight-backdrop {
    position: absolute;
    z-index: 1;
    top: 0;
    left: 0;
    overflow: hidden;
    background-color: #fff;
    color: transparent;
    pointer-events: none;
    border-color: transparent;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.highlight-content {
    white-space: pre-wrap;
    word-wrap: break-word;
    margin: 0;
}

.match-highlight {
    background-color: rgba(255, 193, 7, 0.4);
    border-radius: 2px;
    box-shadow: 0 0 0 1px rgba(255, 193, 7, 0.2);
}

.match-highlight-alt {
    background-color: rgba(13, 110, 253, 0.2);
    border-radius: 2px;
}

.group-highlight {
    border-bottom: 2px solid rgba(13, 110, 253, 0.5);
}

.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

#regexExplanation code {
    background: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    color: #e83e8c;
}

#captureGroupsContent .group-item {
    border-left: 3px solid #0d6efd;
    padding-left: 10px;
    margin-bottom: 10px;
}

.group-label {
    font-weight: bold;
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
}

.group-value {
    background: #f8f9fa;
    padding: 5px;
    border-radius: 4px;
    font-family: var(--bs-font-monospace);
    display: block;
    word-break: break-all;
}

/* Scrollbar styling */
.overflow-auto::-webkit-scrollbar {
    width: 6px;
}
.overflow-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.overflow-auto::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}
.overflow-auto::-webkit-scrollbar-thumb:hover {
    background: #bbb;
}
</style>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
const RegexTool = (function() {
    let debounceTimer;
    
    const elements = {
        pattern: document.getElementById('regexPattern'),
        text: document.getElementById('testText'),
        highlights: document.getElementById('highlights'),
        backdrop: document.getElementById('backdrop'),
        totalMatches: document.getElementById('totalMatches'),
        patternLength: document.getElementById('patternLength'),
        executionTime: document.getElementById('executionTime'),
        matchTableBody: document.getElementById('matchTableBody'),
        captureGroupsCard: document.getElementById('captureGroupsCard'),
        captureGroupsContent: document.getElementById('captureGroupsContent'),
        regexExplanation: document.getElementById('regexExplanation'),
        patternError: document.getElementById('patternError'),
        patternErrorMessage: document.getElementById('patternErrorMessage'),
        shareUrl: document.getElementById('shareUrl'),
        shareStatus: document.getElementById('shareStatus'),
        charCount: document.getElementById('charCount'),
        lineCount: document.getElementById('lineCount'),
        wordCount: document.getElementById('wordCount'),
        examplesList: document.getElementById('examplesList')
    };

    const examples = [
        { name: 'Email Address', pattern: '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}', flags: 'g', text: 'Contact us at support@toolzy.app or sales@example.com for more info.' },
        { name: 'URL', pattern: 'https?:\\/\\/(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)', flags: 'gi', text: 'Visit https://toolzy.app or http://google.com?q=regex' },
        { name: 'Phone Number', pattern: '(?:\\+?(\\d{1,3}))?[-. (]*(\\d{3})[-. )]*(\\d{3})[-. ]*(\\d{4})(?: *x(\\d+))?', flags: 'g', text: 'Call us: +1 (555) 123-4567 or 123.456.7890' },
        { name: 'IPv4 Address', pattern: '\\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\b', flags: 'g', text: 'Localhost is 127.0.0.1, Google DNS: 8.8.8.8' },
        { name: 'Hex Color', pattern: '#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})', flags: 'g', text: 'Toolzy uses #0d6efd and #ffffff.' },
        { name: 'Date (YYYY-MM-DD)', pattern: '\\b\\d{4}-(?:0[1-9]|1[0-2])-(?:0[1-9]|[12][0-9]|3[01])\\b', flags: 'g', text: 'Today is 2026-06-07. Event starts 2026-12-25.' },
        { name: 'Credit Card', pattern: '\\b(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\\d{3})\\d{11})\\b', flags: 'g', text: 'Visa: 4111111111111111, MasterCard: 5500000000000004' },
        { name: 'Password Strength', pattern: '^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]{8,}$', flags: 'g', text: 'StrongP@ss123' },
        { name: 'JWT Token', pattern: '[A-Za-z0-9-_=]+\\.[A-Za-z0-9-_=]+\\.?[A-Za-z0-9-_.+/=]*', flags: 'g', text: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c' },
        { name: 'HTML Tags', pattern: '<([a-z1-6]+)([^>]*)>(.*?)<\\/\\1>', flags: 'gi', text: '<div>Hello <b>World</b></div>' }
    ];

    const explanationRules = [
        { regex: /^\^/, desc: '<b>^</b>: Start of string or line.' },
        { regex: /\$$/, desc: '<b>$</b>: End of string or line.' },
        { regex: /\\b/, desc: '<b>\\b</b>: Word boundary.' },
        { regex: /\\w/, desc: '<b>\\w</b>: Word character (alphanumeric + underscore).' },
        { regex: /\\d/, desc: '<b>\\d</b>: Digit (0-9).' },
        { regex: /\\s/, desc: '<b>\\s</b>: Whitespace character.' },
        { regex: /\./, desc: '<b>.</b>: Any character except newline.' },
        { regex: /\*/, desc: '<b>*</b>: Zero or more occurrences.' },
        { regex: /\+/, desc: '<b>+</b>: One or more occurrences.' },
        { regex: /\?/, desc: '<b>?</b>: Zero or one occurrence.' },
        { regex: /\{(\d+)\}/, desc: (m) => `<b>{${m[1]}}</b>: Exactly ${m[1]} times.` },
        { regex: /\{(\d+),(\d+)\}/, desc: (m) => `<b>{${m[1]},${m[2]}}</b>: Between ${m[1]} and ${m[2]} times.` },
        { regex: /\[(.*?)\]/, desc: (m) => `<b>[${m[1]}]</b>: Any character in the set: <code>${m[1]}</code>.` },
        { regex: /\((.*?)\)/, desc: (m) => `<b>(${m[1]})</b>: Capture group.` },
        { regex: /\(\?:(.*?)\)/, desc: (m) => `<b>(?:${m[1]})</b>: Non-capturing group.` },
        { regex: /\(\?=(.*?)\)/, desc: (m) => `<b>(?=${m[1]})</b>: Positive lookahead.` },
        { regex: /\(\?!(.*?)\)/, desc: (m) => `<b>(?!${m[1]})</b>: Negative lookahead.` }
    ];

    function init() {
        bindEvents();
        loadExamples();
        checkIndicesSupport();
        parseUrlParams();
        evaluate();
    }

    function bindEvents() {
        elements.pattern.addEventListener('input', () => debounce(evaluate));
        elements.text.addEventListener('input', () => {
            updateStats();
            debounce(evaluate);
        });
        elements.text.addEventListener('scroll', syncScroll);
        
        document.querySelectorAll('.regex-flag').forEach(cb => {
            cb.addEventListener('change', evaluate);
        });

        document.getElementById('clearPattern').addEventListener('click', () => {
            elements.pattern.value = '';
            evaluate();
        });

        document.getElementById('clearText').addEventListener('click', () => {
            elements.text.value = '';
            updateStats();
            evaluate();
        });

        document.getElementById('copyPattern').addEventListener('click', () => copyToClipboard(elements.pattern.value, 'Pattern copied!'));
        document.getElementById('copyMatches').addEventListener('click', copyMatches);
        document.getElementById('copyGroups').addEventListener('click', copyGroups);
        document.getElementById('copyExplanation').addEventListener('click', () => copyToClipboard(elements.regexExplanation.innerText, 'Explanation copied!'));
        document.getElementById('copyShareUrl').addEventListener('click', () => copyToClipboard(elements.shareUrl.value, 'Share URL copied!'));
        
        document.getElementById('exportJSON').addEventListener('click', exportJSON);
        document.getElementById('exportCSV').addEventListener('click', exportCSV);
    }

    function debounce(func, timeout = 300) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func(), timeout);
    }

    function syncScroll() {
        elements.backdrop.scrollTop = elements.text.scrollTop;
        elements.backdrop.scrollLeft = elements.text.scrollLeft;
    }

    function checkIndicesSupport() {
        try {
            new RegExp('a', 'd');
        } catch (e) {
            document.getElementById('flagDContainer').classList.add('d-none');
        }
    }

    function loadExamples() {
        examples.forEach((ex, index) => {
            const btn = document.createElement('button');
            btn.className = 'list-group-item list-group-item-action py-2';
            btn.innerHTML = `<div class="fw-bold">${ex.name}</div><code class="x-small">${escapeHtml(ex.pattern)}</code>`;
            btn.addEventListener('click', () => loadExample(index));
            elements.examplesList.appendChild(btn);
        });
    }

    function loadExample(index) {
        const ex = examples[index];
        elements.pattern.value = ex.pattern;
        elements.text.value = ex.text;
        
        document.querySelectorAll('.regex-flag').forEach(cb => {
            cb.checked = ex.flags.includes(cb.value);
        });

        updateStats();
        evaluate();
        showToast(`Loaded example: ${ex.name}`, 'success');
    }

    function getFlags() {
        let flags = '';
        document.querySelectorAll('.regex-flag:checked').forEach(cb => {
            flags += cb.value;
        });
        return flags;
    }

    function evaluate() {
        const pattern = elements.pattern.value;
        const flags = getFlags();
        const text = elements.text.value;

        elements.patternError.classList.add('d-none');
        elements.patternLength.textContent = pattern.length;
        
        if (!pattern) {
            elements.highlights.innerHTML = escapeHtml(text);
            elements.totalMatches.textContent = '0';
            elements.matchTableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted italic">No pattern entered</td></tr>';
            elements.captureGroupsCard.classList.add('d-none');
            elements.regexExplanation.innerHTML = '<div class="text-muted italic">Enter a valid regex pattern to see the explanation.</div>';
            updateShareUrl();
            return;
        }

        try {
            const startTime = performance.now();
            const regex = new RegExp(pattern, flags);
            const matches = [];
            let match;

            if (flags.includes('g')) {
                while ((match = regex.exec(text)) !== null) {
                    if (match.index === regex.lastIndex) regex.lastIndex++;
                    matches.push(match);
                    if (matches.length > 1000) break; // Safety limit
                }
            } else {
                match = regex.exec(text);
                if (match) matches.push(match);
            }

            const endTime = performance.now();
            elements.executionTime.classList.remove('d-none');
            elements.executionTime.querySelector('span').textContent = (endTime - startTime).toFixed(3);

            renderResults(matches, text);
            generateExplanation(pattern);
            updateShareUrl();

        } catch (e) {
            elements.patternError.classList.remove('d-none');
            elements.patternErrorMessage.textContent = e.message;
            elements.highlights.innerHTML = escapeHtml(text);
            elements.totalMatches.textContent = '0';
        }
    }

    function renderResults(matches, text) {
        elements.totalMatches.textContent = matches.length;
        
        // Render Highlighting
        let highlighted = '';
        let lastIdx = 0;

        matches.forEach((m, i) => {
            highlighted += escapeHtml(text.substring(lastIdx, m.index));
            highlighted += `<span class="match-highlight">${escapeHtml(m[0])}</span>`;
            lastIdx = m.index + m[0].length;
        });
        highlighted += escapeHtml(text.substring(lastIdx));
        
        // Add a dummy char for scrolling sync fix if ends with newline
        if (text.endsWith('\n')) highlighted += '\n';

        elements.highlights.innerHTML = highlighted;

        // Render Table
        if (matches.length === 0) {
            elements.matchTableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted italic">No matches found</td></tr>';
            elements.captureGroupsCard.classList.add('d-none');
        } else {
            elements.matchTableBody.innerHTML = matches.map((m, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td class="font-monospace text-break">${escapeHtml(m[0])}</td>
                    <td>${m.index}</td>
                    <td>${m.index + m[0].length}</td>
                    <td>${m[0].length}</td>
                </tr>
            `).join('');

            renderCaptureGroups(matches[0]);
        }
    }

    function renderCaptureGroups(match) {
        if (match.length <= 1 && !match.groups) {
            elements.captureGroupsCard.classList.add('d-none');
            return;
        }

        elements.captureGroupsCard.classList.remove('d-none');
        let html = '';

        // Full Match
        html += `<div class="group-item">
            <div class="group-label">Group 0 (Full Match)</div>
            <code class="group-value">${escapeHtml(match[0])}</code>
        </div>`;

        // Capture Groups
        for (let i = 1; i < match.length; i++) {
            html += `<div class="group-item">
                <div class="group-label">Group ${i}</div>
                <code class="group-value">${match[i] !== undefined ? escapeHtml(match[i]) : '<span class="text-muted italic">undefined</span>'}</code>
            </div>`;
        }

        // Named Groups
        if (match.groups) {
            for (const [name, value] of Object.entries(match.groups)) {
                html += `<div class="group-item border-success">
                    <div class="group-label text-success">Group &lt;${name}&gt;</div>
                    <code class="group-value">${value !== undefined ? escapeHtml(value) : '<span class="text-muted italic">undefined</span>'}</code>
                </div>`;
            }
        }

        elements.captureGroupsContent.innerHTML = html;
    }

    function generateExplanation(pattern) {
        let explanation = '<ul class="list-unstyled mb-0">';
        let found = false;

        explanationRules.forEach(rule => {
            const matches = pattern.matchAll(new RegExp(rule.regex, 'g'));
            for (const m of matches) {
                const desc = typeof rule.desc === 'function' ? rule.desc(m) : rule.desc;
                explanation += `<li class="mb-2">${desc}</li>`;
                found = true;
            }
        });

        if (!found) {
            explanation += '<li class="text-muted italic">No common patterns identified.</li>';
        }
        explanation += '</ul>';
        elements.regexExplanation.innerHTML = explanation;
    }

    function updateStats() {
        const text = elements.text.value;
        elements.charCount.textContent = text.length;
        elements.lineCount.textContent = text ? text.split('\n').length : 0;
        elements.wordCount.textContent = text ? text.trim().split(/\s+/).length : 0;
    }

    function parseUrlParams() {
        const params = new URLSearchParams(window.location.search);
        if (params.has('pattern')) {
            elements.pattern.value = params.get('pattern');
        }
        if (params.has('text')) {
            elements.text.value = params.get('text');
        }
        if (params.has('flags')) {
            const flags = params.get('flags');
            document.querySelectorAll('.regex-flag').forEach(cb => {
                cb.checked = flags.includes(cb.value);
            });
        }
        updateStats();
    }

    function updateShareUrl() {
        const pattern = elements.pattern.value;
        const text = elements.text.value;
        const flags = getFlags();
        
        if (!pattern && !text) {
            elements.shareUrl.value = window.location.origin + window.location.pathname;
            elements.shareStatus.classList.add('d-none');
            return;
        }

        const params = new URLSearchParams();
        if (pattern) params.set('pattern', pattern);
        if (text) params.set('text', text);
        if (flags) params.set('flags', flags);

        const url = window.location.origin + window.location.pathname + '?' + params.toString();
        elements.shareUrl.value = url;
        elements.shareStatus.classList.remove('d-none');
        
        // Update browser URL without reload
        window.history.replaceState({}, '', url);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function copyToClipboard(text, message) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => {
            showToast(message, 'success');
        });
    }

    function copyMatches() {
        const matches = Array.from(document.querySelectorAll('#matchTableBody tr td:nth-child(2)'))
            .map(td => td.innerText)
            .join('\n');
        copyToClipboard(matches, 'All matches copied!');
    }

    function copyGroups() {
        const groups = Array.from(elements.captureGroupsContent.querySelectorAll('.group-value'))
            .map(code => code.innerText)
            .join('\n');
        copyToClipboard(groups, 'All groups copied!');
    }

    function exportJSON() {
        const data = {
            pattern: elements.pattern.value,
            flags: getFlags(),
            text: elements.text.value,
            matches: Array.from(document.querySelectorAll('#matchTableBody tr')).map(tr => {
                const tds = tr.querySelectorAll('td');
                if (tds.length < 5) return null;
                return {
                    index: tds[0].innerText,
                    match: tds[1].innerText,
                    start: tds[2].innerText,
                    end: tds[3].innerText,
                    length: tds[4].innerText
                };
            }).filter(i => i)
        };
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        downloadBlob(blob, 'regex-results.json');
    }

    function exportCSV() {
        let csv = 'Match #,Text,Start,End,Length\n';
        document.querySelectorAll('#matchTableBody tr').forEach(tr => {
            const tds = tr.querySelectorAll('td');
            if (tds.length < 5) return;
            csv += `"${tds[0].innerText}","${tds[1].innerText.replace(/"/g, '""')}","${tds[2].innerText}","${tds[3].innerText}","${tds[4].innerText}"\n`;
        });
        const blob = new Blob([csv], { type: 'text/csv' });
        downloadBlob(blob, 'regex-results.csv');
    }

    function downloadBlob(blob, filename) {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
        showToast('File exported successfully!', 'success');
    }

    return { init };
})();

document.addEventListener('DOMContentLoaded', function() {
    RegexTool.init();
});
</script>
@endpush
