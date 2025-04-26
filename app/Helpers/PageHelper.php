<?php

namespace App\Helpers;

use Illuminate\Support\Facades\URL;
use App\Models\PageMetadata;

class PageHelper
{
    public static function pageMetadataAndBreadcrumbs(string $slug = 'home')
    {
        $slug = strtolower(trim($slug, '/'));
        if ($slug === 'home') {
            $slug = '';
        }
        $page_name = $slug === '' ? 'home' : $slug;
        $metadata = PageMetadata::where('page_name', $page_name)->first();
        $segments = ($slug && $slug !== '') ? explode('/', $slug) : [];
        $breadcrumbs = [];

        $breadcrumbs[] = [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => url('/')
        ];

        $path = '';
        foreach ($segments as $index => $segment) {
            $path .= '/' . $segment;
            $name = match ($segment) {
                'about' => 'About Us',
                'contact' => 'Contact',
                default => ucwords(str_replace('-', ' ', $segment)),
            };

            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $index + 2,
                'name' => $name,
                'item' => url($path),
            ];
        }

        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        ];

        $uiBreadcrumbs = collect($breadcrumbs)->map(function ($item) {
            return [
                'name' => $item['name'],
                'url' => $item['item'],
            ];
        })->toArray();

        return [
            'metaTitle' => $metadata->meta_title ?? 'Natural Products India',
            'metaDescription' => $metadata->meta_description ?? 'Discover the best natural and safe products in India.',
            'metaKeywords' => $metadata->meta_keywords ?? 'natural products, skincare, ayurvedic, safe cosmetics, India',
            'canonical' => url()->current(),
            'og' => [
                'title' => $metadata->meta_title ?? 'Natural Products India',
                'description' => $metadata->meta_description ?? 'Explore India’s best natural and ingredient-safe products.',
                'url' => url()->current(),
                'image' => asset('images/preview.jpg'),
                'type' => 'website'
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $metadata->meta_title ?? 'Natural Products India',
                'description' => $metadata->meta_description ?? 'Explore India’s best natural and ingredient-safe products.',
                'image' => asset('images/preview.jpg'),
                'creator' => '@NaturalProductsIndia'
            ],
            'jsonldBreadcrumbs' => json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'breadcrumbs' => $uiBreadcrumbs,
        ];
    }
}