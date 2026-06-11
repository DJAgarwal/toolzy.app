@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <x-ui-trust-indicator />
    <div class="row g-4">
        {{-- Main Control Column --}}
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <ul class="nav nav-pills card-header-pills" id="generatorTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold" id="words-tab" data-bs-toggle="tab" data-bs-target="#tab-words" type="button" role="tab">Words</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="sentences-tab" data-bs-toggle="tab" data-bs-target="#tab-sentences" type="button" role="tab">Sentences</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="paragraphs-tab" data-bs-toggle="tab" data-bs-target="#tab-paragraphs" type="button" role="tab">Paragraphs</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="lists-tab" data-bs-toggle="tab" data-bs-target="#tab-lists" type="button" role="tab">Lists</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="html-tab" data-bs-toggle="tab" data-bs-target="#tab-html" type="button" role="tab">HTML</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="realistic-tab" data-bs-toggle="tab" data-bs-target="#tab-realistic" type="button" role="tab">Realistic</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body bg-light border-top border-bottom">
                    <div class="tab-content" id="tabContent">
                        {{-- Words Tab --}}
                        <div class="tab-pane fade show active" id="tab-words" role="tabpanel">
                            <div class="row align-items-center g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-uppercase">Number of Words</label>
                                    <input type="number" class="form-control" id="wordCount" value="100" min="1" max="10000">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-uppercase">Type</label>
                                    <select class="form-select" id="wordType">
                                        <option value="lorem">Traditional Lorem Ipsum</option>
                                        <option value="english">English Placeholder</option>
                                        <option value="corporate">Corporate Buzzwords</option>
                                        <option value="tech">Tech Jargon</option>
                                    </select>
                                </div>
                                <div class="col-md-4 pt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="wordStartLorem" checked>
                                        <label class="form-check-label small" for="wordStartLorem">Start with "Lorem Ipsum..."</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Sentences Tab --}}
                        <div class="tab-pane fade" id="tab-sentences" role="tabpanel">
                            <div class="row align-items-center g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-uppercase">Number of Sentences</label>
                                    <input type="number" class="form-control" id="sentenceCount" value="10" min="1" max="1000">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-uppercase">Sentence Length</label>
                                    <select class="form-select" id="sentenceLength">
                                        <option value="short">Short</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="long">Long</option>
                                        <option value="random">Random</option>
                                    </select>
                                </div>
                                <div class="col-md-4 pt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sentenceStartLorem" checked>
                                        <label class="form-check-label small" for="sentenceStartLorem">Start with "Lorem Ipsum..."</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Paragraphs Tab --}}
                        <div class="tab-pane fade" id="tab-paragraphs" role="tabpanel">
                            <div class="row align-items-center g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-uppercase">Number of Paragraphs</label>
                                    <input type="number" class="form-control" id="paragraphCount" value="5" min="1" max="500">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-uppercase">Paragraph Length</label>
                                    <select class="form-select" id="paraLength">
                                        <option value="short">Short (2-3 sentences)</option>
                                        <option value="medium" selected>Medium (4-6 sentences)</option>
                                        <option value="long">Long (7-10 sentences)</option>
                                        <option value="random">Random</option>
                                    </select>
                                </div>
                                <div class="col-md-4 pt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="paraStartLorem" checked>
                                        <label class="form-check-label small" for="paraStartLorem">Start with "Lorem Ipsum..."</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Lists Tab --}}
                        <div class="tab-pane fade" id="tab-lists" role="tabpanel">
                            <div class="row align-items-center g-3">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-uppercase">List Items</label>
                                    <input type="number" class="form-control" id="listItemCount" value="5" min="1" max="100">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-uppercase">List Type</label>
                                    <select class="form-select" id="listType">
                                        <option value="ul">Bulleted (ul)</option>
                                        <option value="ol">Numbered (ol)</option>
                                        <option value="checkbox">Checklist</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-uppercase">Nesting</label>
                                    <select class="form-select" id="listNesting">
                                        <option value="0">None</option>
                                        <option value="1">1 Level</option>
                                        <option value="2">2 Levels</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-uppercase">Item Length</label>
                                    <select class="form-select" id="listItemLength">
                                        <option value="short">Short</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="long">Long</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- HTML Tab --}}
                        <div class="tab-pane fade" id="tab-html" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-uppercase d-block">Elements to Include</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input html-opt" type="checkbox" value="h" id="inclH" checked>
                                            <label class="form-check-label small" for="inclH">Headings</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input html-opt" type="checkbox" value="p" id="inclP" checked>
                                            <label class="form-check-label small" for="inclP">Paragraphs</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input html-opt" type="checkbox" value="list" id="inclList" checked>
                                            <label class="form-check-label small" for="inclList">Lists</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input html-opt" type="checkbox" value="table" id="inclTable">
                                            <label class="form-check-label small" for="inclTable">Tables</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input html-opt" type="checkbox" value="quote" id="inclQuote">
                                            <label class="form-check-label small" for="inclQuote">Blockquotes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input html-opt" type="checkbox" value="form" id="inclForm">
                                            <label class="form-check-label small" for="inclForm">Form Elements</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">Wrapping Structure</label>
                                    <select class="form-select" id="htmlWrap">
                                        <option value="none">None (Fragments)</option>
                                        <option value="section">Section Tags</option>
                                        <option value="article">Article Tags</option>
                                        <option value="div">Div Containers</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">Content Scale</label>
                                    <select class="form-select" id="htmlScale">
                                        <option value="small">Small (Page Section)</option>
                                        <option value="medium" selected>Medium (Full Page)</option>
                                        <option value="large">Large (Complex Mockup)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- Realistic Tab --}}
                        <div class="tab-pane fade" id="tab-realistic" role="tabpanel">
                            <div class="row align-items-center g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">Content Scenario</label>
                                    <select class="form-select" id="realisticScenario">
                                        <option value="blog">Blog Article</option>
                                        <option value="product">Product Description</option>
                                        <option value="news">Breaking News Article</option>
                                        <option value="marketing">Marketing Sales Copy</option>
                                        <option value="landing">Landing Page Content</option>
                                        <option value="faq">FAQ / Help Center</option>
                                        <option value="profile">User Profile Bio</option>
                                        <option value="saas">SaaS Feature List</option>
                                        <option value="dashboard">Dashboard Widget Content</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">Industry / Tone</label>
                                    <select class="form-select" id="realisticTone">
                                        <option value="neutral">Neutral & Professional</option>
                                        <option value="tech">Modern Tech & Startup</option>
                                        <option value="creative">Creative & Artistic</option>
                                        <option value="business">Corporate & Financial</option>
                                        <option value="casual">Friendly & Casual</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body py-3 d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary px-4 fw-bold shadow-sm" id="btnGenerate">
                            <i class="bi bi-gear-fill me-2"></i>Generate Content
                        </button>
                        <button class="btn btn-outline-secondary" id="btnRandomize" title="Randomize Seed">
                            <i class="bi bi-shuffle"></i>
                        </button>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-download me-2"></i>Export As...
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><button class="dropdown-item py-2" id="exportTXT"><i class="bi bi-file-earmark-text me-2"></i>Plain Text (.txt)</button></li>
                            <li><button class="dropdown-item py-2" id="exportHTML"><i class="bi bi-filetype-html me-2"></i>HTML Document (.html)</button></li>
                            <li><button class="dropdown-item py-2" id="exportMD"><i class="bi bi-markdown me-2"></i>Markdown (.md)</button></li>
                            <li><button class="dropdown-item py-2" id="exportJSON"><i class="bi bi-filetype-json me-2"></i>JSON Data (.json)</button></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Output Section --}}
            <div id="outputContainer" class="card shadow-sm border-0 mb-4 d-none">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-file-text me-2"></i>Generated Content
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-light border" id="btnCopyText" title="Copy as Plain Text">
                            <i class="bi bi-clipboard me-1"></i>Copy Text
                        </button>
                        <button class="btn btn-sm btn-light border" id="btnCopyHTML" title="Copy as HTML">
                            <i class="bi bi-code-slash me-1"></i>Copy HTML
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="previewArea" class="p-4 bg-white" style="min-height: 200px; max-height: 600px; overflow-y: auto;">
                        {{-- Content will be rendered here --}}
                    </div>
                    {{-- Raw Textarea for easy selection/copy (hidden) --}}
                    <textarea id="rawOutput" class="d-none"></textarea>
                </div>
                <div class="card-footer bg-light py-2 text-muted small">
                    <div class="row text-center g-2">
                        <div class="col-6 col-md-3 border-end">Words: <strong id="statWords">0</strong></div>
                        <div class="col-6 col-md-3 border-end">Chars: <strong id="statChars">0</strong></div>
                        <div class="col-6 col-md-3 border-end">Paragraphs: <strong id="statParas">0</strong></div>
                        <div class="col-6 col-md-3">Reading Time: <strong id="statTime">0m</strong></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar / Specialized Column --}}
        <div class="col-xl-4">
            {{-- Data Generator Card --}}
            <div class="card shadow-sm border-0 mb-4 h-auto">
                <div class="card-header bg-white py-3 fw-bold">
                    <i class="bi bi-person-badge me-2 text-info"></i>Placeholder Data
                </div>
                <div class="card-body p-3">
                    <p class="small text-muted mb-3">Generate fictional user data for forms and databases.</p>
                    <div class="row g-2">
                        <div class="col-6">
                            <button class="btn btn-sm btn-outline-info w-100 data-gen" data-type="name">Fictional Name</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-sm btn-outline-info w-100 data-gen" data-type="email">Mock Email</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-sm btn-outline-info w-100 data-gen" data-type="address">Fictional Address</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-sm btn-outline-info w-100 data-gen" data-type="phone">Fake Phone</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-sm btn-outline-info w-100 data-gen" data-type="company">Company Name</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-sm btn-outline-info w-100 data-gen" data-type="website">Fake Website</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- UI Testing Mode --}}
            <div class="card shadow-sm border-0 mb-4 h-auto">
                <div class="card-header bg-white py-3 fw-bold">
                    <i class="bi bi-window-sidebar me-2 text-warning"></i>UI Component Text
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush small">
                        <button class="list-group-item list-group-item-action data-gen" data-type="error">Error Messages</button>
                        <button class="list-group-item list-group-item-action data-gen" data-type="success">Success Messages</button>
                        <button class="list-group-item list-group-item-action data-gen" data-type="button">Button Labels</button>
                        <button class="list-group-item list-group-item-action data-gen" data-type="placeholder">Form Placeholders</button>
                        <button class="list-group-item list-group-item-action data-gen" data-type="notification">System Notifications</button>
                    </div>
                </div>
            </div>

            {{-- Example Library --}}
            <div class="card shadow-sm border-0 mb-4 h-auto">
                <div class="card-header bg-white py-3 fw-bold">
                    <i class="bi bi-collection me-2 text-purple"></i>Quick Examples
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush small">
                        <button class="list-group-item list-group-item-action quick-load" data-example="standard">100 Words - Latin</button>
                        <button class="list-group-item list-group-item-action quick-load" data-example="blog">Full Blog Article</button>
                        <button class="list-group-item list-group-item-action quick-load" data-example="faq">Support FAQ Page</button>
                        <button class="list-group-item list-group-item-action quick-load" data-example="product">Product Detail Copy</button>
                        <button class="list-group-item list-group-item-action quick-load" data-example="startup">Startup Pitch Content</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Details & FAQ Sections --}}
    <div class="card shadow-sm border-0 mb-4 mt-4">
        <div class="card-body p-lg-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <h5 class="fw-bold"><i class="bi bi-clock-history me-2"></i>History of Lorem Ipsum</h5>
                    <p class="small text-muted">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, <em>consectetur</em>, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.</p>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold"><i class="bi bi-layout-text-window me-2"></i>Why Designers Use It</h5>
                    <p class="small text-muted">Using placeholder text allows designers to focus on visual elements such as typography, grid, and layout without being influenced by the actual content. It provides a more balanced distribution of letters and words compared to repeating "text here" or "content", which helps in evaluating the "color" and texture of a page layout.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-purple { color: #6f42c1; }
    .bg-purple { background-color: #6f42c1; }
    .nav-pills .nav-link { color: #6c757d; padding: 0.75rem 1.25rem; }
    .nav-pills .nav-link.active { background-color: #0d6efd; color: white; }
    #previewArea { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
    #previewArea h1, #previewArea h2, #previewArea h3 { margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 700; color: #111; }
    #previewArea p { margin-bottom: 1rem; }
    #previewArea ul, #previewArea ol { margin-bottom: 1rem; padding-left: 2rem; }
    #previewArea blockquote { border-left: 4px solid #dee2e6; padding-left: 1rem; font-style: italic; color: #666; margin: 1.5rem 0; }
    #previewArea table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; font-size: 0.9rem; }
    #previewArea th, #previewArea td { border: 1px solid #dee2e6; padding: 0.75rem; text-align: left; }
    #previewArea th { background-color: #f8f9fa; }
    .realistic-snippet { background: #fdfdfd; border-radius: 6px; border-left: 3px solid #0d6efd; padding: 10px 15px; margin-bottom: 10px; }
</style>

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    // Word Banks
    const LATIN = ["lorem", "ipsum", "dolor", "sit", "amet", "consectetur", "adipiscing", "elit", "curabitur", "vel", "hendrerit", "libero", "eleifend", "blandit", "nunc", "ornare", "odio", "ut", "orci", "gravida", "imperdiet", "nullam", "purus", "lacinia", "a", "pretium", "quis", "congue", "praesent", "sagittis", "laoreet", "auctor", "mauris", "non", "velit", "eros", "dictum", "proin", "accumsan", "sapien", "nec", "massa", "volutpat", "venenatis", "sed", "eu", "molestie", "lacus", "quisque", "porttitor", "ligula", "dui", "mollis", "tempus", "at", "magna", "vestibulum", "turpis", "ac", "diam", "tincidunt", "id", "condimentum", "enim", "sodales", "in", "hac", "habitasse", "platea", "dictumst", "aenean", "neque", "fusce", "augue", "leo", "eget", "semper", "mattis", "tortor", "scelerisque", "nulla", "interdum", "tellus", "malesuada", "rhoncus", "porta", "sem", "aliquet", "et", "nam", "suspendisse", "potenti", "vivamus", "luctus", "fringilla", "erat", "donec", "justo", "vehicula", "ultricies", "varius", "ante", "primis", "faucibus", "ultrices", "posuere", "cubilia", "curae", "etiam", "cursus", "aliquam", "quam", "fala", "felis", "convallis", "pharetra", "nisi", "fermentum", "consequat", "maecenas", "iaculis", "pellentesque", "facilisis", "viverra", "sed", "vitae", "nisl", "urna", "metus", "feugiat", "risus"];
    
    const ENGLISH = ["the", "quick", "brown", "fox", "jumps", "over", "lazy", "dog", "every", "single", "day", "in", "field", "behind", "barn", "sun", "shines", "brightly", "birds", "sing", "melodies", "summer", "breeze", "cools", "air", "children", "play", "happily", "near", "river", "bank", "trees", "sway", "gently", "flowers", "bloom", "vibrant", "colors", "nature", "surrounds", "us", "peaceful", "tranquility", "morning", "dew", "glistens", "grass", "mountains", "stand", "tall", "distance", "clouds", "drift", "across", "blue", "sky", "world", "wakes", "up", "new", "possibilities", "adventure", "awaits", "around", "corner", "life", "is", "journey", "worth", "taking", "cherish", "moments", "shared", "friends", "family", "together", "we", "build", "future", "full", "hope", "dreams", "strive", "excellence", "everything", "do", "make", "difference", "positive", "impact", "harmony", "balance", "key", "happiness", "explore", "learn", "grow", "everywhere", "look", "there", "beauty", "found"];

    const TECH = ["cloud", "computing", "artificial", "intelligence", "machine", "learning", "blockchain", "blockchain", "decentralized", "infrastructure", "scalability", "microservices", "frontend", "backend", "fullstack", "database", "query", "optimization", "deployment", "continuous", "integration", "delivery", "automation", "framework", "library", "component", "state", "management", "asynchronous", "asynchronous", "concurrency", "parallelism", "security", "encryption", "authentication", "authorization", "protocol", "endpoint", "api", "restful", "graphql", "serverless", "lambda", "container", "docker", "kubernetes", "virtualization", "networking", "latency", "bandwidth", "responsive", "design", "ux", "ui", "interface", "experience", "interaction", "usability", "accessibility", "analytics", "tracking", "performance", "monitoring", "caching", "edge", "computing", "iot", "internet", "things", "wearable", "smart", "home", "big", "data", "visualization", "algorithm", "abstraction", "encapsulation", "inheritance", "polymorphism", "refactoring", "debugging", "profiling", "testing", "unit", "integration", "end-to-end"];

    const CORPORATE = ["leverage", "synergy", "paradigm", "shift", "strategic", "alignment", "best", "practices", "core", "competency", "deliverables", "holistic", "approach", "value", "proposition", "stakeholder", "engagement", "customer-centric", "solution-oriented", "bandwidth", "low-hanging", "fruit", "deep", "dive", "drill", "down", "scalability", "monetization", "vertical", "integration", "market", "penetration", "brand", "equity", "thought", "leadership", "innovation", "disruption", "agile", "methodology", "scrum", "kanban", "workflow", "optimization", "revenue", "stream", "growth", "hacking", "onboarding", "retention", "user", "acquisition", "conversion", "rate", "churn", "impactful", "game-changer", "moving", "parts", "circle", "back", "offline", "touch", "base", "at", "the", "end", "day", "bottom", "line", "mission", "critical", "pain", "points", "ecosystem", "frictionless", "seamless", "integration", "proactive", "empowerment", "sustainability", "governance", "compliance", "compliance"];

    // Data Banks (Fictional)
    const FIRST_NAMES = ["John", "Jane", "Michael", "Emily", "David", "Sarah", "Alex", "Emma", "Chris", "Olivia", "James", "Sophia", "Robert", "Isabella", "William", "Mia", "Joseph", "Charlotte", "Thomas", "Amelia"];
    const LAST_NAMES = ["Smith", "Johnson", "Williams", "Brown", "Jones", "Garcia", "Miller", "Davis", "Rodriguez", "Martinez", "Hernandez", "Lopez", "Gonzalez", "Wilson", "Anderson", "Thomas", "Taylor", "Moore", "Jackson", "Martin"];
    const DOMAINS = ["example.com", "mocktest.io", "sampleweb.net", "fakesite.org", "dummymail.co"];
    const CITIES = ["Springfield", "Riverton", "Lakeside", "Mountainview", "Fairview", "Oakwood", "Greenville", "Bridgeport", "Brookhaven", "Westfield"];
    const COUNTRIES = ["United States", "Canada", "United Kingdom", "Australia", "Germany", "France", "Japan", "Brazil", "India", "Italy"];
    const COMPANIES = ["TechFlow Solutions", "Starlight Media", "BlueWave Systems", "Peak Performance Co", "Global Reach Inc", "Nova Dynamics", "SilverLeaf Agency", "EverGreen Labs", "Swift Logic", "Prime Vision"];

    // DOM Elements
    const btnGenerate = document.getElementById('btnGenerate');
    const previewArea = document.getElementById('previewArea');
    const rawOutput = document.getElementById('rawOutput');
    const outputContainer = document.getElementById('outputContainer');

    // Stats Elements
    const statWords = document.getElementById('statWords');
    const statChars = document.getElementById('statChars');
    const statParas = document.getElementById('statParas');
    const statTime = document.getElementById('statTime');

    // State
    let currentMode = 'words';
    let seed = Math.random();

    // Event Listeners
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', (e) => {
            currentMode = e.target.id.replace('-tab', '');
            generate();
        });
    });

    // Auto-update on any input change
    document.querySelectorAll('input, select').forEach(el => {
        el.addEventListener('change', generate);
        if (el.tagName === 'INPUT') el.addEventListener('input', generate);
    });

    btnGenerate.addEventListener('click', generate);
    document.getElementById('btnRandomize').addEventListener('click', () => {
        seed = Math.random();
        showNotify('Random seed updated', 'info');
        generate();
    });

    // Copy/Export Events
    document.getElementById('btnCopyText').addEventListener('click', () => copyToClipboard(rawOutput.value, 'Text copied!'));
    document.getElementById('btnCopyHTML').addEventListener('click', () => copyToClipboard(previewArea.innerHTML, 'HTML copied!'));
    
    document.getElementById('exportTXT').addEventListener('click', () => download(rawOutput.value, 'lorem-ipsum.txt', 'text/plain'));
    document.getElementById('exportHTML').addEventListener('click', () => {
        const fullHtml = `<!DOCTYPE html><html><head><title>Placeholder Content</title></head><body>${previewArea.innerHTML}</body></html>`;
        download(fullHtml, 'lorem-ipsum.html', 'text/html');
    });
    document.getElementById('exportMD').addEventListener('click', () => {
        let md = rawOutput.value.replace(/\n\n/g, '\n\n'); // Simple MD conversion
        download(md, 'lorem-ipsum.md', 'text/markdown');
    });
    document.getElementById('exportJSON').addEventListener('click', () => {
        const data = {
            content: rawOutput.value,
            html: previewArea.innerHTML,
            stats: {
                words: statWords.textContent,
                chars: statChars.textContent,
                paras: statParas.textContent
            }
        };
        download(JSON.stringify(data, null, 2), 'lorem-ipsum.json', 'application/json');
    });

    // Specialized Generators
    document.querySelectorAll('.data-gen').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const type = e.target.getAttribute('data-type');
            generateData(type);
        });
    });

    document.querySelectorAll('.quick-load').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const example = e.target.getAttribute('data-example');
            loadExample(example);
        });
    });

    function showNotify(msg, type = 'success') {
        if (typeof showToast === 'function') {
            showToast(msg, type);
        } else {
            alert(msg);
        }
    }

    function copyToClipboard(text, msg) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => showNotify(msg));
    }

    function download(content, filename, type) {
        const blob = new Blob([content], { type });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
    }

    // Generator Logic
    function getWords(count, type = 'lorem', startLorem = false) {
        let bank = LATIN;
        if (type === 'english') bank = ENGLISH;
        if (type === 'tech') bank = TECH;
        if (type === 'corporate') bank = CORPORATE;

        let result = [];
        if (startLorem && type === 'lorem') {
            result = ["Lorem", "ipsum", "dolor", "sit", "amet,"];
        }

        while (result.length < count) {
            const word = bank[Math.floor(Math.random() * bank.length)];
            result.push(word);
        }

        // Limit if count was small
        result = result.slice(0, count);

        // Capitalize first
        result[0] = result[0].charAt(0).toUpperCase() + result[0].slice(1);
        
        return result.join(' ') + (type === 'lorem' ? '.' : '');
    }

    function generateSentence(length = 'medium', type = 'lorem') {
        let bank = (type === 'english') ? ENGLISH : LATIN;
        let count = 8;
        if (length === 'short') count = Math.floor(Math.random() * 5) + 3;
        if (length === 'medium') count = Math.floor(Math.random() * 10) + 7;
        if (length === 'long') count = Math.floor(Math.random() * 15) + 12;
        if (length === 'random') count = Math.floor(Math.random() * 20) + 5;

        let words = [];
        for (let i = 0; i < count; i++) {
            words.push(bank[Math.floor(Math.random() * bank.length)]);
        }

        let s = words.join(' ');
        s = s.charAt(0).toUpperCase() + s.slice(1) + '.';
        return s;
    }

    function generate() {
        let content = '';
        let html = '';

        if (currentMode === 'words') {
            const count = parseInt(document.getElementById('wordCount').value);
            const type = document.getElementById('wordType').value;
            const startLorem = document.getElementById('wordStartLorem').checked;
            content = getWords(count, type, startLorem);
            html = `<p>${content}</p>`;
        } 
        else if (currentMode === 'sentences') {
            const count = parseInt(document.getElementById('sentenceCount').value);
            const length = document.getElementById('sentenceLength').value;
            const startLorem = document.getElementById('sentenceStartLorem').checked;
            
            let sentences = [];
            if (startLorem) sentences.push("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
            
            while (sentences.length < count) {
                sentences.push(generateSentence(length));
            }
            content = sentences.join(' ');
            html = `<p>${content}</p>`;
        }
        else if (currentMode === 'paragraphs') {
            const count = parseInt(document.getElementById('paragraphCount').value);
            const length = document.getElementById('paraLength').value;
            const startLorem = document.getElementById('paraStartLorem').checked;
            
            let paras = [];
            for (let i = 0; i < count; i++) {
                let sCount = 5;
                if (length === 'short') sCount = 2 + Math.floor(Math.random() * 2);
                if (length === 'medium') sCount = 4 + Math.floor(Math.random() * 3);
                if (length === 'long') sCount = 7 + Math.floor(Math.random() * 4);
                if (length === 'random') sCount = 3 + Math.floor(Math.random() * 8);

                let sentences = [];
                if (i === 0 && startLorem) sentences.push("Lorem ipsum dolor sit amet, consectetur adipiscing elit.");
                
                while (sentences.length < sCount) {
                    sentences.push(generateSentence('medium'));
                }
                paras.push(sentences.join(' '));
            }
            content = paras.join('\n\n');
            html = paras.map(p => `<p>${p}</p>`).join('');
        }
        else if (currentMode === 'lists') {
            const count = parseInt(document.getElementById('listItemCount').value);
            const type = document.getElementById('listType').value;
            const nesting = parseInt(document.getElementById('listNesting').value);
            const length = document.getElementById('listItemLength').value;

            let listItems = [];
            for (let i = 0; i < count; i++) {
                let item = generateSentence(length).replace('.', '');
                if (nesting > 0 && Math.random() > 0.7) {
                    let subItems = [];
                    for(let j=0; j<3; j++) subItems.push(`<li>${generateSentence('short').replace('.','')}</li>`);
                    item += `\n<ul>${subItems.join('')}</ul>`;
                }
                listItems.push(item);
            }

            if (type === 'checkbox') {
                html = listItems.map(item => `<div><input type="checkbox"> ${item}</div>`).join('');
                content = listItems.map(item => `[ ] ${item}`).join('\n');
            } else {
                const tag = type === 'ol' ? 'ol' : 'ul';
                html = `<${tag}>${listItems.map(i => `<li>${i}</li>`).join('')}</${tag}>`;
                content = listItems.map((i, idx) => `${type === 'ol' ? (idx+1)+'.' : '•'} ${i}`).join('\n');
            }
        }
        else if (currentMode === 'html') {
            const opts = Array.from(document.querySelectorAll('.html-opt:checked')).map(cb => cb.value);
            const wrap = document.getElementById('htmlWrap').value;
            const scale = document.getElementById('htmlScale').value;
            
            let parts = [];
            let loops = scale === 'small' ? 2 : (scale === 'medium' ? 5 : 12);

            for (let i = 0; i < loops; i++) {
                if (opts.includes('h') && (i === 0 || Math.random() > 0.7)) {
                    parts.push(`<h2>${getWords(3 + Math.floor(Math.random()*4))}</h2>`);
                }
                if (opts.includes('p')) {
                    parts.push(`<p>${getWords(20 + Math.floor(Math.random()*50))}</p>`);
                }
                if (opts.includes('list') && Math.random() > 0.8) {
                    parts.push(`<ul><li>${getWords(5)}</li><li>${getWords(5)}</li><li>${getWords(5)}</li></ul>`);
                }
                if (opts.includes('table') && Math.random() > 0.9) {
                    parts.push(`<table><thead><tr><th>Header 1</th><th>Header 2</th></tr></thead><tbody><tr><td>Data 1</td><td>Data 2</td></tr><tr><td>Data 3</td><td>Data 4</td></tr></tbody></table>`);
                }
                if (opts.includes('quote') && Math.random() > 0.9) {
                    parts.push(`<blockquote>${getWords(15)}</blockquote>`);
                }
                if (opts.includes('form') && Math.random() > 0.9) {
                    parts.push(`<form><div class="mb-3"><label>Full Name</label><input type="text" class="form-control"></div><button type="submit" class="btn btn-primary">Submit</button></form>`);
                }
            }

            html = parts.join('\n');
            if (wrap !== 'none') {
                html = `<${wrap}>\n${html}\n</${wrap}>`;
            }
            content = html; // In HTML mode, content is the source
        }
        else if (currentMode === 'realistic') {
            const scenario = document.getElementById('realisticScenario').value;
            const tone = document.getElementById('realisticTone').value;
            
            ({ content, html } = generateRealistic(scenario, tone));
        }

        renderOutput(content, html);
    }

    function generateRealistic(scenario, tone) {
        let content = '';
        let html = '';

        const techBuzz = ["AI-driven", "cloud-native", "seamless", "synergistic", "blockchain-powered", "disruptive", "scalable", "omnichannel"];
        const creativeBuzz = ["vibrant", "ethereal", "minimalist", "hand-crafted", "inspired", "fluid", "dynamic"];
        
        let bank = tone === 'tech' ? TECH : (tone === 'creative' ? creativeBuzz : ENGLISH);

        if (scenario === 'blog') {
            const title = `The Future of ${bank[0].toUpperCase()}${bank[0].slice(1)} in Today's World`;
            const intro = generateSentence('long', 'english');
            const p1 = generateSentence('long', 'english') + ' ' + generateSentence('medium', 'english');
            const p2 = generateSentence('long', 'english') + ' ' + generateSentence('long', 'english');
            
            html = `<h1>${title}</h1><p class="lead">${intro}</p><p>${p1}</p><h3>Key Insights</h3><p>${p2}</p>`;
            content = `${title}\n\n${intro}\n\n${p1}\n\n${p2}`;
        }
        else if (scenario === 'product') {
            const name = `${COMPANIES[Math.floor(Math.random()*COMPANIES.length)]} ${bank[0].toUpperCase()} Pro`;
            const price = "$" + (Math.random() * 500 + 19).toFixed(2);
            const desc = generateSentence('long', 'english');
            const features = [getWords(5, 'english'), getWords(5, 'english'), getWords(5, 'english')];
            
            html = `<div class="product-card"><h2>${name}</h2><p class="text-primary fw-bold fs-4">${price}</p><p>${desc}</p><ul>${features.map(f => `<li>${f}</li>`).join('')}</ul></div>`;
            content = `${name}\nPrice: ${price}\n\n${desc}\n\nFeatures:\n- ${features.join('\n- ')}`;
        }
        else if (scenario === 'saas') {
            html = `<h2>Powerful Features for Teams</h2><div class="row g-3">`;
            for(let i=0; i<3; i++) {
                html += `<div class="col-md-4"><div class="realistic-snippet fw-bold">${getWords(2, 'tech')}</div><p class="small">${generateSentence('short', 'english')}</p></div>`;
            }
            html += `</div>`;
            content = `Powerful Features\n\n${getWords(2, 'tech')}: ${generateSentence('short', 'english')}`;
        }
        else {
            // Default fallback
            content = generateSentence('long', 'english') + ' ' + generateSentence('long', 'english');
            html = `<p>${content}</p>`;
        }

        return { content, html };
    }

    function generateData(type) {
        let result = '';
        if (type === 'name') result = FIRST_NAMES[Math.floor(Math.random()*FIRST_NAMES.length)] + ' ' + LAST_NAMES[Math.floor(Math.random()*LAST_NAMES.length)];
        if (type === 'email') result = FIRST_NAMES[0].toLowerCase() + Math.floor(Math.random()*99) + '@' + DOMAINS[Math.floor(Math.random()*DOMAINS.length)];
        if (type === 'phone') result = `+1 (${Math.floor(Math.random()*800+200)}) 555-${Math.floor(Math.random()*8999+1000)}`;
        if (type === 'address') result = `${Math.floor(Math.random()*999+1)} ${bankWord(ENGLISH)} St, ${CITIES[Math.floor(Math.random()*CITIES.length)]}, ${COUNTRIES[0]}`;
        if (type === 'company') result = COMPANIES[Math.floor(Math.random()*COMPANIES.length)];
        if (type === 'website') result = "https://www." + COMPANIES[0].split(' ')[0].toLowerCase() + ".io";
        
        // UI Messages
        if (type === 'error') result = "Oops! Something went wrong while processing your request. Please try again later.";
        if (type === 'success') result = "Success! Your profile has been updated successfully.";
        if (type === 'button') result = "Get Started Now";
        if (type === 'placeholder') result = "Enter your username...";
        if (type === 'notification') result = "You have 3 new messages in your inbox.";

        renderOutput(result, `<div class="realistic-snippet"><strong>${type.toUpperCase()}:</strong> ${result}</div>`);
        showNotify(`Generated ${type}`, 'info');
    }

    function bankWord(bank) { return bank[Math.floor(Math.random()*bank.length)]; }

    function renderOutput(content, html) {
        outputContainer.classList.remove('d-none');
        previewArea.innerHTML = html;
        rawOutput.value = content;

        // Stats
        const words = content.trim().split(/\s+/).length;
        const chars = content.length;
        const paras = html.split('</p>').length - 1 || 1;
        const time = Math.ceil(words / 200); // 200 wpm

        statWords.textContent = words;
        statChars.textContent = chars;
        statParas.textContent = paras;
        statTime.textContent = time + 'm';

        outputContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function loadExample(key) {
        if (key === 'standard') {
            document.getElementById('words-tab').click();
            document.getElementById('wordCount').value = 100;
            document.getElementById('wordType').value = 'lorem';
        } else if (key === 'blog') {
            document.getElementById('realistic-tab').click();
            document.getElementById('realisticScenario').value = 'blog';
        } else if (key === 'faq') {
            document.getElementById('realistic-tab').click();
            document.getElementById('realisticScenario').value = 'faq';
        } else if (key === 'product') {
            document.getElementById('realistic-tab').click();
            document.getElementById('realisticScenario').value = 'product';
        } else if (key === 'startup') {
            document.getElementById('realistic-tab').click();
            document.getElementById('realisticScenario').value = 'saas';
            document.getElementById('realisticTone').value = 'tech';
        }
        
        setTimeout(generate, 100);
        showNotify('Example loaded', 'info');
    }

    // Auto-generate on first load
    generate();
});
</script>
@endpush
@endsection
