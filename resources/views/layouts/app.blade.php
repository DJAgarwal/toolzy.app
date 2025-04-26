<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Dynamic JSON-LD Schema Injection --}}
    @if (!empty($jsonld))
        <script type="application/ld+json">{!! $jsonld !!}</script>
    @endif
    {{-- Dynamic Breadcrumb JSON-LD --}}
    @if (!empty($jsonldBreadcrumbs))
        <script type="application/ld+json">{!! $jsonldBreadcrumbs !!}</script>
    @endif
    {{-- Essential Meta Tags --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- SEO Meta --}}
    <meta name="description" content="{{ $metaDescription ?? 'default Discover the best natural and safe products in India, analyzed by ingredients and trusted reviews.' }}">
    <meta name="keywords" content="natural products, safe skincare, ingredient check, India, shampoo, ayurvedic, paraben free, chemical free">
    <meta name="author" content="NaturalProductsIndia">
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}" />
    {{-- Open Graph / Social Sharing --}}
    <meta property="og:title" content="{{ $og['title'] ?? '' }}">
    <meta property="og:description" content="{{ $og['description'] ?? '' }}">
    <meta property="og:url" content="{{ $og['url'] ?? '' }}">
    <meta property="og:type" content="{{ $og['type'] ?? '' }}">
    <meta property="og:image" content="{{ $og['image'] ?? '' }}">
    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="{{ $twitter['card'] ?? '' }}">
    <meta name="twitter:title" content="{{ $twitter['title'] ?? '' }}">
    <meta name="twitter:description" content="{{ $twitter['description'] ?? '' }}">
    <meta name="twitter:image" content="{{ $twitter['image'] ?? '' }}">
    <meta name="twitter:creator" content="{{ $twitter['creator'] ?? '' }}">
    {{-- Title --}}
    <title>{{ $metaTitle ?? config('app.name', 'Toolzy') }}</title>
    {{-- Preload Critical CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="preload" as="style" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Load Non-Essential CSS After Page Load --}}
    <!-- <script>
        window.onload = function() {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/your-non-essential-styles.css';
            document.head.appendChild(link);
        };
    </script> -->
    {{-- Deferred CSS Example --}}
    <link href="https://cdn.jsdelivr.net/npm/your-non-critical-styles.css" rel="stylesheet" media="print" id="deferred-css">
    <script>
        window.onload = function() {
            var link = document.getElementById('deferred-css');
            link.media = 'all';
        };
    </script>
    <style>
    .breadcrumb {
    --bs-breadcrumb-divider: '›';
}
.hover-link:hover {
        color: #0d6efd !important; /* Bootstrap primary color */
        text-decoration: underline;
        transition: all 0.2s ease-in-out;
    }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary fs-4" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Toolzy Logo" style="height: 32px;" class="me-2">
            Toolzy
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
    {{-- UI Breadcrumbs --}}
    @include('components.ui-breadcrumbs')
    <!-- Main Content -->
    <main class="container py-4">
        @yield('content')
    </main>
    <!-- Footer -->
    <footer class="bg-white text-center text-muted border-top mt-5 pt-4 pb-3">
    <div class="container">
        <p class="mb-2 fw-semibold text-dark">&copy; {{ date('Y') }} <span class="text-primary">Toolzy</span>. All rights reserved.</p>
        
        <div class="d-flex flex-wrap justify-content-center gap-3 small mb-2">
            <a href="{{ url('/about') }}" class="text-decoration-none text-muted hover-link">About</a>
            <a href="{{ url('/contact') }}" class="text-decoration-none text-muted hover-link">Contact</a>
            <a href="{{ url('/disclaimer') }}" class="text-decoration-none text-muted hover-link">Disclaimer</a>
            <a href="{{ url('/privacy-policy') }}" class="text-decoration-none text-muted hover-link">Privacy Policy</a>
            <a href="{{ url('/terms-and-conditions') }}" class="text-decoration-none text-muted hover-link">Terms & Conditions</a>
        </div>

        <div class="small text-secondary">Toolzy.app — Free online tools for everyone.</div>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>