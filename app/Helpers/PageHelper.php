<?php

namespace App\Helpers;

use Illuminate\Support\Facades\URL;
use App\Models\StaticPage;
use App\Models\Tool;

class PageHelper
{
    public static function pageMetadataAndBreadcrumbs($slug)
    {
        $slug = strtolower(trim($slug, '/'));
        $page_name = $slug === '' ? 'home' : $slug;
        $metadata = StaticPage::where('page_name', $page_name)->first();
        $page_type = 'static';
        if (!$metadata) {
            $metadata = Tool::where('page_name', $page_name)->first();
            $page_type = 'tools';
        }
        $jsonLd = json_decode($metadata->json_ld ?? '', true);
        $breadcrumbList = collect($jsonLd['@graph'] ?? [])->firstWhere('@type', 'BreadcrumbList');
        $uiBreadcrumbs = collect($breadcrumbList['itemListElement'] ?? [])->map(function ($item) {
            return [
                'name' => $item['name'],
                'url' => $item['item'],
            ];
        })->toArray();
        return [
            'page_type' => $page_type,
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
            'jsonld' => $metadata->json_ld ?? null,
            'breadcrumbs' => $uiBreadcrumbs,
        ];
    }
}