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
            'metaTitle' => $metadata->meta_title ?? 'Toolzy - Free Online Tools for Everyone',
            'metaDescription' => $metadata->meta_description ?? 'Toolzy offers a collection of free online tools to simplify your daily tasks — fast, easy, and accessible for everyone.',
            'metaKeywords' => $metadata->meta_keywords ?? 'online tools, free tools, Toolzy, calculators, converters, productivity tools, web utilities',
            'canonical' => url()->current(),
            'og' => [
                'title' => $metadata->meta_title ?? 'Toolzy - Free Online Tools for Everyone',
                'description' => $metadata->meta_description ?? 'Simplify your daily tasks with Toolzy’s free calculators, converters, and web utilities.',
                'url' => url()->current(),
                'image' => asset('images/logo.webp'),
                'type' => 'website'
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $metadata->meta_title ?? 'Toolzy - Free Online Tools for Everyone',
                'description' => $metadata->meta_description ?? 'Simplify your daily tasks with Toolzy’s free calculators, converters, and web utilities.',
                'image' => asset('images/logo.webp'),
                'creator' => '@Toolzy'
            ],
            'jsonldBreadcrumbs' => json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'breadcrumbs' => $uiBreadcrumbs,
            'page_type' => $metadata->page_type,
        ];
    }
}