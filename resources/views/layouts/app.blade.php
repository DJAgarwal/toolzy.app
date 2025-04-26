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
    <meta name="robots" content="index, follow">
    {{-- SEO Meta --}}
    <meta name="description" content="{{ $metaDescription ?? 'Toolzy offers a collection of free online tools to simplify your daily tasks — fast, easy, and accessible for everyone.' }}">
    <meta name="keywords" content="online tools, free tools, Toolzy, calculator, converter, generators, productivity tools, web utilities">
    <meta name="author" content="Toolzy">
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
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    {{-- Preload Critical CSS --}}
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></noscript>
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
    <!-- <link href="https://cdn.jsdelivr.net/npm/your-non-critical-styles.css" rel="stylesheet" media="print" id="deferred-css">
    <script>
        window.onload = function() {
            var link = document.getElementById('deferred-css');
            link.media = 'all';
        };
    </script> -->
    {{-- Custom Styles --}}
    <style>
        /* Global smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Breadcrumb styles */
        .breadcrumb {
            --bs-breadcrumb-divider: '›';
            background-color: #f8f9fa;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            font-size: 0.95rem;
        }

        /* Hover link styling */
        .hover-link {
            transition: color 0.3s ease, text-decoration 0.3s ease;
        }

        .hover-link:hover {
            color: #0d6efd !important;
            text-decoration: underline;
        }

        /* Navbar tweaks */
        .navbar-brand img {
            height: 36px;
        }

        .navbar {
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Main content spacing */
        main.container {
            min-height: 70vh;
        }

        /* Footer tweaks */
        footer {
            font-size: 0.9rem;
        }

        footer a:hover {
            color: #0d6efd !important;
        }

        /* Smooth hover effect for logo */
        .navbar-brand:hover {
            opacity: 0.85;
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary fs-4 d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.webp') }}" alt="Toolzy Logo" class="me-2">
                Toolzy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    {{-- Breadcrumb --}}
    @include('components.ui-breadcrumbs')
    {{-- Main Content --}}
    <main class="container py-5">
        @yield('content')
    </main>
    {{-- Footer --}}
    <footer class="bg-white text-center text-muted border-top mt-auto pt-4 pb-3">
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

    {{-- Bootstrap Bundle JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>