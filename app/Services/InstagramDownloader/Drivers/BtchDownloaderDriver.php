<?php

namespace App\Services\InstagramDownloader\Drivers;

use App\Services\InstagramDownloader\Contracts\InstagramRetrievalInterface;
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

            $process = new Process(['node', $nodeScript, $normalizedUrl]);
            $process->setTimeout(15);
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
                    return [
                        'shortcode' => $shortcode,
                        'download_url' => $videoUrl,
                        'thumbnail' => $thumbnail,
                        'username' => 'instagram_user',
                        'caption' => 'Instagram Video',
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
}
