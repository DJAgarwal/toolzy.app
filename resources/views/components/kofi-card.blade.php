<div class="card border-0 shadow-sm rounded-4 h-100 kofi-card" id="kofi-card-wrapper">
    <div class="card-body p-4 d-flex flex-column justify-content-between">
        <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="badge bg-light text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-semibold">International Supporters</span>
                <span class="text-muted small"><i class="bi bi-globe2 text-primary me-1"></i>Global Support</span>
            </div>
            
            <h3 class="card-title fw-bold text-dark mb-2 fs-4">Support via Ko-fi</h3>
            <p class="text-secondary small mb-4">For supporters outside of India. Ko-fi accepts PayPal, Stripe, and major Credit/Debit cards with zero platform fees for creators.</p>

            <!-- Ko-fi visual graphic/placeholder -->
            <div class="text-center bg-light p-4 rounded-4 mb-4 border border-dashed border-secondary-subtle d-flex flex-column align-items-center justify-content-center kofi-graphic-container">
                <div class="kofi-icon-container mb-3 p-3 bg-white rounded-circle shadow-sm">
                    <svg viewBox="0 0 24 24" width="36" height="36" fill="#FF5E5B" aria-hidden="true">
                        <path d="M23.881 8.948c-.773-4.085-4.859-4.593-4.859-4.593H.723c-.604 0-.679.798-.679.798v10.274c0 5.275 6.06 5.902 6.06 5.902l.004.037c.334.18.73.197 1.077.045 2.193-1.282 4.195-2.736 5.923-4.321H19c5.688 0 4.881-8.142 4.881-8.142zM19 12.352h-1.224a18.262 18.262 0 0 1-3.239 2.502c-1.358.82-2.73 1.579-4.108 2.278v-1.127c0-2.483.02-4.966-.002-7.449h8.572c2.404 0 2.404 3.796 0 3.796z"/>
                    </svg>
                </div>
                <div class="fw-bold text-dark fs-5 mb-1">Buy Toolzy a Coffee</div>
                <div class="text-muted small">Safe & secure external donation platform.</div>
            </div>
        </div>

        <div>
            <!-- Ko-fi Link Button -->
            <a href="{{ config('donation.kofi.url') }}" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="btn w-100 py-3 rounded-3 fw-bold fs-5 text-white d-flex align-items-center justify-content-center gap-2 kofi-btn" 
               id="kofi-donate-btn"
               aria-label="Support Toolzy on Ko-fi (opens in a new tab)">
                <i class="bi bi-heart-fill" aria-hidden="true"></i> Support on Ko-fi
            </a>
            <div class="text-center text-muted small mt-2">
                Redirects directly to Ko-fi.com.
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}">
(function() {
    function initKofi() {
        const btn = document.getElementById('kofi-donate-btn');
        if (btn) {
            btn.addEventListener('click', function() {
                trackEvent('kofi_clicked');
            });
        }
    }
    
    if (document.readyState !== 'loading') {
        initKofi();
    } else {
        document.addEventListener('DOMContentLoaded', initKofi);
    }
})();
</script>
