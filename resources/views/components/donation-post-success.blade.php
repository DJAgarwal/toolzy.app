<!-- Post-Success Donation CTA -->
<div id="post-success-donation-cta" 
     class="card border-0 shadow-lg rounded-4 p-3 position-fixed bottom-0 end-0 m-3 d-none z-3 post-success-cta-card" 
     role="alert" aria-live="polite" aria-label="Support future development suggestion">
    <div class="d-flex align-items-start gap-3">
        <!-- Heart Indicator -->
        <div class="bg-primary-subtle text-primary rounded-circle p-2.5 d-flex align-items-center justify-content-center flex-shrink-0 post-success-heart-wrapper" aria-hidden="true">
            <i class="bi bi-heart-pulse-fill fs-5"></i>
        </div>
        
        <!-- Content -->
        <div class="flex-grow-1">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fw-bold text-dark small">Toolzy saved you time?</span>
                <button type="button" class="btn-close ms-2 post-success-close-btn" id="dismiss-post-success-cta" aria-label="Dismiss this support message"></button>
            </div>
            <p class="text-secondary small mb-3">Support future development with a small donation to keep our tools free and browser-based.</p>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('/support-toolzy') }}" 
                   class="btn btn-primary btn-sm px-3 py-1.5 rounded-pill fw-bold shadow-sm"
                   id="support-toolzy-btn"
                   aria-label="Support Toolzy - Open support options">
                    Support Toolzy
                </a>
                <button type="button" class="btn btn-link text-decoration-none text-muted btn-sm fw-semibold" id="dismiss-link-cta" aria-label="Maybe later">Maybe later</button>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function() {
    const cta = document.getElementById('post-success-donation-cta');
    const dismissBtn = document.getElementById('dismiss-post-success-cta');
    const dismissLink = document.getElementById('dismiss-link-cta');
    const supportBtn = document.getElementById('support-toolzy-btn');
    
    const STORAGE_KEY = 'toolzy_donation_dismissed_at';
    const SESSION_KEY = 'toolzy_donation_shown_session';
    const HIDE_DAYS = 30;

    function shouldShow() {
        // 1. Check if dismissed in last 30 days
        const dismissedAt = localStorage.getItem(STORAGE_KEY);
        if (dismissedAt) {
            const diffTime = Math.abs(new Date() - new Date(parseInt(dismissedAt)));
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays <= HIDE_DAYS) {
                return false;
            }
        }
        // 2. Check if shown in current session
        if (sessionStorage.getItem(SESSION_KEY)) {
            return false;
        }
        return true;
    }

    function showCTA() {
        if (!shouldShow()) return;

        // Set session storage as shown
        sessionStorage.setItem(SESSION_KEY, 'true');

        // Display container
        cta.classList.remove('d-none');
        
        // Let browser render d-none removal before animating
        setTimeout(() => {
            cta.style.transform = 'translateY(0)';
            cta.style.opacity = '1';
            trackEvent('donation_cta_viewed');
        }, 100);
    }

    function dismissCTA(permanent = false) {
        cta.style.transform = 'translateY(100px)';
        cta.style.opacity = '0';
        
        setTimeout(() => {
            cta.classList.add('d-none');
        }, 400);

        if (permanent) {
            localStorage.setItem(STORAGE_KEY, Date.now().toString());
        }
    }

    // Dismiss events
    if (dismissBtn) dismissBtn.addEventListener('click', () => dismissCTA(true));
    if (dismissLink) dismissLink.addEventListener('click', () => dismissCTA(false));
    if (supportBtn) {
        supportBtn.addEventListener('click', function() {
            trackEvent('donation_cta_clicked');
            dismissCTA(false);
        });
    }

    // Hook into global toast success events to detect tool success
    if (window.showToast) {
        const originalShowToast = window.showToast;
        window.showToast = function(message, type = 'success') {
            originalShowToast(message, type);
            if (type === 'success') {
                // Delay slightly to let the toast appear first, then slide up the donation CTA
                setTimeout(showCTA, 1200);
            }
        };
    }
});
</script>
