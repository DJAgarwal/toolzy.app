<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Dynamic JSON-LD Schema Injection --}}
    @if (!empty($jsonld))<script nonce="{{ $cspNonce }}" type="application/ld+json">{!! $jsonld !!}</script>@endif

    {{-- Custom Styles --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    {{-- Essential Meta Tags --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#0d6efd">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    {{-- SEO Meta --}}
    <meta name="description" content="{{ $metaDescription ?? 'Toolzy offers a collection of free online tools to simplify your daily tasks — fast, easy, and accessible for everyone.' }}">
    <meta name="keywords" content="online tools, free tools, Toolzy, calculator, converter, generators, productivity tools, web utilities">
    <meta name="author" content="Toolzy">
    <meta http-equiv="content-language" content="en">
    <meta name="geo.region" content="IN">
    <meta name="geo.placename" content="India">
    <meta name="distribution" content="global">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}" />

    {{-- Open Graph / Social Sharing --}}
    <meta property="og:title" content="{{ $og['title'] ?? '' }}">
    <meta property="og:description" content="{{ $og['description'] ?? '' }}">
    <meta property="og:url" content="{{ $og['url'] ?? '' }}">
    <meta property="og:type" content="{{ $og['type'] ?? 'website' }}">
    <meta property="og:image" content="{{ $og['image'] ?? '' }}">
    <meta property="og:locale" content="{{ $og['locale'] ?? '' }}">
    <meta property="og:site_name" content="{{ $og['site_name'] ?? '' }}">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="{{ $twitter['card'] ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="{{ $twitter['title'] ?? '' }}">
    <meta name="twitter:description" content="{{ $twitter['description'] ?? '' }}">
    <meta name="twitter:image" content="{{ $twitter['image'] ?? '' }}">
    <meta name="twitter:creator" content="{{ $twitter['creator'] ?? '' }}">
    <meta name="twitter:site" content="{{ $twitter['site'] ?? '' }}">

    {{-- Title --}}
    <title>{{ $metaTitle ?? config('app.name', 'Toolzy') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="{{ asset('manifest.json') }}" crossorigin="anonymous">
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100" data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="70" tabindex="0">
    {{-- Header --}}
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary fs-4 d-flex align-items-center" href="{{ url('/') }}">
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
                        <a class="nav-link fs-5" href="{{ url('/') }}" @if(request()->is('/')) aria-current="page" @endif>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5" href="{{ url('/tools') }}" @if(request()->is('tools')) aria-current="page" @endif>More Tools</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5" href="{{ url('/contact') }}" @if(request()->is('contact')) aria-current="page" @endif>Request a Tool</a>
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
        @if (!empty($isToolPage))
        <div class="container py-5">
            <h1 class="mb-4 text-center fw-bold">{{ $metaTitle ?? config('app.name', 'Toolzy') }}</h1>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card shadow border-0">
                        <div class="card-body p-4">
                        @yield('content')
                        </div>
                    </div>
                </div>
            </div>
            <section class="my-5">
            @include('components.what-is')
            </section>
            <section class="my-5">
            @include('components.faq')
            </section>
        </div>
        @else
        @yield('content')
        @endif
    </main>
    
    {{-- Footer --}}
    <footer class="footer-main bg-white border-top mt-auto pt-5 pb-4 text-center text-muted">
        <div class="container">
            <p class="mb-3 fw-bold text-dark fs-5">
                &copy; {{ date('Y') }} <span class="text-primary">Toolzy</span>. All rights reserved.
            </p>
            <nav class="footer-links d-flex flex-wrap justify-content-center gap-3 gap-md-4 mb-3">
                <a href="{{ url('/about') }}" class="footer-link text-uppercase">About</a>
                <a href="{{ url('/contact') }}" class="footer-link text-uppercase">Contact</a>
                <a href="{{ url('/disclaimer') }}" class="footer-link text-uppercase">Disclaimer</a>
                <a href="{{ url('/privacy-policy') }}" class="footer-link text-uppercase">Privacy Policy</a>
                <a href="{{ url('/terms-and-conditions') }}" class="footer-link text-uppercase">Terms & Conditions</a>
            </nav>
            <!-- <div class="footer-social mb-3">
                <a href="https://facebook.com" class="footer-social-link" aria-label="Facebook" target="_blank" rel="noopener"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16"><path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/></svg></a>
                <a href="https://twitter.com" class="footer-social-link" aria-label="Twitter" target="_blank" rel="noopener"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16"><path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334q.002-.211-.006-.422A6.7 6.7 0 0 0 16 3.542a6.7 6.7 0 0 1-1.889.518 3.3 3.3 0 0 0 1.447-1.817 6.5 6.5 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.32 9.32 0 0 1-6.767-3.429 3.29 3.29 0 0 0 1.018 4.382A3.3 3.3 0 0 1 .64 6.575v.045a3.29 3.29 0 0 0 2.632 3.218 3.2 3.2 0 0 1-.865.115 3 3 0 0 1-.614-.057 3.28 3.28 0 0 0 3.067 2.277A6.6 6.6 0 0 1 .78 13.58a6 6 0 0 1-.78-.045A9.34 9.34 0 0 0 5.026 15"/></svg></a>
                <a href="https://linkedin.com" class="footer-social-link" aria-label="LinkedIn" target="_blank" rel="noopener"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16"><path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z"/></svg></a>
                <a href="https://instagram.com" class="footer-social-link" aria-label="Instagram" target="_blank" rel="noopener"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/></svg></a>
            </div> -->
            <div class="small text-secondary">
                Toolzy.app — Free online tools for everyone.
            </div>
            <div class="mt-3">
                <a href="#" class="footer-top-link" aria-label="Back to top">
                    Back to top
                </a>
            </div>
        </div>
    </footer>
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="imagePreviewLabel">Image Preview</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
            <img id="imagePreviewModalImg" src="" alt="Preview" class="img-fluid rounded shadow">
        </div>
        </div>
    </div>
    </div>
    <div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3 z-3 m-h-80">
        <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastBody">
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script nonce="{{ $cspNonce }}" async src="https://www.googletagmanager.com/gtag/js?id=G-2RRT13ZPY7"></script>
    <script nonce="{{ $cspNonce }}">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-2RRT13ZPY7', {
            anonymize_ip: true,
            transport_type: 'beacon'
        });
    </script>
    @if(config('app.env') == 'production')
    <script nonce="{{ $cspNonce }}" defer src="https://static.cloudflareinsights.com/beacon.min.js" data-cf-beacon='{"token": "55384888b8044d07825181ae0c517c3d"}'></script>
    @endif
    <script nonce="{{ $cspNonce }}" defer src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
    <script nonce="{{ $cspNonce }}">
        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            const toastBody = document.getElementById('toastBody');
            toastBody.textContent = message;
            toastEl.classList.remove('bg-success', 'bg-danger', 'bg-info', 'bg-warning');
            toastEl.classList.add(`bg-${type}`);
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    </script>
</body>
</html>