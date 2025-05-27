@extends('layouts.app')
@section('content')
<div class="py-5 hero-gradient">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-3">Welcome to Toolzy</h1>
        <p class="hero-lead">Free, fast, and reliable tools to simplify your digital tasks — no signups, no nonsense.</p>
        <a href="#tools" class="btn btn-primary btn-lg mt-3" id="explore-tools-btn">
            <i class="bi bi-search"></i> Explore Tools
        </a>
    </div>
</div>
<div class="py-5" id="tools">
    <div class="container">
        <h2 class="text-center mb-4">Popular Tools</h2>

        <!-- Search Bar -->
        <div class="mb-4 text-center">
            <form method="GET" action="{{ route('home') }}">
                <div class="input-group input-group-lg mx-auto max-width-600">
                    <label for="tool-search" class="visually-hidden">Search Tools</label>
                    <input type="text" id="tool-search" class="form-control form-control-lg border-primary" name="search" placeholder="Search tools..." value="{{ request()->query('search') }}">
                    <button class="btn btn-primary btn-lg" type="submit">Search</button>
                </div>
            </form>
        </div>

        <!-- Tools Cards -->
        <div class="row g-4">
            @foreach ($tools as $tool)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-lg hover-shadow transition-all">
                    <div class="card-body text-center p-4 d-flex flex-column">
                        <h3 class="card-title mb-3 fw-semibold">{{ $tool->meta_title }}</h3>
                        <p class="card-text text-muted mb-4 small flex-grow-1">{{ $tool->meta_description }}</p>
                        <a href="{{ url('/tools/' . $tool->page_name) }}" class="btn btn-outline-primary btn-lg w-100 mt-auto">Use Tool</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $tools->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $tools->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @for ($i = 1; $i <= $tools->lastPage(); $i++)
                        <li class="page-item {{ $tools->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $tools->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $tools->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $tools->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Why Choose Toolzy Section -->
<section class="py-5 hero-gradient">
    <div class="container text-center">
        <h3 class="fw-bold mb-3">Why Choose Toolzy?</h3>
        <p class="whychoose-lead mb-5">We believe in simplicity, privacy, and unlimited access to tools that just work + no ads, no tracking.</p>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-sm-6">
              <div class="whychoose-card p-4 rounded-4 h-100 shadow-sm transition">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-lightning-charge" viewBox="0 0 16 16">
                            <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09zM4.157 8.5H7a.5.5 0 0 1 .478.647L6.11 13.59l5.732-6.09H9a.5.5 0 0 1-.478-.647L9.89 2.41z"/>
                        </svg>
                    </div>
                    <h4 class="fw-semibold">Fast</h4>
                    <p class="mb-0 small fw-semibold">Instant tools that get the job done without delay.</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="whychoose-card p-4 rounded-4 h-100 shadow-sm transition">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-shield-lock" viewBox="0 0 16 16">
                            <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                            <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415"/>
                        </svg>
                    </div>
                    <h4 class="fw-semibold">Secure</h4>
                    <p class="mb-0 small fw-semibold">Your data stays with you. No tracking. No cookies.</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="whychoose-card p-4 rounded-4 h-100 shadow-sm transition">
                    <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-infinity" viewBox="0 0 16 16">
                        <path d="M5.68 5.792 7.345 7.75 5.681 9.708a2.75 2.75 0 1 1 0-3.916ZM8 6.978 6.416 5.113l-.014-.015a3.75 3.75 0 1 0 0 5.304l.014-.015L8 8.522l1.584 1.865.014.015a3.75 3.75 0 1 0 0-5.304l-.014.015zm.656.772 1.663-1.958a2.75 2.75 0 1 1 0 3.916z"/>
                    </svg>
                    </div>
                    <h4 class="fw-semibold">Free Forever</h4>
                    <p class="mb-0 small fw-semibold">All tools are 100% free. No signups or subscriptions ever.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Closing Message Section -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <p class="fs-5 mb-1">🚀 Built with ❤️ by developers who care about simplicity.</p>
        <small class="text-muted">More tools launching soon — stay tuned!</small>
    </div>
</section>
@endsection
@push('scripts')
<script nonce="{{ $cspNonce }}">
    document.querySelector('#explore-tools-btn').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('#tools').scrollIntoView({ behavior: 'smooth' });
    });
    window.onload = function() {
        // Check if search query exists in the URL or if the page is not the first page
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('search');
        const page = urlParams.get('page');

        // If there is a search query or pagination, scroll to the tools section
        if (searchQuery || page) {
            const toolsSection = document.getElementById('tools');
            if (toolsSection) {
                toolsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
    };
</script>
@endpush