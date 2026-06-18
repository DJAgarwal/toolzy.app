<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    private string $endpoint = 'https://api.indexnow.org/indexnow';
    private bool $enabled;
    private ?string $key;
    private ?string $host;

    public function __construct()
    {
        $this->enabled = config('indexnow.enabled', false);
        $this->key = config('indexnow.key');
        $this->host = config('indexnow.host');
    }

    /**
     * Submit a single URL to IndexNow.
     */
    public function submitUrl(string $url): void
    {
        $this->submitUrls([$url]);
    }

    /**
     * Submit multiple URLs to IndexNow in a batch.
     */
    public function submitUrls(array $urls): void
    {
        if (!$this->enabled) {
            return;
        }

        if (empty($this->key)) {
            Log::warning('IndexNow: API key is missing. Skipping submission.');
            return;
        }

        if (empty($urls)) {
            return;
        }

        // Filter and unique URLs
        $urls = array_unique(array_filter($urls));

        if (empty($urls)) {
            return;
        }

        try {
            $response = Http::post($this->endpoint, [
                'host' => $this->host,
                'key' => $this->key,
                'urlList' => $urls,
            ]);

            if ($response->successful()) {
                Log::info('IndexNow: Successfully submitted URLs.', [
                    'count' => count($urls),
                    'status' => $response->status(),
                    'urls' => $urls,
                ]);
            } else {
                Log::error('IndexNow: Failed to submit URLs.', [
                    'status' => $response->status(),
                    'reason' => $response->body(),
                    'urls' => $urls,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('IndexNow: Exception during submission.', [
                'message' => $e->getMessage(),
                'urls' => $urls,
            ]);
        }
    }
}
