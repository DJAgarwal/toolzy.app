<?php

namespace App\Services\InstagramDownloader\Contracts;

interface InstagramRetrievalInterface
{
    /**
     * Retrieve media metadata for a given shortcode and normalized Instagram URL.
     *
     * @param string $shortcode
     * @param string $normalizedUrl
     * @return array|null Returns formatted media array or null if failed.
     */
    public function retrieve(string $shortcode, string $normalizedUrl): ?array;
}
