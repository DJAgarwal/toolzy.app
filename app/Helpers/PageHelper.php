<?php

namespace App\Helpers;

use Illuminate\Support\Facades\URL;
use App\Models\StaticPage;
use App\Models\Tool;

class PageHelper
{
    public static function pageMetadataAndBreadcrumbs(string $slug = 'home')
    {
        $slug = strtolower(trim($slug, '/'));
        if ($slug === 'home') {
            $slug = '';
        }
        $page_name = $slug === '' ? 'home' : $slug;

        // Try StaticPage first
        $metadata = StaticPage::where('page_name', $page_name)->first();
        $page_type = 'static';

        // If not found in StaticPage, try Tool
        if (!$metadata) {
            $metadata = Tool::where('page_name', $page_name)->first();
            $page_type = 'tools';
        }

        $segments = ($slug && $slug !== '') ? explode('/', $slug) : [];
        $breadcrumbs = [];

        $breadcrumbs[] = [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => url('/')
        ];
        
        // If it's a tool, add "Tools" as part of the breadcrumb
        if (isset($page_type) && $page_type === 'tools' && $metadata) {
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Tools',
                'item' => url('/tools'),
            ];
        
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => ucwords(str_replace('-', ' ', $metadata->page_name)),
                'item' => url("/tools/{$metadata->page_name}"),
            ];
        } else {
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
            'jsonldBreadcrumbs' => json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'breadcrumbs' => $uiBreadcrumbs,
        ];
    }
}