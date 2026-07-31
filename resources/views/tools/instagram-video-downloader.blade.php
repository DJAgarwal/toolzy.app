@extends('layouts.app')

@section('content')
<x-ui-trust-indicator />

{{-- Main Tool Card --}}
<div class="row justify-content-center">
    <div class="col-lg-10">
        {{-- Hero / Intro --}}
        <div class="text-center mb-4">
            <p class="lead text-muted">
                Paste any public Instagram Reel, Video, or IGTV URL below to save high-quality MP4 videos directly to your device. Fast, free, and no account required.
            </p>
        </div>

        {{-- Input Section --}}
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body p-4">
                <form id="downloaderForm" novalidate>
                    <label for="instagramUrl" class="form-label fw-bold fs-5 text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-instagram text-danger me-2 align-middle" viewBox="0 0 16 16">
                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                        </svg>
                        Instagram Video / Reel URL:
                    </label>
                    <div class="input-group input-group-lg mb-3">
                        <input type="url" class="form-control" id="instagramUrl" placeholder="https://www.instagram.com/reel/Cxxxxxx/" aria-label="Instagram Video Link" autocomplete="off" required>
                        <button class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center text-nowrap" type="button" id="pasteBtn" aria-label="Paste Link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard me-1 d-inline-block align-middle flex-shrink-0" viewBox="0 0 16 16">
                                <path d="M4 1.5H3a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-11a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-11a1 1 0 0 1 1-1h1z"/>
                                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                            </svg>
                            <span>Paste</span>
                        </button>
                        <button class="btn btn-outline-danger d-none" type="button" id="clearInputBtn" aria-label="Clear Input">
                            &times;
                        </button>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-3">
                        <button type="button" class="btn btn-primary btn-lg px-5 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center text-nowrap" id="fetchBtn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="btnSpinner" role="status" aria-hidden="true"></span>
                            <span id="btnText">Download Video</span>
                        </button>
                    </div>
                </form>

                {{-- Alert for Error Messages --}}
                <div class="alert alert-danger alert-dismissible fade show mt-3 d-none" id="errorAlert" role="alert">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <div id="errorMessage">Invalid Instagram URL.</div>
                    </div>
                    <button type="button" class="btn-close" id="closeErrorAlertBtn" aria-label="Close"></button>
                </div>
            </div>
        </div>

        {{-- Loading Skeleton Card --}}
        <div class="card shadow-sm border-0 mb-4 d-none" id="loadingCard">
            <div class="card-body p-4 text-center py-5">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Fetching video media...</span>
                </div>
                <h5 class="fw-bold text-dark">Retrieving Instagram Media...</h5>
                <p class="text-muted small mb-0">Analyzing link, extracting video stream & metadata...</p>
            </div>
        </div>

        {{-- Media Preview & Download Results Card --}}
        <div class="card shadow border-0 mb-4 d-none" id="mediaCard">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l4.992-5.99a.75.75 0 0 0-.018-1.042z"/>
                    </svg>
                    Video Ready to Download
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-center">
                    {{-- Media Preview (Player / Thumbnail) --}}
                    <div class="col-md-5 text-center mb-3 mb-md-0">
                        <div class="position-relative d-inline-block rounded shadow-sm overflow-hidden bg-dark style-media-container" style="max-width: 100%; max-height: 380px;">
                            <video id="mediaPlayer" controls class="w-100 rounded" style="max-height: 380px; object-fit: contain;">
                                <source id="videoSource" src="" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <img id="mediaThumbnail" src="" alt="Instagram Video Preview" class="img-fluid rounded d-none" style="max-height: 380px; object-fit: cover;">
                        </div>
                    </div>

                    {{-- Metadata & Actions --}}
                    <div class="col-md-7">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-danger fs-6" id="userBadge">@instagram_user</span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-1">Caption / Description</label>
                            <div class="p-3 bg-light rounded border text-dark small" id="captionText" style="max-height: 100px; overflow-y: auto;">
                                Instagram Video Post
                            </div>
                        </div>



                        {{-- Main Action Buttons --}}
                        <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-2 pt-3 border-top w-100">
                            <a href="#" class="btn btn-success fw-bold flex-grow-1 text-nowrap py-2 d-inline-flex align-items-center justify-content-center" id="downloadMediaBtn" download>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-download me-2 d-inline-block align-middle flex-shrink-0" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                </svg>
                                <span>Download Full HD Video</span>
                            </a>
                            <button type="button" class="btn btn-outline-secondary text-nowrap py-2 d-inline-flex align-items-center justify-content-center" id="copyLinkBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg me-1 d-inline-block align-middle flex-shrink-0" viewBox="0 0 16 16">
                                    <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                                    <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z"/>
                                </svg>
                                <span>Copy Link</span>
                            </button>
                            <button type="button" class="btn btn-outline-danger text-nowrap py-2 d-inline-flex align-items-center justify-content-center" id="resetBtn">
                                <span>Reset</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Comprehensive SEO Content Section --}}
        <article class="mt-5 border-top pt-5 text-dark">
            <h2 class="fw-bold mb-4">Complete Guide to Downloading Instagram Videos & Reels</h2>
            
            <div class="mb-4">
                <p>
                    Instagram is one of the world's most popular visual social networking platforms, hosting millions of creative Reels, short video clips, educational tutorials, and IGTV broadcasts every day. However, Instagram does not provide a built-in direct download button for saving public videos to your local device storage. Toolzy solves this problem with our modern, high-speed <strong>Instagram Video Downloader</strong>.
                </p>
                <p>
                    Our free online downloader empowers creators, social media managers, students, and casual viewers to extract and download high-definition Instagram Reels and videos in standard MP4 format without installing extra software or compromising account security.
                </p>
            </div>

            <h3 class="fw-bold mt-4 mb-3">Supported Instagram URL Formats</h3>
            <p>Toolzy automatically parses and normalizes all public Instagram video links. Supported URL structures include:</p>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item bg-transparent"><strong>Instagram Reels:</strong> <code>https://www.instagram.com/reel/shortcode/</code></li>
                <li class="list-group-item bg-transparent"><strong>Instagram Video Posts:</strong> <code>https://www.instagram.com/p/shortcode/</code></li>
                <li class="list-group-item bg-transparent"><strong>IGTV Posts:</strong> <code>https://www.instagram.com/tv/shortcode/</code></li>
                <li class="list-group-item bg-transparent"><strong>Mobile Share Links:</strong> <code>https://instagram.com/reel/shortcode/?igsh=xxxx</code></li>
            </ul>

            <h3 class="fw-bold mt-4 mb-3">How to Download Instagram Videos Step-by-Step</h3>
            <ol class="lh-lg mb-4">
                <li><strong>Copy the Video URL:</strong> Open Instagram on your mobile phone or browser, navigate to the Reel or video you wish to save, tap the three dots or share button, and select <em>"Copy Link"</em>.</li>
                <li><strong>Paste into Toolzy:</strong> Navigate to Toolzy's Instagram Video Downloader and paste the URL into the input bar above.</li>
                <li><strong>Process Media:</strong> Click the <strong>"Download Video"</strong> button. Our service automatically cleans tracking parameters and retrieves the original video metadata.</li>
                <li><strong>Save to Device:</strong> Preview the video, and click <strong>Download</strong> to save the MP4 video directly to your downloads folder.</li>
            </ol>

            <h3 class="fw-bold mt-4 mb-3">Why Choose Toolzy Instagram Downloader?</h3>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light h-100">
                        <h5 class="fw-bold text-primary">⚡ Original HD Quality</h5>
                        <p class="small text-muted mb-0">We extract original high-definition video streams without adding unwanted compression or watermarks.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light h-100">
                        <h5 class="fw-bold text-primary">🔒 100% Anonymous & Secure</h5>
                        <p class="small text-muted mb-0">No login, passwords, or personal details required. Your downloads remain private and untracked.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light h-100">
                        <h5 class="fw-bold text-primary">📱 Mobile & Cross-Platform</h5>
                        <p class="small text-muted mb-0">Works seamlessly on Android, iOS iPhone/iPad, Mac, Windows, and Linux across all modern web browsers.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light h-100">
                        <h5 class="fw-bold text-primary">🚀 Fast & Unlimited</h5>
                        <p class="small text-muted mb-0">No caps or limits on how many public videos you can download. Experience instant, serverless-speed processing.</p>
                    </div>
                </div>
            </div>

            <h3 class="fw-bold mt-4 mb-3">Privacy & Legal Guidelines</h3>
            <p class="small text-muted">
                Toolzy strictly respects intellectual property rights and creator privacy. This downloader only works for publicly accessible Instagram content. Media from private profiles or restricted posts cannot be accessed. Downloaded content should be used for personal offline viewing or educational reference. If redistributing content, please credit original creators and respect copyright laws.
            </p>
        </article>
    </div>
</div>
@endsection

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const instagramUrlInput = document.getElementById('instagramUrl');
    const downloaderForm = document.getElementById('downloaderForm');
    const closeErrorAlertBtn = document.getElementById('closeErrorAlertBtn');

    if (downloaderForm) {
        downloaderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            processUrl();
        });
    }

    if (closeErrorAlertBtn) {
        closeErrorAlertBtn.addEventListener('click', hideError);
    }
    const pasteBtn = document.getElementById('pasteBtn');
    const clearInputBtn = document.getElementById('clearInputBtn');
    const fetchBtn = document.getElementById('fetchBtn');
    const btnSpinner = document.getElementById('btnSpinner');
    const btnText = document.getElementById('btnText');
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');
    const loadingCard = document.getElementById('loadingCard');
    const mediaCard = document.getElementById('mediaCard');
    const mediaPlayer = document.getElementById('mediaPlayer');
    const videoSource = document.getElementById('videoSource');
    const mediaThumbnail = document.getElementById('mediaThumbnail');
    const userBadge = document.getElementById('userBadge');
    const captionText = document.getElementById('captionText');
    const downloadMediaBtn = document.getElementById('downloadMediaBtn');
    const copyLinkBtn = document.getElementById('copyLinkBtn');
    const resetBtn = document.getElementById('resetBtn');

    // Global state
    let currentData = null;

    // Track page view event
    if (typeof trackEvent === 'function') {
        trackEvent('tool_opened', { tool: 'instagram-video-downloader' });
    }

    // Input state listeners
    instagramUrlInput.addEventListener('input', toggleClearBtn);
    clearInputBtn.addEventListener('click', function() {
        instagramUrlInput.value = '';
        toggleClearBtn();
        hideError();
        instagramUrlInput.focus();
    });

    // Paste button listener
    pasteBtn.addEventListener('click', async function() {
        instagramUrlInput.focus();
        try {
            const text = await navigator.clipboard.readText();
            if (text) {
                instagramUrlInput.value = text.trim();
                toggleClearBtn();
                hideError();
            }
        } catch (err) {
            try {
                if (document.queryCommandSupported && document.queryCommandSupported('paste')) {
                    document.execCommand('paste');
                    toggleClearBtn();
                    hideError();
                }
            } catch (e) {
                instagramUrlInput.focus();
            }
        }
    });

    // Toggle clear input button visibility
    function toggleClearBtn() {
        if (instagramUrlInput.value.trim().length > 0) {
            clearInputBtn.classList.remove('d-none');
        } else {
            clearInputBtn.classList.add('d-none');
        }
    }

    // Fetch Video Listener
    fetchBtn.addEventListener('click', processUrl);
    instagramUrlInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            processUrl();
        }
    });

    // Process Instagram URL
    async function processUrl() {
        const rawUrl = instagramUrlInput.value.trim();
        hideError();
        mediaCard.classList.add('d-none');

        // Front-end URL validation
        if (!rawUrl) {
            showError('Please enter a valid Instagram URL.');
            if (typeof trackEvent === 'function') trackEvent('validation_error', { reason: 'empty_url' });
            return;
        }

        try {
            const parsed = new URL(rawUrl);
            const host = parsed.hostname.toLowerCase();
            if (!['instagram.com', 'www.instagram.com', 'm.instagram.com'].includes(host)) {
                showError('Only Instagram links (instagram.com) are supported. YouTube, Facebook, or other links are rejected.');
                if (typeof trackEvent === 'function') trackEvent('validation_error', { reason: 'invalid_domain' });
                return;
            }
            if (!parsed.pathname.match(/\/(reel|p|tv)\/[A-Za-z0-9_-]+/i)) {
                showError('Please enter a valid Instagram Reel, Post, or IGTV video URL (e.g., https://www.instagram.com/reel/shortcode/).');
                if (typeof trackEvent === 'function') trackEvent('validation_error', { reason: 'invalid_path' });
                return;
            }
        } catch (e) {
            showError('Invalid URL format. Please check the link and try again.');
            if (typeof trackEvent === 'function') trackEvent('validation_error', { reason: 'malformed_url' });
            return;
        }

        // Set Loading State
        setLoading(true);
        if (typeof trackEvent === 'function') trackEvent('download_attempted', { url: rawUrl });

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const response = await fetch('/api/instagram-downloader/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ url: rawUrl })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                const errMsg = data.error || 'Failed to process Instagram video. Post may be private or deleted.';
                showError(errMsg);
                if (typeof trackEvent === 'function') trackEvent('download_failed', { error: errMsg });
                return;
            }

            // Success: render media card
            currentData = data.data;
            renderMediaCard(currentData);
            if (typeof trackEvent === 'function') trackEvent('download_succeeded', { shortcode: currentData.shortcode });

        } catch (err) {
            showError('Network error or server timeout. Please check your connection and try again.');
            if (typeof trackEvent === 'function') trackEvent('download_failed', { error: err.message });
        } finally {
            setLoading(false);
        }
    }

    // Render Media Preview
    function renderMediaCard(item) {
        userBadge.textContent = `@${item.username || 'instagram_user'}`;
        captionText.textContent = item.caption || 'Instagram Video Post';

        // Direct Download link via proxy API endpoint
        const downloadProxyUrl = `/api/instagram-downloader/download?shortcode=${encodeURIComponent(item.shortcode)}&video_url=${encodeURIComponent(item.download_url)}`;
        downloadMediaBtn.setAttribute('href', downloadProxyUrl);

        // Render player or thumbnail fallback
        if (item.thumbnail) {
            mediaThumbnail.src = item.thumbnail;
            mediaPlayer.setAttribute('poster', item.thumbnail);
        }

        // Automatic fallback to thumbnail image if Instagram CDN blocks direct inline browser video playback
        const showThumbnailFallback = function() {
            if (item.thumbnail) {
                mediaPlayer.classList.add('d-none');
                mediaThumbnail.classList.remove('d-none');
            }
        };

        mediaPlayer.onerror = showThumbnailFallback;
        videoSource.onerror = showThumbnailFallback;

        if (item.download_url) {
            videoSource.src = item.download_url;
            mediaPlayer.load();
            mediaPlayer.classList.remove('d-none');
            mediaThumbnail.classList.add('d-none');
        } else if (item.thumbnail) {
            showThumbnailFallback();
        }



        mediaCard.classList.remove('d-none');
        mediaCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Reset Action
    resetBtn.addEventListener('click', function() {
        mediaCard.classList.add('d-none');
        instagramUrlInput.value = '';
        toggleClearBtn();
        hideError();
        instagramUrlInput.focus();
    });

    // Copy Link Action
    copyLinkBtn.addEventListener('click', function() {
        if (currentData && currentData.url) {
            if (typeof copyToClipboard === 'function') {
                copyToClipboard(currentData.url, copyLinkBtn, 'Original Link Copied!');
            } else {
                navigator.clipboard.writeText(currentData.url);
                showToast('Link copied to clipboard', 'success');
            }
        }
    });

    // UI Helpers
    function setLoading(isLoading) {
        if (isLoading) {
            fetchBtn.disabled = true;
            btnSpinner.classList.remove('d-none');
            btnText.textContent = 'Processing...';
            loadingCard.classList.remove('d-none');
        } else {
            fetchBtn.disabled = false;
            btnSpinner.classList.add('d-none');
            btnText.textContent = 'Download Video';
            loadingCard.classList.add('d-none');
        }
    }

    function showError(msg) {
        errorMessage.textContent = msg;
        errorAlert.classList.remove('d-none');
    }

    function hideError() {
        errorAlert.classList.add('d-none');
    }
});
</script>
@endpush
