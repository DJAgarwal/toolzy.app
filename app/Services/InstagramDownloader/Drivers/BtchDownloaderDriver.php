<?php

namespace App\Services\InstagramDownloader\Drivers;

use App\Services\InstagramDownloader\Contracts\InstagramRetrievalInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class BtchDownloaderDriver implements InstagramRetrievalInterface
{
    public function retrieve(string $shortcode, string $normalizedUrl): ?array
    {
        try {
            $nodeScript = __DIR__ . '/../scripts/fetch_ig_btch.js';
            if (!file_exists($nodeScript)) {
                return null;
            }

            $nodeBinary = $this->findNodeBinary();
            $env = $this->getExecutionEnvironment();

            // Set working directory to Laravel base_path() so Node.js resolves node_modules on Linux & Windows
            $process = new Process([$nodeBinary, $nodeScript, $normalizedUrl], base_path(), $env);
            $process->setTimeout(12);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::warning("BtchDownloaderDriver node process failed: " . $process->getErrorOutput());
                return null;
            }

            $output = trim($process->getOutput());
            $json = json_decode($output, true);

            if (!empty($json) && !empty($json['status']) && !empty($json['result'])) {
                $videoUrl = null;
                $thumbnail = null;

                foreach ($json['result'] as $item) {
                    if (!empty($item['url']) && trim($item['url']) !== '') {
                        $videoUrl = $item['url'];
                        $thumbnail = $item['thumbnail'] ?? null;
                        break;
                    }
                }

                if ($videoUrl) {
                    // Enrich with username & caption via fast public metadata scraper
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
                        'source' => 'BtchDownloaderDriver',
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::warning("BtchDownloaderDriver error for shortcode {$shortcode}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Locate Node.js executable across Linux, cPanel, macOS, and Windows environments.
     */
    protected function findNodeBinary(): string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return 'node';
        }

        $commonPaths = [
            '/usr/local/bin/node',
            '/usr/bin/node',
            '/bin/node',
            '/opt/cpanel/ea-nodejs20/bin/node',
            '/opt/cpanel/ea-nodejs18/bin/node',
            '/opt/cpanel/ea-nodejs16/bin/node',
        ];

        foreach ($commonPaths as $path) {
            if (@file_exists($path) && @is_executable($path)) {
                return $path;
            }
        }

        return 'node';
    }

    /**
     * Get platform-appropriate environment variables for child process execution.
     */
    protected function getExecutionEnvironment(): array
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return [
                'SystemRoot' => getenv('SystemRoot') ?: 'C:\\WINDOWS',
                'WINDIR' => getenv('WINDIR') ?: 'C:\\WINDOWS',
                'PATH' => getenv('PATH') ?: 'C:\\WINDOWS\\system32;C:\\Program Files\\nodejs',
                'PATHEXT' => getenv('PATHEXT') ?: '.COM;.EXE;.BAT;.CMD',
                'TEMP' => getenv('TEMP') ?: 'C:\\WINDOWS\\Temp',
                'TMP' => getenv('TMP') ?: 'C:\\WINDOWS\\Temp',
            ];
        }

        $existingPath = getenv('PATH') ?: '';
        return [
            'PATH' => '/usr/local/bin:/usr/bin:/bin:/usr/local/sbin:/usr/sbin:' . $existingPath,
            'HOME' => getenv('HOME') ?: '/tmp',
        ];
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
            // Silently ignore to preserve video extraction speed
        }

        return [];
    }
}
