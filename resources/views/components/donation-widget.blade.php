@props([
    'variant' => 'standard'
])

@if ($variant === 'compact')
    <!-- Compact Donation Widget -->
    <div class="card border-0 shadow-sm rounded-3 bg-light-subtle border-start border-primary border-3 py-2 px-3 mb-4 donation-widget compact-widget" 
         role="complementary" aria-label="Support Toolzy">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="fs-5" aria-hidden="true">❤️</span>
                <span class="text-dark fw-semibold small">Support Toolzy:</span>
                <span class="text-secondary small">Toolzy is free and privacy-focused. If it helped you, consider supporting development.</span>
            </div>
            <a href="{{ url('/support-toolzy') }}" 
               class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm hover-shadow donation-donate-btn"
               aria-label="Donate and support Toolzy">
                Donate <i class="bi bi-arrow-right-short" aria-hidden="true"></i>
            </a>
        </div>
    </div>

@elseif ($variant === 'large')
    <!-- Large Donation Widget -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 donation-widget large-widget widget-bg-large" 
         role="complementary" aria-label="Support Toolzy">
        <div class="card-body p-4 p-md-5 text-center">
            <div class="mb-3 display-6" aria-hidden="true">❤️</div>
            <h3 class="fw-bold text-dark mb-2">Support Toolzy Development</h3>
            <p class="text-secondary mx-auto max-width-600 mb-4">
                Toolzy is free, ad-free, and privacy-focused. We process everything in your browser, never storing or tracking your data. If our tools have saved you time, please consider supporting future maintenance, domain costs, and new tool development.
            </p>
            
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ url('/support-toolzy') }}" 
                   class="btn btn-primary btn-lg px-4 py-3 rounded-3 fw-bold shadow hover-shadow d-inline-flex align-items-center gap-2 donation-donate-btn"
                   aria-label="Support Toolzy with a donation">
                    <i class="bi bi-heart-fill" aria-hidden="true"></i> Support Toolzy
                </a>
                <a href="{{ url('/tools') }}" 
                   class="btn btn-outline-secondary btn-lg px-4 py-3 rounded-3 fw-semibold"
                   aria-label="Browse more developer tools">
                    Explore Tools
                </a>
            </div>
            
            <div class="mt-4 pt-3 border-top border-light-subtle d-flex align-items-center justify-content-center gap-4 text-muted small flex-wrap">
                <span><i class="bi bi-shield-check text-success me-1"></i> Browser-Based</span>
                <span><i class="bi bi-eye-slash text-success me-1"></i> No Tracking</span>
                <span><i class="bi bi-heart-pulse text-success me-1"></i> Voluntarily Supported</span>
            </div>
        </div>
    </div>

@else
    <!-- Standard Donation Widget (Default) -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 donation-widget standard-widget widget-bg-standard" 
         role="complementary" aria-label="Support Toolzy">
        <div class="card-body p-4 text-center">
            <div class="mb-2 fs-3" aria-hidden="true">❤️</div>
            <h4 class="card-title fw-bold text-dark mb-2">Support Toolzy</h4>
            <p class="card-text text-secondary mb-4 small mx-auto widget-max-width-480">
                Toolzy is free and privacy-focused. If it helped you, consider supporting development.
            </p>
            <a href="{{ url('/support-toolzy') }}" 
               class="btn btn-primary px-4 py-2.5 rounded-pill fw-bold shadow-sm hover-shadow d-inline-flex align-items-center gap-2 donation-donate-btn"
               aria-label="Support Toolzy with a donation">
                Donate <i class="bi bi-heart" aria-hidden="true"></i>
            </a>
        </div>
    </div>
@endif
