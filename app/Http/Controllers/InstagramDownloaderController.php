<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstagramDownloaderRequest;
use App\Services\InstagramDownloader\InstagramDownloaderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InstagramDownloaderController extends Controller
{
    protected InstagramDownloaderService $downloaderService;

    public function __construct(InstagramDownloaderService $downloaderService)
    {
        $this->downloaderService = $downloaderService;
    }

    /**
     * Process Instagram URL and retrieve media metadata.
     */
    public function process(InstagramDownloaderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->downloaderService->getMediaMetadata($validated['url']);

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 422);
        }

        return response()->json($result);
    }

    /**
     * Download the media file as a stream attachment.
     */
    public function download(Request $request): StreamedResponse|JsonResponse
    {
        $request->validate([
            'video_url' => 'required|string|url',
            'shortcode' => 'required|string|alpha_dash',
        ]);

        $videoUrl = $request->query('video_url') ?? $request->input('video_url');
        $shortcode = $request->query('shortcode') ?? $request->input('shortcode');

        return $this->downloaderService->proxyDownload($videoUrl, $shortcode);
    }
}
