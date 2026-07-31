<?php

namespace App\Services\InstagramDownloader\Drivers;

use App\Services\InstagramDownloader\Contracts\InstagramRetrievalInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmbedScraperDriver implements InstagramRetrievalInterface
{
    protected array $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1',
    ];

    public function retrieve(string $shortcode, string $normalizedUrl): ?array
    {
        // Strategy 1: Embed Page Scraping with iframe navigation headers
        $embedResult = $this->scrapeEmbedPage($shortcode);
        if ($embedResult && !empty($embedResult['download_url'])) {
            return $embedResult;
        }

        // Strategy 2: Direct Page / OpenGraph Scraping
        $pageResult = $this->scrapeDirectPage($normalizedUrl, $shortcode);
        if ($pageResult && !empty($pageResult['download_url'])) {
            return $pageResult;
        }

        return null;
    }

    /**
     * Scrape Instagram Embed iframe endpoint
     */
    protected function scrapeEmbedPage(string $shortcode): ?array
    {
        $embedUrl = "https://www.instagram.com/p/{$shortcode}/embed/captioned/";
        
        foreach ($this->userAgents as $ua) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => $ua,
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Sec-Fetch-Dest' => 'iframe',
                    'Sec-Fetch-Mode' => 'navigate',
                    'Sec-Fetch-Site' => 'cross-site',
                ])->timeout(4)->get($embedUrl);

                if (!$response->successful()) {
                    continue;
                }

                $html = $response->body();

                if (str_contains($html, 'PolarisErrorRoot.entrypoint')) {
                    continue;
                }

                $candidates = [];
                if (preg_match_all('/(https?:[\\\\\/]+[^\s"\'<>]+\.mp4[^\s"\'<>]*)/i', $html, $matches)) {
                    $candidates = $matches[1];
                }

                $videoUrl = $this->selectBestMuxedVideoUrl($candidates);

                if (!$videoUrl) {
                    if (preg_match('/"video_url":"([^"]+)"/', $html, $matches)) {
                        $videoUrl = stripcslashes($matches[1]);
                    } elseif (preg_match('/<video[^>]+src="([^"]+)"/i', $html, $matches)) {
                        $videoUrl = html_entity_decode($matches[1]);
                    }
                }

                $thumbnail = null;
                if (preg_match('/"display_url":"([^"]+)"/', $html, $matches)) {
                    $thumbnail = stripcslashes($matches[1]);
                } elseif (preg_match('/class="EmbeddedMediaImage"[^>]+src="([^"]+)"/i', $html, $matches)) {
                    $thumbnail = html_entity_decode($matches[1]);
                }

                $username = 'instagram_user';
                if (preg_match('/"username":"([^"]+)"/', $html, $matches)) {
                    $username = $matches[1];
                } elseif (preg_match('/class="UsernameText"[^>]*>([^<]+)</i', $html, $matches)) {
                    $username = trim($matches[1]);
                }

                $caption = null;
                if (preg_match('/class="Caption"[^>]*>(.*?)<\/div>/is', $html, $matches)) {
                    $caption = trim(strip_tags($matches[1]));
                }

                if ($videoUrl) {
                    return $this->formatResult([
                        'shortcode' => $shortcode,
                        'video_url' => $videoUrl,
                        'thumbnail' => $thumbnail,
                        'username' => $username,
                        'caption' => $caption,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning("EmbedScraperDriver embed error for shortcode {$shortcode}: " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Scrape Instagram direct post/reel page HTML
     */
    protected function scrapeDirectPage(string $normalizedUrl, string $shortcode): ?array
    {
        foreach ($this->userAgents as $ua) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => $ua,
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Cache-Control' => 'no-cache',
                ])->timeout(4)->get($normalizedUrl);

                if (!$response->successful()) {
                    continue;
                }

                $html = $response->body();

                if (str_contains($html, 'PolarisErrorRoot.entrypoint')) {
                    continue;
                }

                $candidates = [];
                if (preg_match_all('/(https?:[\\\\\/]+[^\s"\'<>]+\.mp4[^\s"\'<>]*)/i', $html, $matches)) {
                    $candidates = $matches[1];
                }

                $videoUrl = $this->selectBestMuxedVideoUrl($candidates);

                if (!$videoUrl) {
                    if (preg_match('/<meta\s+property="og:video(?::secure_url)?"\s+content="([^"]+)"/i', $html, $m)) {
                        $videoUrl = html_entity_decode($m[1]);
                    } elseif (preg_match('/<meta\s+content="([^"]+)"\s+property="og:video(?::secure_url)?"/i', $html, $m)) {
                        $videoUrl = html_entity_decode($m[1]);
                    }
                }

                $thumbnail = null;
                if (preg_match('/<meta\s+property="og:image(?::secure_url)?"\s+content="([^"]+)"/i', $html, $m)) {
                    $thumbnail = html_entity_decode($m[1]);
                }

                $title = null;
                if (preg_match('/<meta\s+property="og:title"\s+content="([^"]+)"/i', $html, $m)) {
                    $title = html_entity_decode($m[1]);
                }

                $username = 'instagram_user';
                if ($title && preg_match('/^([^•:]+?)(?:\s+on\s+Instagram|\s*[:•])/i', $title, $m)) {
                    $username = trim($m[1]);
                }

                if ($videoUrl) {
                    return $this->formatResult([
                        'shortcode' => $shortcode,
                        'video_url' => $videoUrl,
                        'thumbnail' => $thumbnail,
                        'username' => $username,
                        'caption' => $title,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning("EmbedScraperDriver page scrape error: " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Select the best candidate MP4 URL.
     */
    protected function selectBestMuxedVideoUrl(array $candidates): ?string
    {
        $scored = [];

        foreach ($candidates as $raw) {
            $clean = stripcslashes(str_replace(['\/', '&amp;', 'u0026'], ['/', '&', '&'], $raw));
            $clean = preg_replace('/(?:u003C|%3C|<).*$/i', '', $clean);
            $clean = trim($clean);

            if (!str_contains($clean, 'fbcdn.net') && !str_contains($clean, 'cdninstagram.com')) {
                continue;
            }

            parse_str(parse_url($clean, PHP_URL_QUERY) ?? '', $queryParams);
            $efg = isset($queryParams['efg']) ? base64_decode($queryParams['efg']) : '';

            $score = 10;

            if (str_contains($efg, 'xpv_progressive') || str_contains($efg, 'progressive_recipe') || str_contains($clean, 'progressive')) {
                $score = 100;
            } elseif (str_contains($efg, 'dash_baseline')) {
                $score = 5;
            } elseif (str_contains($efg, 'audio')) {
                $score = 1;
            }

            $scored[] = [
                'url' => $clean,
                'score' => $score,
            ];
        }

        if (empty($scored)) {
            return null;
        }

        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

        return $scored[0]['url'];
    }

    /**
     * Format result payload
     */
    protected function formatResult(array $raw): array
    {
        $shortcode = $raw['shortcode'];
        $videoUrl = $raw['video_url'];

        $videoUrl = str_replace(['\u0026', '&amp;'], '&', $videoUrl);
        $videoUrl = preg_replace('/(?:u003C|%3C|<).*$/i', '', $videoUrl);
        
        $thumbnail = !empty($raw['thumbnail']) ? str_replace(['\u0026', '&amp;'], '&', $raw['thumbnail']) : null;

        $resolutions = [
            [
                'label' => 'HD (1080p)',
                'quality' => '1080p',
                'format' => 'MP4',
                'url' => $videoUrl,
            ],
            [
                'label' => 'SD (720p)',
                'quality' => '720p',
                'format' => 'MP4',
                'url' => $videoUrl,
            ],
        ];

        return [
            'shortcode' => $shortcode,
            'download_url' => $videoUrl,
            'thumbnail' => $thumbnail,
            'username' => $raw['username'] ?? 'instagram_user',
            'caption' => $raw['caption'] ?? 'Instagram Video',
            'resolutions' => $resolutions,
            'source' => 'EmbedScraperDriver',
        ];
    }
}
