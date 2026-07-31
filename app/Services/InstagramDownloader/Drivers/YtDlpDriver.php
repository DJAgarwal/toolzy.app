<?php

namespace App\Services\InstagramDownloader\Drivers;

use App\Services\InstagramDownloader\Contracts\InstagramRetrievalInterface;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class YtDlpDriver implements InstagramRetrievalInterface
{
    public function retrieve(string $shortcode, string $normalizedUrl): ?array
    {
        try {
            $cmd = ['python', '-m', 'yt_dlp', '--dump-single-json', '--no-warnings', '--no-playlist', $normalizedUrl];

            $process = new Process($cmd);
            $process->setTimeout(15);
            $process->run();

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                $data = json_decode($output, true);

                if (!empty($data) && is_array($data)) {
                    $videoUrl = $data['url'] ?? null;
                    if (!$videoUrl && !empty($data['formats'])) {
                        foreach (array_reverse($data['formats']) as $fmt) {
                            if (!empty($fmt['url']) && (str_contains($fmt['url'], 'mp4') || str_contains($fmt['ext'] ?? '', 'mp4'))) {
                                $videoUrl = $fmt['url'];
                                break;
                            }
                        }
                    }

                    if ($videoUrl) {
                        return [
                            'shortcode' => $shortcode,
                            'download_url' => $videoUrl,
                            'thumbnail' => $data['thumbnail'] ?? null,
                            'username' => $data['uploader'] ?? $data['channel'] ?? 'instagram_user',
                            'caption' => $data['title'] ?? $data['description'] ?? 'Instagram Video',
                            'resolutions' => [
                                [
                                    'label' => 'HD (1080p)',
                                    'quality' => '1080p',
                                    'format' => 'MP4',
                                    'url' => $videoUrl,
                                ]
                            ],
                            'source' => 'YtDlpDriver',
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning("YtDlpDriver error for shortcode {$shortcode}: " . $e->getMessage());
        }

        return null;
    }
}
