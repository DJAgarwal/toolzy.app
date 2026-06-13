<div class="card border-0 shadow-sm rounded-4 h-100 paytm-card" id="paytm-card-wrapper" data-upi-id="{{ config('donation.paytm.upi_id') }}" data-phone="{{ config('donation.paytm.number') }}">
    <div class="card-body p-4 d-flex flex-column justify-content-between">
        <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold">Indian Supporters</span>
                <span class="text-muted small"><i class="bi bi-shield-fill-check text-success me-1"></i>Secure UPI</span>
            </div>
            
            <h3 class="card-title fw-bold text-dark mb-2 fs-4">Support via UPI</h3>
            <p class="text-secondary small mb-4">Scan the QR code or use the details below to support Toolzy directly from any UPI app (Paytm, PhonePe, GPay, etc.).</p>

            <!-- QR Code Section -->
            <div class="text-center bg-light p-3 rounded-4 mb-4 position-relative border border-dashed border-secondary-subtle">
                <div class="d-inline-block p-2 bg-white rounded-3 shadow-sm mb-2">
                    @if(config('donation.paytm.qr_image'))
                        <img src="{{ asset(config('donation.paytm.qr_image')) }}" 
                             alt="UPI QR Code to support Toolzy" 
                             width="160" 
                             height="160" 
                             class="img-fluid d-block mx-auto rounded"
                             loading="lazy">
                    @endif
                </div>
                <div class="text-muted small fw-semibold">
                    <i class="bi bi-qr-code-scan me-1 text-primary"></i> Scan to Pay
                </div>
            </div>

            <!-- Payment details -->
            <div class="mb-4">
                <!-- UPI ID -->
                <div class="mb-3">
                    <label for="upi-id-input" class="form-label small fw-bold text-uppercase text-muted tracking-wider mb-1">UPI ID</label>
                    <div class="input-group">
                        <input type="text" id="upi-id-input" class="form-control bg-light border-0 fw-semibold text-dark rounded-start-3" 
                               value="{{ config('donation.paytm.upi_id') }}" readonly aria-label="UPI ID for donation">
                        <button class="btn btn-primary px-3 rounded-end-3 copy-upi-btn" type="button" id="copy-upi-btn" 
                                aria-label="Copy UPI ID to clipboard">
                            <i class="bi bi-clipboard me-1" id="copy-upi-icon" aria-hidden="true"></i> <span id="copy-upi-text">Copy</span>
                        </button>
                    </div>
                </div>

                <!-- Paytm Number -->
                <div>
                    <label class="form-label small fw-bold text-uppercase text-muted tracking-wider mb-1">Paytm Number / Mobile</label>
                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                        <span class="fw-semibold text-dark fs-5">{{ config('donation.paytm.number') }}</span>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 copy-paytm-num-btn" type="button"
                                aria-label="Copy Paytm mobile number">
                            <i class="bi bi-copy me-1" aria-hidden="true"></i> Copy Number
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <!-- Open UPI App (Mobile deep link) -->
            <a href="{{ config('donation.paytm.qr_data') }}" 
               class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold d-md-none d-flex align-items-center justify-content-center gap-2" 
               id="open-upi-app-btn"
               aria-label="Open UPI App to pay">
                <i class="bi bi-phone-vibrate-fill" aria-hidden="true"></i> Open UPI App
            </a>
            <div class="text-center text-muted small mt-2 d-md-none">
                Tap above to launch your installed UPI app.
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}">
(function() {
    function initPaytm() {
        const card = document.getElementById('paytm-card-wrapper');
        if (!card) return;
        const copyUpiBtn = card.querySelector('.copy-upi-btn');
        const upiVal = card.getAttribute('data-upi-id');
        if (copyUpiBtn && upiVal) {
            copyUpiBtn.addEventListener('click', function() {
                copyToClipboard(upiVal, copyUpiBtn, 'UPI ID copied!');
            });
        }
        
        const copyNumBtn = card.querySelector('.copy-paytm-num-btn');
        const numVal = card.getAttribute('data-phone');
        if (copyNumBtn && numVal) {
            copyNumBtn.addEventListener('click', function() {
                copyToClipboard(numVal, copyNumBtn, 'Copied Number!');
            });
        }
    }
    
    if (document.readyState !== 'loading') {
        initPaytm();
    } else {
        document.addEventListener('DOMContentLoaded', initPaytm);
    }
})();
</script>
