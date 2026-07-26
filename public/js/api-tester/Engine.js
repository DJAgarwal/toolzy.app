/**
 * API Request Tester Engine - Toolzy
 * Handles 100% client-side HTTP request execution using Web Fetch API.
 * Never proxies requests through server.
 */

window.ApiEngine = (function () {
    /**
     * Executes an HTTP request using browser fetch()
     * @param {Object} options - { url, method, headers, body, timeoutMs, followRedirects }
     * @returns {Promise<Object>} Response object with metrics
     */
    async function sendRequest(options) {
        let {
            url,
            method = 'GET',
            headers = {},
            body = null,
            timeoutMs = 30000
        } = options;

        if (url && !/^https?:\/\//i.test(url.trim())) {
            url = 'https://' + url.trim();
        }

        const startTime = performance.now();
        let responseTime = 0;
        let controller = new AbortController();
        let timeoutId = setTimeout(() => controller.abort(), timeoutMs);

        // Filter forbidden header names like user-agent before sending fetch
        const safeHeaders = {};
        if (headers && typeof headers === 'object') {
            Object.keys(headers).forEach(k => {
                if (k.toLowerCase() !== 'user-agent') {
                    safeHeaders[k] = headers[k];
                }
            });
        }

        const reqHeaders = new Headers(safeHeaders);
        if (body instanceof FormData) {
            reqHeaders.delete('content-type');
            reqHeaders.delete('Content-Type');
        }

        const fetchOpts = {
            method: method.toUpperCase(),
            headers: reqHeaders,
            signal: controller.signal,
            mode: 'cors',
            credentials: 'omit' // prevent implicit cookie sending unless specified
        };

        // Do not attach body to GET or HEAD requests
        if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(fetchOpts.method) && body !== null) {
            fetchOpts.body = body;
        }

        try {
            const response = await fetch(url, fetchOpts);
            clearTimeout(timeoutId);
            const endTime = performance.now();
            responseTime = Math.round(endTime - startTime);

            // Extract Response Headers
            const responseHeaders = {};
            response.headers.forEach((val, key) => {
                responseHeaders[key.toLowerCase()] = val;
            });

            // Read Body Content & Size
            const blob = await response.blob();
            const rawText = await blob.text();
            const downloadSize = blob.size;

            // Attempt Content-Type detection
            const contentType = responseHeaders['content-type'] || 'text/plain';

            // Resource timing entries for DNS/TCP breakdown if available
            let timingDetails = null;
            if (window.performance && typeof window.performance.getEntriesByName === 'function') {
                const perfEntries = window.performance.getEntriesByName(url);
                if (perfEntries && perfEntries.length > 0) {
                    const entry = perfEntries[perfEntries.length - 1];
                    timingDetails = {
                        dns: entry.domainLookupEnd > 0 ? Math.round(entry.domainLookupEnd - entry.domainLookupStart) : 0,
                        tcp: entry.connectEnd > 0 ? Math.round(entry.connectEnd - entry.connectStart) : 0,
                        ttfb: entry.responseStart > 0 ? Math.round(entry.responseStart - (entry.requestStart || entry.startTime)) : Math.round(responseTime * 0.7),
                        download: entry.responseEnd > 0 && entry.responseStart > 0 ? Math.round(entry.responseEnd - entry.responseStart) : Math.round(responseTime * 0.3)
                    };
                }
            }

            if (!timingDetails) {
                // Approximate timing breakdown when ResourceTiming details are restricted by CORS
                timingDetails = {
                    dns: 0,
                    tcp: 0,
                    ttfb: Math.max(10, Math.round(responseTime * 0.7)),
                    download: Math.max(5, Math.round(responseTime * 0.3))
                };
            }

            return {
                ok: response.ok,
                status: response.status,
                statusText: response.statusText || getStatusText(response.status),
                responseTimeMs: responseTime,
                headers: responseHeaders,
                body: rawText,
                sizeBytes: downloadSize,
                contentType: contentType,
                timingDetails: timingDetails,
                isCorsRestricted: Object.keys(responseHeaders).length <= 2 // typical when CORS opaque or standard simple headers
            };
        } catch (err) {
            clearTimeout(timeoutId);
            const endTime = performance.now();
            responseTime = Math.round(endTime - startTime);

            let errorCategory = 'NETWORK_ERROR';
            let userFriendlyMessage = err.message || 'The request failed to complete.';

            if (err.name === 'AbortError') {
                errorCategory = 'TIMEOUT';
                userFriendlyMessage = `Request timed out after ${timeoutMs / 1000} seconds. The server did not respond in time.`;
            } else if (err.message && err.message.toLowerCase().includes('failed to fetch')) {
                // Classic CORS or network connection failure
                if (url.startsWith('https://') && location.protocol === 'http:') {
                    errorCategory = 'MIXED_CONTENT';
                    userFriendlyMessage = 'Mixed Content Error: Sending requests from HTTP to HTTPS or vice-versa may be blocked by browser security.';
                } else {
                    errorCategory = 'CORS_OR_NETWORK';
                    userFriendlyMessage = 'Network Failure / CORS Error: The request was blocked by the browser. This usually occurs when the target API server lacks Access-Control-Allow-Origin headers or the domain DNS failed.';
                }
            }

            return {
                ok: false,
                status: 0,
                statusText: errorCategory === 'TIMEOUT' ? 'Request Timeout' : 'Network Error / CORS Blocked',
                responseTimeMs: responseTime,
                headers: {},
                body: null,
                sizeBytes: 0,
                contentType: '',
                errorCategory: errorCategory,
                errorMessage: userFriendlyMessage
            };
        }
    }

    function getStatusText(status) {
        const map = {
            200: 'OK', 201: 'Created', 202: 'Accepted', 204: 'No Content',
            301: 'Moved Permanently', 302: 'Found', 304: 'Not Modified',
            400: 'Bad Request', 401: 'Unauthorized', 403: 'Forbidden', 404: 'Not Found',
            405: 'Method Not Allowed', 409: 'Conflict', 422: 'Unprocessable Entity', 429: 'Too Many Requests',
            500: 'Internal Server Error', 502: 'Bad Gateway', 503: 'Service Unavailable', 504: 'Gateway Timeout'
        };
        return map[status] || 'Unknown Status';
    }

    return {
        sendRequest: sendRequest,
        getStatusText: getStatusText
    };
})();
