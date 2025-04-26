<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageMetadataSeeder extends Seeder
{
    public function run()
    {
        $pages = [
            [
                'page_name' => 'about',
                'meta_title' => 'About Us - Toolzy',
                'meta_description' => 'Learn about Toolzy — your ultimate platform for free, unlimited online tools designed for global users to simplify everyday digital tasks.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => 'Toolzy',
                    'url' => url('/about'),
                    'description' => 'Toolzy offers free and unlimited online tools for everyone — simple, fast, and reliable.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'contact',
                'meta_title' => 'Contact Us - Toolzy',
                'meta_description' => 'Get in touch with Toolzy for any inquiries, feedback, or suggestions. We’d love to hear from you!',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'ContactPage',
                    'name' => 'Contact Toolzy',
                    'url' => url('/contact'),
                    'description' => 'Reach out to Toolzy for feedback, questions, or anything else.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'privacy-policy',
                'meta_title' => 'Privacy Policy - Toolzy',
                'meta_description' => 'Read about how Toolzy collects, uses, and protects your data while you use our free tools.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Privacy Policy of Toolzy',
                    'url' => url('/privacy-policy'),
                    'description' => 'This Privacy Policy explains how Toolzy handles your personal data and information.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'terms-and-conditions',
                'meta_title' => 'Terms and Conditions - Toolzy',
                'meta_description' => 'Read the terms and conditions for using Toolzy’s free online tools and services.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Terms and Conditions of Toolzy',
                    'url' => url('/terms-and-conditions'),
                    'description' => 'The terms and conditions for using the online tools available on Toolzy.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'disclaimer',
                'meta_title' => 'Disclaimer - Toolzy',
                'meta_description' => 'Read the disclaimer regarding the accuracy, reliability, and liability of the tools and services provided by Toolzy.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Disclaimer of Toolzy',
                    'url' => url('/disclaimer'),
                    'description' => 'The disclaimer statement related to the tools provided by Toolzy.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'home',
                'meta_title' => 'Toolzy - Free Online Tools for Image Conversion, PDF Editing & More',
                'meta_description' => 'Explore a variety of free online tools on Toolzy: image conversions, PDF editing, and much more — all available at your fingertips.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Home',
                            'item' => url('/')
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            ],                                    
        ];

        foreach ($pages as $page) {
            DB::table('page_metadata')->updateOrInsert(
                ['page_name' => $page['page_name']],
                $page
            );
        }
    }
}
