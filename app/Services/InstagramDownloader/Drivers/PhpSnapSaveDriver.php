<?php

namespace App\Services\InstagramDownloader\Drivers;

use App\Services\InstagramDownloader\Contracts\InstagramRetrievalInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhpSnapSaveDriver implements InstagramRetrievalInterface
{
    public function retrieve(string $shortcode, string $normalizedUrl): ?array
    {
        try {
            $response = Http::asForm()->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                'Referer' => 'https://snapsave.app/',
                'Origin' => 'https://snapsave.app',
            ])->timeout(5)->post('https://snapsave.app/action.php', [
                'url' => $normalizedUrl
            ]);

            if (!$response->successful()) {
                return null;
            }

            $unpacked = $this->unpackSnapSave($response->body());
            $html = stripcslashes($unpacked ?: $response->body());

            $videoUrl = null;
            $thumbnail = null;

            if (preg_match_all('/href="([^"]+)"/i', $html, $m)) {
                foreach ($m[1] as $link) {
                    $clean = stripcslashes($link);
                    if (str_contains($clean, 'rapidcdn') || str_contains($clean, 'fbcdn') || str_contains($clean, 'cdninstagram') || str_contains($clean, 'token=')) {
                        $videoUrl = $clean;
                        break;
                    }
                }
            }

            if (preg_match('/src="([^"]+)"/i', $html, $m2)) {
                $thumbnail = stripcslashes($m2[1]);
            }

            if ($videoUrl) {
                $meta = $this->fetchPublicMetadata($normalizedUrl);
                $username = $meta['username'] ?? 'instagram_user';
                $caption = $meta['caption'] ?? 'Instagram Video Post';

                return [
                    'shortcode' => $shortcode,
                    'download_url' => $videoUrl,
                    'thumbnail' => $thumbnail,
                    'username' => $username,
                    'caption' => $caption,
                    'resolutions' => [
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
                    ],
                    'source' => 'PhpSnapSaveDriver',
                ];
            }
        } catch (\Throwable $e) {
            Log::warning("PhpSnapSaveDriver error for shortcode {$shortcode}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Unpack SnapSave obfuscated JavaScript response in native PHP.
     */
    protected function unpackSnapSave(string $packedJs): ?string
    {
        try {
            if (preg_match('/decodeURIComponent\(escape\(r\)\)\}\s*\(\s*"([^"]+)"\s*,\s*(\d+)\s*,\s*"([^"]+)"\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/', $packedJs, $m)) {
                return $this->decodeSnapSave($m[1], (int)$m[2], $m[3], (int)$m[4], (int)$m[5], (int)$m[6]);
            }
            if (preg_match('/decodeURIComponent\(escape\(r\)\)\}\s*\(\s*\'([^\']+)\'\s*,\s*(\d+)\s*,\s*\'([^\']+)\'\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/', $packedJs, $m)) {
                return $this->decodeSnapSave($m[1], (int)$m[2], $m[3], (int)$m[4], (int)$m[5], (int)$m[6]);
            }
        } catch (\Throwable $e) {
            return null;
        }
        return null;
    }

    protected function _0xe20c(string $d, int $e, int $f): string
    {
        $charMap = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/";
        $h = substr($charMap, 0, $e);
        $i = substr($charMap, 0, $f);

        $chars = str_split($d);
        $reversed = array_reverse($chars);
        $j = 0;
        foreach ($reversed as $c => $b) {
            $pos = strpos($h, $b);
            if ($pos !== false) {
                $j += $pos * pow($e, $c);
            }
        }

        $k = "";
        $temp = (int)$j;
        while ($temp > 0) {
            $rem = $temp % $f;
            $k = $i[$rem] . $k;
            $temp = (int)(($temp - $rem) / $f);
        }

        return $k ?: "0";
    }

    protected function decodeSnapSave(string $h, int $u, string $n, int $t, int $e, int $r): string
    {
        $result = "";
        $len = strlen($h);
        $i = 0;
        $delimiterChar = $n[$e];

        while ($i < $len) {
            $s = "";
            while ($i < $len && $h[$i] !== $delimiterChar) {
                $s .= $h[$i];
                $i++;
            }
            $i++;

            if ($s === "") continue;

            for ($j = 0; $j < strlen($n); $j++) {
                $s = str_replace($n[$j], (string)$j, $s);
            }

            $code = (int)$this->_0xe20c($s, $e, 10) - $t;
            $result .= chr($code);
        }

        return urldecode(rawurldecode($result));
    }

    /**
     * Fetch public username and caption without blocking main video extraction.
     */
    protected function fetchPublicMetadata(string $url): array
    {
        try {
            $res = Http::withHeaders([
                'User-Agent' => 'TelegramBot (like TwitterBot)',
                'Accept-Language' => 'en-US,en;q=0.9',
            ])->timeout(2)->get($url);

            if ($res->successful()) {
                $html = $res->body();
                $title = null;
                $description = null;

                if (preg_match('/<meta\s+property="og:title"\s+content="([^"]+)"/i', $html, $m)) {
                    $title = html_entity_decode($m[1]);
                }
                if (preg_match('/<meta\s+property="og:description"\s+content="([^"]+)"/i', $html, $m)) {
                    $description = html_entity_decode($m[1]);
                }

                $username = null;
                $caption = null;

                if ($description && preg_match('/-\s*([a-zA-Z0-9_.]+)\s+on\s+/i', $description, $m)) {
                    $username = $m[1];
                } elseif ($title && preg_match('/^([^•:]+?)(?:\s+on\s+Instagram|\s*[:•])/i', $title, $m)) {
                    $username = trim($m[1]);
                }

                if ($title && preg_match('/:\s*"([^"]+)"/i', $title, $m)) {
                    $caption = trim($m[1]);
                } elseif ($description && preg_match('/:\s*"([^"]+)"/i', $description, $m)) {
                    $caption = trim($m[1]);
                } elseif ($title) {
                    $caption = $title;
                }

                return [
                    'username' => $username,
                    'caption' => $caption,
                ];
            }
        } catch (\Throwable $e) {
            // Silently ignore
        }

        return [];
    }
}
