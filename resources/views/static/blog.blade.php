@extends('layouts.app')

@section('content')
<div class="py-4">
    <!-- Blog Header Banner -->
    <div class="p-5 mb-5 rounded-4 hero-gradient text-center shadow-sm d-flex flex-column align-items-center justify-content-center">
        <h1 class="display-5 fw-bold mb-3">Toolzy Blogs</h1>
        <p class="lead text-muted max-width-720 mx-auto">
            Discover developer guides, productivity hacks, and updates on free web utilities to streamline your digital workflow.
        </p>
    </div>

    <!-- Articles Grid -->
    <div class="row g-4">
        <!-- Token Optimization Blog Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition hover-shadow">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ date('M d, Y') }}</small>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1 small">AI & Tools</span>
                    </div>
                    <h2 class="card-title h5 fw-bold mb-3">
                        <a href="{{ url('/blog/optimize-token-usage-coding-agents') }}" class="text-dark text-decoration-none hover-link">
                            How to Optimize Token Usage in AI Coding Agents: A Practical Developer's Guide
                        </a>
                    </h2>
                    <p class="card-text text-secondary mb-4 flex-grow-1">
                        Learn how to reduce token consumption in AI coding agents using context engineering, prompt optimization, tool management, and model selection.
                    </p>
                    <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 32px; height: 32px; font-size: 14px;">
                                T
                            </div>
                            <span class="small text-muted font-weight-medium">Toolzy Team</span>
                        </div>
                        <a href="{{ url('/blog/optimize-token-usage-coding-agents') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            Read Article <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
