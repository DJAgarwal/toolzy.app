@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/api-tester.css') }}?v={{ filemtime(public_path('css/api-tester.css')) }}">
@endpush

@section('content')
<div class="api-tester-app">
    <x-ui-trust-indicator />
    {{-- Top Action & Environment Toolbar --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <select id="envSelector" class="form-select form-select-sm border-secondary-subtle" style="max-width: 220px;" aria-label="Environment Selector">
                <option value="Default">Environment: Default</option>
            </select>
            <button type="button" class="btn btn-outline-secondary btn-sm text-nowrap d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#envModal">
                <i class="bi bi-gear-fill me-1"></i> Variables
            </button>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" id="loadSampleApiBtn" class="btn btn-outline-info btn-sm text-nowrap d-inline-flex align-items-center">
                <i class="bi bi-lightning-charge-fill me-1"></i> Load Example API
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm text-nowrap d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-box-arrow-in-down me-1"></i> Import (cURL/Postman)
            </button>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle text-nowrap d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-box-arrow-up me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" id="exportPostmanBtn"><i class="bi bi-filetype-json me-2"></i> Postman Collection (v2.1)</a></li>
                    <li><a class="dropdown-item" href="#" id="exportMarkdownBtn"><i class="bi bi-filetype-md me-2"></i> Markdown Documentation</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Request Bar (Method + URL + Send) --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-2 p-md-3">
            <div class="input-group request-bar-input-group">
                <select id="requestMethodSelect" class="form-select method-select fw-bold badge-method-GET" style="max-width: 130px;" aria-label="HTTP Method">
                    <option value="GET" selected>GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="PATCH">PATCH</option>
                    <option value="DELETE">DELETE</option>
                    <option value="HEAD">HEAD</option>
                    <option value="OPTIONS">OPTIONS</option>
                </select>

                <input type="url" id="requestUrlInput" class="form-control url-input-field" placeholder="https://api.example.com/users" value="https://jsonplaceholder.typicode.com/users/1" aria-label="API Endpoint URL" autocomplete="off">

                <button id="sendRequestBtn" class="btn btn-primary px-3 px-md-4 fw-bold d-flex align-items-center" type="button">
                    <i class="bi bi-send-fill me-1"></i> Send
                    <span class="badge bg-white bg-opacity-25 ms-2 d-none d-md-inline" style="font-size: 0.7rem;">Ctrl+Enter</span>
                </button>

                <button id="saveRequestBtn" class="btn btn-outline-secondary px-3 fw-semibold d-flex align-items-center" type="button" data-bs-toggle="modal" data-bs-target="#saveCollectionModal" title="Save API request to a folder">
                    <i class="bi bi-bookmark-plus me-1 text-primary"></i> Save
                </button>
            </div>
        </div>
    </div>

    {{-- Main Workspace Grid --}}
    <div class="row g-4">
        
        {{-- Left / Center Column: Request Builder & Response Viewer --}}
        <div class="col-lg-8">

            {{-- Request Configuration Tabs --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <ul class="nav nav-tabs card-header-tabs" id="requestTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold" id="params-tab" data-bs-toggle="tab" data-bs-target="#params-sec" type="button" role="tab" aria-selected="true">
                                Params <span class="badge bg-secondary rounded-pill ms-1" id="paramsCountBadge">2</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="headers-tab" data-bs-toggle="tab" data-bs-target="#headers-sec" type="button" role="tab" aria-selected="false">
                                Headers <span class="badge bg-secondary rounded-pill ms-1" id="headersCountBadge">2</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="auth-tab" data-bs-toggle="tab" data-bs-target="#auth-sec" type="button" role="tab" aria-selected="false">
                                Auth
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="body-tab" data-bs-toggle="tab" data-bs-target="#body-sec" type="button" role="tab" aria-selected="false">
                                Body
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="requestTabContent">
                        
                        {{-- 1. Params Tab --}}
                        <div class="tab-pane fade show active" id="params-sec" role="tabpanel" aria-labelledby="params-tab">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">Query Parameters (automatically synced with URL string)</span>
                                <div>
                                    <button type="button" class="btn btn-outline-primary btn-sm me-1" id="addQueryParamBtn"><i class="bi bi-plus-lg"></i> Add Parameter</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearQueryParamsBtn"><i class="bi bi-x-circle"></i> Clear</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm kv-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 40px;">Use</th>
                                            <th>Key</th>
                                            <th>Value</th>
                                            <th class="text-center" style="width: 50px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="queryParamsTbody"></tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 2. Headers Tab --}}
                        <div class="tab-pane fade" id="headers-sec" role="tabpanel" aria-labelledby="headers-tab">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small text-muted me-1">Presets:</span>
                                    <button type="button" class="btn btn-light btn-sm border btn-preset-header" data-preset="json">+ Content-Type: JSON</button>
                                    <button type="button" class="btn btn-light btn-sm border btn-preset-header" data-preset="accept_json">+ Accept: JSON</button>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addHeaderBtn"><i class="bi bi-plus-lg"></i> Add Header</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm kv-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 40px;">Use</th>
                                            <th>Header Name</th>
                                            <th>Header Value</th>
                                            <th class="text-center" style="width: 50px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="headersTbody"></tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 3. Auth Tab --}}
                        <div class="tab-pane fade" id="auth-sec" role="tabpanel" aria-labelledby="auth-tab">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="authTypeSelect" class="form-label small fw-semibold">Authentication Type</label>
                                    <select id="authTypeSelect" class="form-select form-select-sm" aria-label="Authentication Type">
                                        <option value="none" selected>No Auth</option>
                                        <option value="bearer">Bearer Token (JWT / OAuth2)</option>
                                        <option value="basic">Basic Auth (Username / Password)</option>
                                        <option value="apikey">API Key</option>
                                        <option value="jwt">JWT Payload Decoder</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    {{-- Bearer Section --}}
                                    <div id="authSec_bearer" class="auth-section d-none">
                                        <label for="authBearerTokenInput" class="form-label small fw-semibold">Bearer Token</label>
                                        <input type="text" id="authBearerTokenInput" class="form-control form-control-sm font-monospace" placeholder="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...">
                                    </div>

                                    {{-- Basic Auth Section --}}
                                    <div id="authSec_basic" class="auth-section d-none">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label for="authBasicUserInput" class="form-label small fw-semibold">Username</label>
                                                <input type="text" id="authBasicUserInput" class="form-control form-control-sm" placeholder="admin">
                                            </div>
                                            <div class="col-6">
                                                <label for="authBasicPassInput" class="form-label small fw-semibold">Password</label>
                                                <input type="password" id="authBasicPassInput" class="form-control form-control-sm" placeholder="••••••••">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- API Key Section --}}
                                    <div id="authSec_apikey" class="auth-section d-none">
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <label for="authApiKeyNameInput" class="form-label small fw-semibold">Key Name</label>
                                                <input type="text" id="authApiKeyNameInput" class="form-control form-control-sm" placeholder="X-API-Key">
                                            </div>
                                            <div class="col-5">
                                                <label for="authApiKeyValueInput" class="form-label small fw-semibold">Key Value</label>
                                                <input type="text" id="authApiKeyValueInput" class="form-control form-control-sm" placeholder="secret_val_123">
                                            </div>
                                            <div class="col-3">
                                                <label for="authApiKeyLocSelect" class="form-label small fw-semibold">Add To</label>
                                                <select id="authApiKeyLocSelect" class="form-select form-select-sm">
                                                    <option value="header">Header</option>
                                                    <option value="query">Query Params</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- JWT Decoder Section --}}
                                    <div id="authSec_jwt" class="auth-section d-none">
                                        <label for="authJwtTokenInput" class="form-label small fw-semibold">JWT Token string to decode</label>
                                        <input type="text" id="authJwtTokenInput" class="form-control form-control-sm font-monospace mb-2" placeholder="Paste JWT token here...">
                                        <pre id="jwtPayloadOutput" class="bg-dark text-success p-2 rounded small font-monospace mb-0" style="max-height: 120px; overflow:auto;">Paste a JWT token to decode payload.</pre>
                                    </div>

                                    {{-- No Auth Default --}}
                                    <div id="authSec_none" class="auth-section">
                                        <p class="text-muted small mb-0 pt-4">No authorization header will be attached to this request.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 4. Body Tab --}}
                        <div class="tab-pane fade" id="body-sec" role="tabpanel" aria-labelledby="body-tab">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="bodyTypeSelect" class="small fw-semibold mb-0">Body Format:</label>
                                    <select id="bodyTypeSelect" class="form-select form-select-sm" style="max-width: 180px;" aria-label="Request Body Format">
                                        <option value="none">none (no payload)</option>
                                        <option value="json" selected>JSON (application/json)</option>
                                        <option value="xml">XML (application/xml)</option>
                                        <option value="form">Form Data (multipart)</option>
                                        <option value="urlencoded">x-www-form-urlencoded</option>
                                        <option value="raw">Raw / Text</option>
                                    </select>
                                </div>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-outline-success btn-sm" id="formatJsonBodyBtn"><i class="bi bi-magic"></i> Beautify JSON</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="minifyJsonBodyBtn">Minify</button>
                                </div>
                            </div>

                            {{-- Raw / JSON / XML Textarea --}}
                            <div id="bodySec_json" class="body-section">
                                <textarea id="requestBodyTextarea" class="form-control code-editor-textarea" rows="7" placeholder='{\n  "key": "value"\n}'></textarea>
                            </div>

                            {{-- Form Data Section --}}
                            <div id="bodySec_form" class="body-section d-none">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm kv-table mb-2">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Key</th>
                                                <th>Value</th>
                                                <th class="text-center" style="width: 50px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="formDataTbody"></tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addFormDataBtn"><i class="bi bi-plus-lg"></i> Add Field</button>
                            </div>

                            {{-- URL Encoded Section --}}
                            <div id="bodySec_urlencoded" class="body-section d-none">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm kv-table mb-2">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Key</th>
                                                <th>Value</th>
                                                <th class="text-center" style="width: 50px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="urlEncodedTbody"></tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addUrlEncodedBtn"><i class="bi bi-plus-lg"></i> Add Field</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Response Container & Results --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex align-items-center justify-content-between flex-wrap py-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-body-text text-primary me-2"></i> Response & Performance Analysis</h6>
                    <div id="jsonStatsBar"></div>
                </div>
                <div class="card-body">
                    
                    {{-- Status Header Pill --}}
                    <div id="responseStatusContainer">
                        <div class="alert alert-light border text-center text-muted py-4 mb-0">
                            <i class="bi bi-send fs-2 d-block mb-2 text-secondary"></i>
                            Click <strong>Send</strong> above to execute API request and inspect response metrics.
                        </div>
                    </div>

                    {{-- Response Navigation Tabs --}}
                    <ul class="nav nav-tabs mb-3" id="responseTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold" id="res-tree-tab" data-bs-toggle="tab" data-bs-target="#res-tree-sec" type="button" role="tab" aria-selected="true">
                                <i class="bi bi-diagram-3-fill me-1"></i> Pretty JSON Tree
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="res-raw-tab" data-bs-toggle="tab" data-bs-target="#res-raw-sec" type="button" role="tab" aria-selected="false">
                                Raw Body
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="res-html-tab" data-bs-toggle="tab" data-bs-target="#res-html-sec" type="button" role="tab" aria-selected="false">
                                HTML Preview
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="res-perf-tab" data-bs-toggle="tab" data-bs-target="#res-perf-sec" type="button" role="tab" aria-selected="false">
                                <i class="bi bi-speedometer2 me-1"></i> Performance
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="res-code-tab" data-bs-toggle="tab" data-bs-target="#res-code-sec" type="button" role="tab" aria-selected="false">
                                <i class="bi bi-code-slash me-1"></i> Code Generator
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="res-headers-tab" data-bs-toggle="tab" data-bs-target="#res-headers-sec" type="button" role="tab" aria-selected="false">
                                Headers Audit
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="responseTabContent">
                        
                        {{-- 1. JSON Tree Viewer --}}
                        <div class="tab-pane fade show active" id="res-tree-sec" role="tabpanel" aria-labelledby="res-tree-tab">
                            <div class="json-tree-container" id="responseJsonTree">
                                <span class="text-muted italic">Response output will be formatted as an interactive tree view here.</span>
                            </div>
                        </div>

                        {{-- 2. Raw Body --}}
                        <div class="tab-pane fade" id="res-raw-sec" role="tabpanel" aria-labelledby="res-raw-tab">
                            <pre class="bg-dark text-light p-3 rounded font-monospace small" id="responseRawBody" style="max-height: 450px; overflow: auto;">No response data yet.</pre>
                        </div>

                        {{-- 3. HTML Preview --}}
                        <div class="tab-pane fade" id="res-html-sec" role="tabpanel" aria-labelledby="res-html-tab">
                            <div class="border rounded overflow-hidden" style="height: 450px;">
                                <iframe id="responseHtmlIframe" class="w-100 h-100 border-0" sandbox="allow-same-origin" title="Sandboxed HTML Preview"></iframe>
                            </div>
                            <img id="responseImagePreview" src="" alt="Response Image Preview" class="img-fluid rounded border mt-2 d-none">
                        </div>

                        {{-- 4. Performance Dashboard --}}
                        <div class="tab-pane fade" id="res-perf-sec" role="tabpanel" aria-labelledby="res-perf-tab">
                            <div id="perfDashboardContainer">
                                <div class="text-muted text-center py-4">Execute a request to view performance benchmark metrics.</div>
                            </div>
                        </div>

                        {{-- 5. Code Generator --}}
                        <div class="tab-pane fade" id="res-code-sec" role="tabpanel" aria-labelledby="res-code-tab">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="codeLangSelect" class="small fw-semibold mb-0">Language:</label>
                                    <select id="codeLangSelect" class="form-select form-select-sm" style="max-width: 220px;" aria-label="Code Generator Language">
                                        <option value="curl" selected>cURL Command</option>
                                        <option value="javascript">JavaScript (fetch)</option>
                                        <option value="axios">JavaScript (Axios)</option>
                                        <option value="laravel">PHP (Laravel Http Client)</option>
                                        <option value="php">PHP (cURL native)</option>
                                        <option value="python">Python (requests)</option>
                                        <option value="node">Node.js (fetch)</option>
                                        <option value="go">Go (net/http)</option>
                                        <option value="java">Java (HttpClient)</option>
                                        <option value="csharp">C# (HttpClient)</option>
                                    </select>
                                </div>
                                <button type="button" id="copyCodeSnippetBtn" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-clipboard me-1"></i> Copy Code
                                </button>
                            </div>
                            <pre id="generatedCodeSnippet" class="snippet-pre mb-0">curl -X GET "https://jsonplaceholder.typicode.com/users/1"</pre>
                        </div>

                        {{-- 6. Response Headers Audit --}}
                        <div class="tab-pane fade" id="res-headers-sec" role="tabpanel" aria-labelledby="res-headers-tab">
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm font-monospace mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Header Key</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody id="responseHeadersTableBody">
                                        <tr><td colspan="2" class="text-muted text-center py-2">No headers to display yet.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="securityAnalysisContainer"></div>
                            <div id="responseCookiesContainer" class="mt-3"></div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- Right Sidebar Column: Local History & Saved Collections --}}
        <div class="col-lg-4">
            
            {{-- Local Request History Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-primary me-2"></i> Request History</h6>
                    <button type="button" class="btn btn-link text-danger btn-sm p-0" id="clearHistoryBtn">Clear All</button>
                </div>
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    <div id="historyListContainer"></div>
                </div>
            </div>

            {{-- Collections Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex align-items-center justify-content-between py-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-folder2-open text-warning me-2"></i> Collections</h6>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addCollectionFolderBtn">
                        + New Folder
                    </button>
                </div>
                <div class="card-body p-3" id="collectionsContainer"></div>
            </div>

        </div>

    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="importModalLabel"><i class="bi bi-box-arrow-in-down text-primary me-2"></i> Import Request Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted">Paste a <strong>cURL command</strong>, <strong>Postman Collection (v2.1) JSON</strong>, or <strong>OpenAPI/Swagger specification</strong> below to automatically extract endpoint, method, headers, and body:</p>
                <textarea id="importSourceTextarea" class="form-control font-monospace small" rows="8" placeholder="curl -X POST 'https://api.example.com/login' -H 'Content-Type: application/json' -d '&#123;&quot;user&quot;:&quot;admin&quot;&#125;'"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmImportBtn" class="btn btn-primary fw-bold">Import Now</button>
            </div>
        </div>
    </div>
</div>

{{-- Environment Modal --}}
<div class="modal fade" id="envModal" tabindex="-1" aria-labelledby="envModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-sm">
            <div class="modal-header bg-light py-2">
                <h5 class="modal-title fw-bold text-dark fs-6" id="envModalLabel">
                    <i class="bi bi-sliders text-primary me-2"></i> Environment Variables Manager
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-3">
                    Define variables like <code>base_url</code>, <code>token</code>, or <code>api_key</code> and reuse them anywhere in your requests as <code>&#123;&#123;variable_name&#125;&#125;</code> (URL, Headers, Params, Body).
                </p>
                
                {{-- Environment Toolbar --}}
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 bg-light p-2 rounded border">
                    <div class="d-flex align-items-center gap-2">
                        <label for="modalEnvSelect" class="small fw-semibold mb-0">Active Environment:</label>
                        <select id="modalEnvSelect" class="form-select form-select-sm border-secondary-subtle" style="max-width: 220px;" aria-label="Modal Environment Select">
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnCreateNewEnv">
                            <i class="bi bi-plus-circle me-1"></i> New Environment
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" id="btnDeleteActiveEnv" title="Delete current environment">
                            <i class="bi bi-trash me-1"></i> Delete Env
                        </button>
                    </div>
                </div>

                {{-- Variables Table --}}
                <div class="table-responsive border rounded mb-3" style="max-height: 320px; overflow-y: auto;">
                    <table class="table table-bordered table-hover table-sm align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width: 35%;">Variable Name (Key)</th>
                                <th>Value</th>
                                <th class="text-center" style="width: 60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="envVarsTableBody">
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-primary btn-sm fw-bold" id="btnAddEnvVarRow">
                        <i class="bi bi-plus-lg me-1"></i> Add Variable
                    </button>
                    <span class="small text-muted"><i class="bi bi-info-circle me-1"></i> Changes auto-save instantly</span>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Save to Collection Modal --}}
<div class="modal fade" id="saveCollectionModal" tabindex="-1" aria-labelledby="saveCollectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-sm">
            <div class="modal-header bg-light py-2">
                <h5 class="modal-title fw-bold text-dark fs-6" id="saveCollectionModalLabel">
                    <i class="bi bi-bookmark-plus text-primary me-2"></i> Save API Request to Folder
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="saveReqNameInput" class="form-label small fw-semibold">Request Display Name</label>
                    <input type="text" id="saveReqNameInput" class="form-control form-control-sm" placeholder="e.g. Send Login OTP">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="saveReqFolderSelect" class="form-label small fw-semibold mb-0">Target Collection Folder</label>
                        <button type="button" class="btn btn-link text-primary p-0 text-decoration-none" id="btnCreateFolderFromSaveModal" style="font-size: 0.78rem;">
                            + Create New Folder
                        </button>
                    </div>
                    <select id="saveReqFolderSelect" class="form-select form-select-sm">
                    </select>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm fw-bold" id="confirmSaveToCollectionBtn">
                    <i class="bi bi-folder-plus me-1"></i> Save to Folder
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}" src="{{ asset('js/api-tester/Engine.js') }}?v={{ filemtime(public_path('js/api-tester/Engine.js')) }}"></script>
<script nonce="{{ $cspNonce }}" src="{{ asset('js/api-tester/EnvManager.js') }}?v={{ filemtime(public_path('js/api-tester/EnvManager.js')) }}"></script>
<script nonce="{{ $cspNonce }}" src="{{ asset('js/api-tester/CodeGenerator.js') }}?v={{ filemtime(public_path('js/api-tester/CodeGenerator.js')) }}"></script>
<script nonce="{{ $cspNonce }}" src="{{ asset('js/api-tester/HistoryManager.js') }}?v={{ filemtime(public_path('js/api-tester/HistoryManager.js')) }}"></script>
<script nonce="{{ $cspNonce }}" src="{{ asset('js/api-tester/CollectionManager.js') }}?v={{ filemtime(public_path('js/api-tester/CollectionManager.js')) }}"></script>
<script nonce="{{ $cspNonce }}" src="{{ asset('js/api-tester/ImportExport.js') }}?v={{ filemtime(public_path('js/api-tester/ImportExport.js')) }}"></script>
<script nonce="{{ $cspNonce }}" src="{{ asset('js/api-tester/PerformanceAnalyzer.js') }}?v={{ filemtime(public_path('js/api-tester/PerformanceAnalyzer.js')) }}"></script>
<script nonce="{{ $cspNonce }}" src="{{ asset('js/api-tester/ResponseViewer.js') }}?v={{ filemtime(public_path('js/api-tester/ResponseViewer.js')) }}"></script>
<script nonce="{{ $cspNonce }}" src="{{ asset('js/api-tester/RequestBuilder.js') }}?v={{ filemtime(public_path('js/api-tester/RequestBuilder.js')) }}"></script>
<script nonce="{{ $cspNonce }}" src="{{ asset('js/api-tester/AppInit.js') }}?v={{ filemtime(public_path('js/api-tester/AppInit.js')) }}"></script>
@endpush
