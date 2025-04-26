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
    {{-- CSS - Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light bg-light px-3">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">Toolzy</a>
    </nav>

  

    {{-- UI Breadcrumbs --}}
    @include('components.ui-breadcrumbs')
    <!-- Main Content -->
    <main class="container py-4">
        @yield('content')
    </main>
    <!-- Footer -->
    <footer class="bg-light text-center text-muted py-4 border-top mt-5">
    <div class="container">
    <p class="mb-2">&copy; {{ date('Y') }} Toolzy. All rights reserved.</p>
     <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="{{ url('/about') }}" class="text-decoration-none text-muted">About</a>
            <a href="{{ url('/contact') }}" class="text-decoration-none text-muted">Contact</a>
            <a href="{{ url('/disclaimer') }}" class="text-decoration-none text-muted">Disclaimer</a>
            <a href="{{ url('/privacy-policy') }}" class="text-decoration-none text-muted">Privacy Policy</a>
            <a href="{{ url('/terms-and-conditions') }}" class="text-decoration-none text-muted">Terms & Conditions</a>
        </div>
        <div class="mt-3 small">&copy; {{ date('Y') }} Toolzy.app — Free online tools for everyone.</div>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>