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
    <meta name="theme-color" content="#0d6efd">

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
    <meta property="og:type" content="{{ $og['type'] ?? 'website' }}">
    <meta property="og:image" content="{{ $og['image'] ?? '' }}">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="{{ $twitter['card'] ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="{{ $twitter['title'] ?? '' }}">
    <meta name="twitter:description" content="{{ $twitter['description'] ?? '' }}">
    <meta name="twitter:image" content="{{ $twitter['image'] ?? '' }}">
    <meta name="twitter:creator" content="{{ $twitter['creator'] ?? '' }}">

    {{-- Title --}}
    <title>{{ $metaTitle ?? config('app.name', 'Toolzy') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Custom Styles --}}
    <link rel="preload" href="http://127.0.0.1:8000/bootstrap/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'" media="screen">
    <noscript><link rel="stylesheet" href="http://127.0.0.1:8000/bootstrap/css/bootstrap.min.css" media="screen"></noscript>
    <style>
        html { scroll-behavior: smooth; }
        .breadcrumb { --bs-breadcrumb-divider: '›';background-color: #f8f9fa;padding: 0.75rem 1rem;border-radius: 0.5rem;box-shadow: 0 1px 3px rgba(0,0,0,0.1);font-size: 0.95rem; }
        .hover-link { transition: color 0.3s ease, text-decoration 0.3s ease; }
        .hover-link:hover { color: #0d6efd !important;text-decoration: underline; }
        .navbar-brand img { height: 36px; }
        .navbar { transition: background-color 0.3s ease, box-shadow 0.3s ease; }
        main.container { min-height: 70vh; }
        footer { font-size: 0.9rem; }
        footer a:hover { color: #0d6efd !important; }
        .navbar-brand:hover { opacity: 0.85; transition: opacity 0.3s ease; }
        .hover-shadow:hover { box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.1);transition: box-shadow 0.3s ease; }
        .max-width-600 { max-width: 600px;  }
        .nav-item:not(:last-child) .nav-link::after {
            content: "|";
            margin-left: 10px;
            color: #333; /* Optional: color of the pipe */
        }
        .hover-shadow {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        .hover-shadow:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.15), 0 6px 6px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100" data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="70" tabindex="0">
    {{-- Header --}}
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary fs-4 d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.webp') }}" alt="Toolzy Logo" class="me-2" width="40" height="40">
                    Toolzy
                </a>
                
                <!-- Toggler for mobile view -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link fs-5" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5" href="{{ url('/tools') }}">More Tools</a>
                    </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

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
    <script src="http://127.0.0.1:8000/bootstrap/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>