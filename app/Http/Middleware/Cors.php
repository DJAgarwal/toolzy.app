<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $nonce = base64_encode(random_bytes(16));
        view()->share('cspNonce', $nonce);
        if ($request->isMethod('OPTIONS')) {
            return response('', 204)
                ->header('Access-Control-Allow-Origin', env('ORIGIN_BASE_PATH'))
                ->header('Access-Control-Allow-Methods', 'POST, GET, PATCH, OPTIONS, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization, Origin')
                ->header('X-XSS-Protection', '1; mode=block')
                ->header('Referrer-Policy', 'strict-origin-when-cross-origin')
                ->header('Permissions-Policy', 'geolocation=(self), microphone=()')
                ->header('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self'; frame-ancestors 'none'; form-action 'self'; base-uri 'self'; object-src 'none';");
            }

        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', env('ORIGIN_BASE_PATH'));
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization, Origin');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PATCH, OPTIONS, PUT, DELETE');
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' https://www.googletagmanager.com https://www.google-analytics.com 'nonce-{$nonce}' 'strict-dynamic' 'unsafe-inline'; style-src 'self' 'nonce-{$nonce}'; connect-src 'self' https://www.google-analytics.com https://cloudflareinsights.com; img-src 'self' https://www.google-analytics.com data:; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' https://www.googletagmanager.com https://www.google-analytics.com 'nonce-{$nonce}' 'strict-dynamic' 'unsafe-inline'; style-src 'self' 'nonce-{$nonce}' https://rsms.me;connect-src 'self' https://www.google-analytics.com https://cloudflareinsights.com;img-src 'self' https://www.google-analytics.com data:;object-src 'none';frame-ancestors 'none';base-uri 'self';form-action 'self';");
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(self), microphone=()');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        return $response;
    }
}