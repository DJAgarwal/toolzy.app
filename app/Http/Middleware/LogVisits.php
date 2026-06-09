<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $botKeywords = [
            'bot', 'crawl', 'spider', 'slurp', 'facebook', 'meta-externalagent',
            'headless', 'okhttp', 'go-http-client', 'secscan', 'python-requests',
            'wget', 'curl', 'node-fetch'
        ];
        
        $userAgent = $request->userAgent();
        $isBot = false;
        
        if ($userAgent) {
            foreach ($botKeywords as $keyword) {
                if (stripos($userAgent, $keyword) !== false) {
                    $isBot = true;
                    break;
                }
            }
        }
        
        $type = $isBot ? 'bot' : 'human';

        Log::info('Page hit', [
            'type' => $type,
            // 'userAgent' => $userAgent,
            'path' => $request->path(),
            'referer' => $request->header('referer'),
            // 'ip' => $request->ip(),
        ]);

        return $next($request);
    }
}
