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

        return \Cache::remember("metadata_{$page_name}", 3600, function () use ($page_name, $slug) {
            $metadata = StaticPage::where('page_name', $page_name)->first();
            $page_type = 'static';
            if (!$metadata) {
                $metadata = Tool::where('page_name', $page_name)->first();
                $page_type = 'tools';
            }

            if (!$metadata) {
                return null;
            }

            $jsonLd = $metadata->json_ld;
            if (is_string($jsonLd)) {
                $jsonLd = json_decode($jsonLd, true);
            }
            
            if (!is_array($jsonLd)) {
                $jsonLd = [];
            }
            
            $breadcrumbList = collect($jsonLd['@graph'] ?? [])->firstWhere('@type', 'BreadcrumbList');
            $uiBreadcrumbs = [];
            if ($breadcrumbList && isset($breadcrumbList['itemListElement'])) {
                $uiBreadcrumbs = collect($breadcrumbList['itemListElement'])->map(function ($item) {
                    return [
                        'name' => $item['name'] ?? 'Unknown',
                        'url' => $item['item'] ?? '#',
                    ];
                })->toArray();
            }

            return [
                'page_type' => $page_type,
                'metaTitle' => $metadata->meta_title ?? 'Toolzy - Free Online Tools for Everyone',
                'metaDescription' => $metadata->meta_description ?? 'Toolzy offers a collection of free online tools to simplify your daily tasks — fast, easy, and accessible for everyone.',
                'metaKeywords' => $metadata->meta_keywords ?? 'online tools, free tools, Toolzy, calculators, converters, productivity tools, web utilities',
                'canonical' => url()->to($slug === 'home' ? '/' : $slug),
                'og' => [
                    'title' => $metadata->meta_title ?? 'Toolzy - Free Online Tools for Everyone',
                    'description' => $metadata->meta_description ?? 'Simplify your daily tasks with Toolzy’s free calculators, converters, and web utilities.',
                    'url' => url()->to($slug === 'home' ? '/' : $slug),
                    'image' => asset('images/logo.webp'),
                    'locale' => 'en_US',
                    'site_name' => 'Toolzy',
                    'type' => 'website'
                ],
                'twitter' => [
                    'card' => 'summary_large_image',
                    'title' => $metadata->meta_title ?? 'Toolzy - Free Online Tools for Everyone',
                    'description' => $metadata->meta_description ?? 'Simplify your daily tasks with Toolzy’s free calculators, converters, and web utilities.',
                    'image' => asset('images/logo.webp'),
                    'creator' => '@Toolzy',
                    'site' => '@Toolzy'
                ],
                'jsonld' => is_array($jsonLd) ? json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $jsonLd,
                'breadcrumbs' => $uiBreadcrumbs,
            ];
        });
    }
}