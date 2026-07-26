/**
 * Performance Analyzer Module - Toolzy
 * Benchmarks response time, computes TTFB, payload size, compression ratios, and renders micro waterfall charts.
 */

window.PerformanceAnalyzer = (function () {
    /**
     * Renders performance metrics into container elements
     * @param {Object} res - Response object from ApiEngine
     */
    function render(res) {
        const perfContainer = document.getElementById('perfDashboardContainer');
        if (!perfContainer) return;

        if (!res || res.status === 0) {
            perfContainer.innerHTML = `
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Performance metrics unavailable due to request failure or network/CORS restriction.
                </div>
            `;
            return;
        }

        const totalTime = res.responseTimeMs || 1;
        const sizeBytes = res.sizeBytes || 0;
        const sizeKB = (sizeBytes / 1024).toFixed(2);

        const timing = res.timingDetails || {
            dns: 0, tcp: 0, ttfb: Math.round(totalTime * 0.7), download: Math.round(totalTime * 0.3)
        };

        const dnsPct = Math.max(2, Math.round((timing.dns / totalTime) * 100));
        const tcpPct = Math.max(2, Math.round((timing.tcp / totalTime) * 100));
        const ttfbPct = Math.max(5, Math.round((timing.ttfb / totalTime) * 100));
        const dlPct = Math.max(5, 100 - (dnsPct + tcpPct + ttfbPct));

        // Detect compression from headers
        const encoding = (res.headers['content-encoding'] || 'none').toLowerCase();
        let compressionStatus = 'Uncompressed';
        let estSavings = '0%';
        if (encoding.includes('gzip')) {
            compressionStatus = 'gzip Enabled';
            estSavings = '~70% saved';
        } else if (encoding.includes('br')) {
            compressionStatus = 'Brotli Enabled';
            estSavings = '~75% saved';
        }

        let html = `
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="perf-metric-box shadow-sm">
                        <div class="perf-metric-val text-primary">${totalTime} <span class="fs-6">ms</span></div>
                        <div class="perf-metric-lbl">Total Time</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="perf-metric-box shadow-sm">
                        <div class="perf-metric-val text-success">${timing.ttfb} <span class="fs-6">ms</span></div>
                        <div class="perf-metric-lbl">TTFB (Est.)</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="perf-metric-box shadow-sm">
                        <div class="perf-metric-val text-info">${sizeKB} <span class="fs-6">KB</span></div>
                        <div class="perf-metric-lbl">Payload Size</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="perf-metric-box shadow-sm">
                        <div class="perf-metric-val text-purple" style="color: #6f42c1;">${compressionStatus}</div>
                        <div class="perf-metric-lbl">${estSavings}</div>
                    </div>
                </div>
            </div>

            <div class="perf-card shadow-sm mb-3">
                <h6 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="bi bi-bar-chart-steps text-primary me-2"></i> Request Timing Waterfall
                </h6>
                <div class="perf-waterfall-track mb-3" title="Timing Breakdown">
                    ${timing.dns > 0 ? `<div class="perf-stage-dns" style="width: ${dnsPct}%;" data-bs-toggle="tooltip" title="DNS: ${timing.dns}ms"></div>` : ''}
                    ${timing.tcp > 0 ? `<div class="perf-stage-tcp" style="width: ${tcpPct}%;" data-bs-toggle="tooltip" title="TCP: ${timing.tcp}ms"></div>` : ''}
                    <div class="perf-stage-ttfb" style="width: ${ttfbPct}%;" data-bs-toggle="tooltip" title="TTFB: ${timing.ttfb}ms"></div>
                    <div class="perf-stage-download" style="width: ${dlPct}%;" data-bs-toggle="tooltip" title="Download: ${timing.download}ms"></div>
                </div>
                <div class="row text-center small text-muted g-2">
                    <div class="col"><span class="badge bg-purple me-1" style="background: #6f42c1;">•</span> DNS Lookup: ${timing.dns}ms</div>
                    <div class="col"><span class="badge bg-warning me-1">•</span> TCP Connect: ${timing.tcp}ms</div>
                    <div class="col"><span class="badge bg-primary me-1">•</span> TTFB: ${timing.ttfb}ms</div>
                    <div class="col"><span class="badge bg-success me-1">•</span> Download: ${timing.download}ms</div>
                </div>
            </div>
        `;

        perfContainer.innerHTML = html;
    }

    return {
        render: render
    };
})();
