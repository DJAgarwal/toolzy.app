<?php

namespace App\Services\InstagramDownloader;

use App\Services\InstagramDownloader\Contracts\InstagramRetrievalInterface;
use App\Services\InstagramDownloader\Drivers\BtchDownloaderDriver;
use App\Services\InstagramDownloader\Drivers\EmbedScraperDriver;
use App\Services\InstagramDownloader\Drivers\OEmbedDriver;
use App\Services\InstagramDownloader\Drivers\YtDlpDriver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InstagramDownloaderService
{
    /**
     * Active drivers list for media retrieval strategies.
     * @var InstagramRetrievalInterface[]
     */
    protected array $drivers;

    public function __construct(?array $drivers = null)
    {
        $this->drivers = $drivers ?? [
            new BtchDownloaderDriver(),
            new EmbedScraperDriver(),
            new YtDlpDriver(),
            new OEmbedDriver(),
        ];
    }

    /**
     * Normalize Instagram URL by removing query parameters and standardizing format.
     */
    public function normalizeUrl(string $url): string
    {
        $parsed = parse_url(trim($url));
        $host = isset($parsed['host']) ? strtolower($parsed['host']) : 'www.instagram.com';
        
        if (!str_starts_with($host, 'www.')) {
            $host = 'www.' . ltrim($host, 'm.');
        }

        $path = $parsed['path'] ?? '/';
        if (!str_ends_with($path, '/')) {
            $path .= '/';
        }

        return "https://{$host}{$path}";
    }

    /**
     * Extract shortcode from an Instagram URL.
     */
    public function extractShortcode(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        if (preg_match('#/(reel|p|tv)/([A-Za-z0-9_-]+)#i', $path, $matches)) {
            return $matches[2];
        }
        return null;
    }

    /**
     * Retrieve media metadata and downloadable video URL.
     */
    public function getMediaMetadata(string $rawUrl): array
    {
        $normalizedUrl = $this->normalizeUrl($rawUrl);
        $shortcode = $this->extractShortcode($normalizedUrl);

        if (!$shortcode) {
            return [
                'success' => false,
                'error' => 'Invalid Instagram URL structure. Please provide a valid Reel, Post, or IGTV URL.',
            ];
        }

        $cacheKey = "ig_downloader_{$shortcode}";

        return Cache::remember($cacheKey, 1800, function () use ($shortcode, $normalizedUrl) {
            foreach ($this->drivers as $driver) {
                try {
                    $media = $driver->retrieve($shortcode, $normalizedUrl);

                    if ($media && !empty($media['download_url'])) {
                        return [
                            'success' => true,
                            'data' => [
                                'shortcode' => $shortcode,
                                'url' => $normalizedUrl,
                                'thumbnail' => $media['thumbnail'] ?? null,
                                'username' => $media['username'] ?? 'instagram_user',
                                'caption' => $media['caption'] ?? 'Instagram Video',
                                'download_url' => $media['download_url'],
                                'resolutions' => $media['resolutions'] ?? [
                                    [
                                        'label' => 'HD (1080p)',
                                        'quality' => '1080p',
                                        'format' => 'MP4',
                                        'url' => $media['download_url'],
                                    ]
                                ],
                                'source' => $media['source'] ?? 'Service',
                            ],
                        ];
                    }
                } catch (\Throwable $e) {
                    Log::error("Instagram retrieval driver error ({$shortcode}): " . $e->getMessage());
                }
            }

            return [
                'success' => false,
                'error' => 'Unable to fetch video media. The post might be private or deleted on Instagram.',
            ];
        });
    }

    /**
     * Securely proxy download video stream to avoid CORS and force file attachment download in browser.
     */
    public function proxyDownload(string $videoUrl, string $shortcode): StreamedResponse|\Illuminate\Http\JsonResponse
    {
        $host = parse_url($videoUrl, PHP_URL_HOST);
        if (!$host) {
            return response()->json(['error' => 'Invalid video stream URL'], 400);
        }

        $host = strtolower($host);
        $allowedDomains = [
            '.cdninstagram.com', '.fbcdn.net', '.instagram.com', 'cdninstagram.com', 'fbcdn.net', 'instagram.com',
            '.rapidcdn.app', 'rapidcdn.app', '.snapinst.app', 'snapinst.app', '.snapsave.app', 'snapsave.app', '.fastdl.app', 'fastdl.app'
        ];
        $isAllowed = false;

        foreach ($allowedDomains as $domain) {
            if (str_ends_with($host, $domain)) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            Log::warning("SSRF block attempted for host: {$host}");
            return response()->json(['error' => 'Unauthorized media host origin.'], 403);
        }

        $filename = "instagram_video_{$shortcode}.mp4";

        return response()->streamDownload(function () use ($videoUrl) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $videoUrl);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_USERAGENT, 'TelegramBot (like TwitterBot)');
            
            curl_exec($ch);
            curl_close($ch);
        }, $filename, [
            'Content-Type' => 'video/mp4',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, private',
        ]);
    }
}
