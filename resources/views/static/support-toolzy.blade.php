@extends('layouts.app')

@section('content')
<div class="support-page-container py-3">
    <!-- Hero Section -->
    <section class="hero-section text-center py-5 mb-5 rounded-4 position-relative overflow-hidden hero-gradient">
        <div class="container position-relative z-1 py-4">
            <h1 class="display-5 fw-bold text-dark mb-3">Support Toolzy</h1>
            <p class="hero-lead mx-auto mb-4 max-width-720">
                Toolzy is built to provide free, privacy-focused developer tools that run directly in your browser. If Toolzy has saved you time, consider supporting future development.
            </p>
            <div class="d-flex justify-content-center gap-3 align-items-center flex-wrap mt-2">
                <span><i class="bi bi-heart-fill text-danger me-1"></i> Voluntary Donation</span>
                <span><i class="bi bi-shield-lock-fill text-info me-1"></i> No Locked Features</span>
                <span><i class="bi bi-eye-slash-fill text-warning me-1"></i> 100% Privacy Friendly</span>
            </div>
        </div>
    </section>

    <!-- Donation Section / Cards -->
    <section class="donation-methods-section mb-5" aria-labelledby="donation-section-title">
        <div class="text-center mb-4">
            <h2 id="donation-section-title" class="fw-bold text-dark">Choose a Payment Method</h2>
        </div>
        
        <div class="row justify-content-center g-4" id="donation-methods-container">
            <div class="col-lg-5 col-md-6" id="paytm-card-container">
                <x-paytm-card />
            </div>
            <div class="col-lg-5 col-md-6" id="kofi-card-container">
                <x-kofi-card />
            </div>
        </div>
    </section>

    <!-- Why Toolzy Exists / Trust Section -->
    <section class="trust-section mb-5" aria-labelledby="trust-section-title">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <h2 id="trust-section-title" class="fw-bold text-dark mb-3 fs-2">Why Toolzy is Different</h2>
                <p class="text-secondary mb-4">
                    Most developer tools on the web are either cluttered with annoying popups, sell your data to advertisers, or lock essential features behind monthly subscriptions. 
                    <strong>Toolzy takes a different path.</strong>
                </p>
                <div class="p-4 bg-light rounded-4 border border-light-subtle">
                    <p class="mb-0 fw-semibold text-dark italic">
                        "I believe tools should be fast, private, and accessible to every developer globally. Your support helps keep this vision alive."
                    </p>
                    <div class="mt-3 text-secondary small fw-bold">— Dheeraj Agarwal, Creator of Toolzy</div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="card border-0 bg-white shadow-sm h-100 p-3 rounded-4 transition-transform hover-shadow">
                            <div class="card-body">
                                <div class="bg-primary-subtle text-primary rounded-circle p-2 d-inline-block mb-3 support-trust-icon-wrapper" aria-hidden="true">
                                    <i class="bi bi-cpu fs-5"></i>
                                </div>
                                <h3 class="h5 fw-bold text-dark mb-2">Browser-Based</h3>
                                <p class="text-secondary small mb-0">All code formatting, token decoding, and generation happen on your machine. No server-side leaks.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 bg-white shadow-sm h-100 p-3 rounded-4 transition-transform hover-shadow">
                            <div class="card-body">
                                <div class="bg-success-subtle text-success rounded-circle p-2 d-inline-block mb-3 support-trust-icon-wrapper" aria-hidden="true">
                                    <i class="bi bi-shield-shaded fs-5"></i>
                                </div>
                                <h3 class="h5 fw-bold text-dark mb-2">User Privacy</h3>
                                <p class="text-secondary small mb-0">We never collect, upload, or sell your files or processed inputs. Your code stays in your browser.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 bg-white shadow-sm h-100 p-3 rounded-4 transition-transform hover-shadow">
                            <div class="card-body">
                                <div class="bg-warning-subtle text-warning rounded-circle p-2 d-inline-block mb-3 support-trust-icon-wrapper" aria-hidden="true">
                                    <i class="bi bi-cash-stack fs-5"></i>
                                </div>
                                <h3 class="h5 fw-bold text-dark mb-2">100% Free Access</h3>
                                <p class="text-secondary small mb-0">No premium tier, no API rate limits. Every tool is completely open and free to use for everyone.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 bg-white shadow-sm h-100 p-3 rounded-4 transition-transform hover-shadow">
                            <div class="card-body">
                                <div class="bg-danger-subtle text-danger rounded-circle p-2 d-inline-block mb-3 support-trust-icon-wrapper" aria-hidden="true">
                                    <i class="bi bi-eye-slash-fill fs-5"></i>
                                </div>
                                <h3 class="h5 fw-bold text-dark mb-2">No Tracking</h3>
                                <p class="text-secondary small mb-0">No telemetry scripts tracking your keystrokes or cookies collecting profile data. Clean environment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <!-- What Donations Help Fund -->
    <section class="funding-goals-section mb-5" aria-labelledby="goals-section-title">
        <h2 id="goals-section-title" class="fw-bold text-dark text-center mb-4">What Your Support Funds</h2>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="fs-3" aria-hidden="true">🌐</span>
                            <h3 class="h5 fw-bold text-dark mb-0">Hosting & Domain Costs</h3>
                        </div>
                        <p class="text-secondary small mb-0">Ensuring high availability, domain renewals (toolzy.app), fast content delivery networks (CDNs), and SSL certificates for secure browsing.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="fs-3" aria-hidden="true">🛠️</span>
                            <h3 class="h5 fw-bold text-dark mb-0">New Tool Development</h3>
                        </div>
                        <p class="text-secondary small mb-0">Allocating dedicated time and research to conceptualize, design, and program new developer tools requested by our users.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="fs-3" aria-hidden="true">⚡</span>
                            <h3 class="h5 fw-bold text-dark mb-0">Performance Optimizations</h3>
                        </div>
                        <p class="text-secondary small mb-0">Improving build sizes, upgrading to cutting-edge web technologies, and optimizing execution speeds so files process in milliseconds.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="fs-3" aria-hidden="true">🐛</span>
                            <h3 class="h5 fw-bold text-dark mb-0">Bug Fixes & Maintenance</h3>
                        </div>
                        <p class="text-secondary small mb-0">Addressing reported edge cases, updating dependencies to prevent security alerts, and maintaining cross-browser and mobile device compatibility.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="fs-3" aria-hidden="true">🔒</span>
                            <h3 class="h5 fw-bold text-dark mb-0">Security Audits</h3>
                        </div>
                        <p class="text-secondary small mb-0">Reviewing our dependencies, verifying CSP policies, and ensuring browser sandboxing stays locked down to protect your code segments.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="fs-3" aria-hidden="true">☕</span>
                            <h3 class="h5 fw-bold text-dark mb-0">Developer Vitality</h3>
                        </div>
                        <p class="text-secondary small mb-0">Keeping the main developer fueled on coffee to continuously maintain, support, and keep Toolzy growing as an active, ad-free side project.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Future Roadmap -->
    <section class="roadmap-section mb-5 py-5 px-4 bg-light rounded-4" aria-labelledby="roadmap-section-title">
        <div class="roadmap-max-width-600 mx-auto text-center mb-4">
            <h2 id="roadmap-section-title" class="fw-bold text-dark">Future Roadmap</h2>
            <p class="text-secondary">Here are the features we have planned. Your donations directly influence the speed of building and releasing these utilities.</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="timeline position-relative">
                    <!-- Roadmap Item 1 -->
                    <div class="d-flex mb-4 gap-3 timeline-item">
                        <div class="timeline-badge bg-primary text-white rounded-circle p-2 flex-shrink-0 timeline-badge-wrapper">
                            <i class="bi bi-terminal-fill"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-1">API Request Builder</h3>
                            <p class="text-secondary small mb-0">A fully-featured REST, GraphQL, and WebSocket request client right in the browser, matching Postman features without privacy concerns.</p>
                        </div>
                    </div>
                    <!-- Roadmap Item 2 -->
                    <div class="d-flex mb-4 gap-3 timeline-item">
                        <div class="timeline-badge bg-primary text-white rounded-circle p-2 flex-shrink-0 timeline-badge-wrapper">
                            <i class="bi bi-file-earmark-code-fill"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-1">OpenAPI/Swagger Spec Viewer</h3>
                            <p class="text-secondary small mb-0">Paste or drop yaml/json swagger schemas to inspect endpoints, model schemes, and copy curls securely without third-party analytics leaks.</p>
                        </div>
                    </div>
                    <!-- Roadmap Item 3 -->
                    <div class="d-flex mb-4 gap-3 timeline-item">
                        <div class="timeline-badge bg-primary text-white rounded-circle p-2 flex-shrink-0 timeline-badge-wrapper">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-1">Database Schema Visualizer</h3>
                            <p class="text-secondary small mb-0">Design and visualize database schemas interactively in a visual canvas, then export schema configurations directly to SQL, JSON, or Mermaid code.</p>
                        </div>
                    </div>
                    <!-- Roadmap Item 4 -->
                    <div class="d-flex mb-4 gap-3 timeline-item">
                        <div class="timeline-badge bg-primary text-white rounded-circle p-2 flex-shrink-0 timeline-badge-wrapper">
                            <i class="bi bi-code-square"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-1">JSON Schema Generator</h3>
                            <p class="text-secondary small mb-0">Paste sample JSON objects to automatically infer and generate complete JSON Schema structures with custom options.</p>
                        </div>
                    </div>
                    <!-- Roadmap Item 5 -->
                    <div class="d-flex gap-3 timeline-item">
                        <div class="timeline-badge bg-primary text-white rounded-circle p-2 flex-shrink-0 timeline-badge-wrapper">
                            <i class="bi bi-plus-circle-fill"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold text-dark mb-1">More Developer Tools</h3>
                            <p class="text-secondary small mb-0">We are constantly reviewing user-submitted requests via our contact page to expand our catalog of text, formatting, image, and utility tools.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section mb-5" aria-labelledby="faq-section-title">
        <h2 id="faq-section-title" class="fw-bold text-dark text-center mb-4">Frequently Asked Questions</h2>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="supportFaqAccordion">
                    <!-- FAQ 1 -->
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                        <h3 class="accordion-header" id="faq-head-1">
                            <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-1" aria-expanded="true" aria-controls="faq-collapse-1">
                                Is Toolzy free?
                            </button>
                        </h3>
                        <div id="faq-collapse-1" class="accordion-collapse collapse show" aria-labelledby="faq-head-1" data-bs-parent="#supportFaqAccordion">
                            <div class="accordion-body text-secondary small">
                                Yes, Toolzy is 100% free and open for everyone. There are no paywalls, hidden limits, or subscriptions required. Every tool operates entirely in your browser without any limitations.
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 2 -->
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                        <h3 class="accordion-header" id="faq-head-2">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-2" aria-expanded="false" aria-controls="faq-collapse-2">
                                Will donations unlock premium features?
                            </button>
                        </h3>
                        <div id="faq-collapse-2" class="accordion-collapse collapse" aria-labelledby="faq-head-2" data-bs-parent="#supportFaqAccordion">
                            <div class="accordion-body text-secondary small">
                                No. Donations are entirely voluntary and will never lock or unlock any features on the site. Toolzy is built on the philosophy that essential developer tools should be open, private, and accessible to everyone.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                        <h3 class="accordion-header" id="faq-head-3">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-3" aria-expanded="false" aria-controls="faq-collapse-3">
                                How are donations used?
                            </button>
                        </h3>
                        <div id="faq-collapse-3" class="accordion-collapse collapse" aria-labelledby="faq-head-3" data-bs-parent="#supportFaqAccordion">
                            <div class="accordion-body text-secondary small">
                                Donations are utilized to cover recurring infrastructure bills like server hosting, domain name renewals, CDN bandwidth, and SSL certificates. Additional funds support security reviews and help dedicate focused time to building new browser-based utilities.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                        <h3 class="accordion-header" id="faq-head-4">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-4" aria-expanded="false" aria-controls="faq-collapse-4">
                                Is Toolzy open to everyone?
                            </button>
                        </h3>
                        <div id="faq-collapse-4" class="accordion-collapse collapse" aria-labelledby="faq-head-4" data-bs-parent="#supportFaqAccordion">
                            <div class="accordion-body text-secondary small">
                                Yes. Toolzy can be accessed from any country, on any device. Because all processing is browser-based, you only need an internet connection to load the tool page, after which you can even use many of our features offline!
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                        <h3 class="accordion-header" id="faq-head-5">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-5" aria-expanded="false" aria-controls="faq-collapse-5">
                                Can I support Toolzy from outside India?
                            </button>
                        </h3>
                        <div id="faq-collapse-5" class="accordion-collapse collapse" aria-labelledby="faq-head-5" data-bs-parent="#supportFaqAccordion">
                            <div class="accordion-body text-secondary small">
                                Yes! International visitors can support Toolzy using Ko-fi. Ko-fi accepts PayPal, Stripe, and global credit/debit cards. It redirects you securely to Ko-fi's portal.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 6 -->
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                        <h3 class="accordion-header" id="faq-head-6">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-6" aria-expanded="false" aria-controls="faq-collapse-6">
                                Can I support Toolzy from India?
                            </button>
                        </h3>
                        <div id="faq-collapse-6" class="accordion-collapse collapse" aria-labelledby="faq-head-6" data-bs-parent="#supportFaqAccordion">
                            <div class="accordion-body text-secondary small">
                                Yes! Supporters in India can scan the Paytm UPI QR code or copy the UPI ID to complete a transfer directly through their favorite UPI-enabled apps like Paytm, PhonePe, Google Pay, or BHIM.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 7 -->
                    <div class="accordion-item border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                        <h3 class="accordion-header" id="faq-head-7">
                            <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-7" aria-expanded="false" aria-controls="faq-collapse-7">
                                Why does Toolzy ask for donations?
                            </button>
                        </h3>
                        <div id="faq-collapse-7" class="accordion-collapse collapse" aria-labelledby="faq-head-7" data-bs-parent="#supportFaqAccordion">
                            <div class="accordion-body text-secondary small">
                                Because we refuse to show commercial ads or monetize your usage data, donations are the only way to cover our running infrastructure costs. We believe developer tools should be pure, free, and privacy-respecting, and community support enables us to keep them that way.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')

@endpush
@endsection
