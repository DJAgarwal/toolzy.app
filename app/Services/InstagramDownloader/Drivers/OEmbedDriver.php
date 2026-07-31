<?php

namespace App\Services\InstagramDownloader\Drivers;

use App\Services\InstagramDownloader\Contracts\InstagramRetrievalInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OEmbedDriver implements InstagramRetrievalInterface
{
    public function retrieve(string $shortcode, string $normalizedUrl): ?array
    {
        $oembedUrl = "https://www.instagram.com/oembed/?url=" . urlencode($normalizedUrl);

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                'Accept' => 'application/json',
            ])->timeout(5)->get($oembedUrl);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['thumbnail_url'])) {
                    return [
                        'shortcode' => $shortcode,
                        'download_url' => null, // OEmbed does not directly give raw mp4 stream link
                        'thumbnail' => $data['thumbnail_url'] ?? null,
                        'username' => $data['author_name'] ?? 'instagram_user',
                        'caption' => $data['title'] ?? 'Instagram Reel',
                        'resolutions' => [],
                        'source' => 'OEmbedDriver',
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::warning("OEmbedDriver error for shortcode {$shortcode}: " . $e->getMessage());
        }

        return null;
    }
}
