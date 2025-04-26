<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $meta['title'] ?? 'Toolzy - Free Online Tools for Everyone' }}</title>
    <meta name="description" content="{{ $meta['description'] ?? 'Toolzy.app offers free and unlimited access to essential online tools like PDF converters, image compressors, and more.' }}">
    <meta name="keywords" content="{{ $meta['keywords'] ?? 'online tools, free tools, pdf converter, image compressor, word counter, toolzy' }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $meta['title'] ?? 'Toolzy - Free Online Tools for Everyone' }}">
    <meta property="og:description" content="{{ $meta['description'] ?? 'Free tools like PDF to Word, Image Compressor, QR Code Generator, and more.' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('og-default.png') }}"> <!-- You can change this -->

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta['title'] ?? 'Toolzy - Free Online Tools' }}">
    <meta name="twitter:description" content="{{ $meta['description'] ?? 'Free online tools for everyone. No login, no limit.' }}">
    <meta name="twitter:image" content="{{ asset('og-default.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-light bg-light px-3">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">Toolzy</a>
    </nav>

    <main class="container py-5">
        @yield('content')
    </main>

    <footer class="text-center py-3 border-top">
        <p class="mb-0 small">© {{ date('Y') }} Toolzy.app — Free online tools for everyone</p>
    </footer>
</body>
</html>