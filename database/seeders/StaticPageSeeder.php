<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaticPageSeeder extends Seeder
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
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/about'),
                            'url' => url('/about'),
                            'name' => 'About Toolzy',
                            'description' => 'Learn more about Toolzy and our mission to provide free, unlimited digital tools to users worldwide.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/about')
                        ],
                        [
                            '@type' => 'Organization',
                            'name' => 'Toolzy',
                            'url' => url('/'),
                            'logo' => url('/images/logo.webp'),
                            'contactPoint' => [
                                '@type' => 'ContactPoint',
                                'contactType' => 'Customer Support',
                                'email' => 'dheerajagarwal1995@gmail.com',
                                'availableLanguage' => 'en'
                            ],
                            'description' => 'Toolzy offers free and unlimited online tools for everyone — simple, fast, and reliable.',
                            'sameAs' => [
                                'https://twitter.com/Toolzy',
                                'https://facebook.com/Toolzy'
                            ]
                        ],
                        [
                            "@type"=> "BreadcrumbList",
                            "itemListElement"=> [
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 1,
                                    "name"=> "Home",
                                    "item"=> url('/')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 2,
                                    "name"=> "About Us",
                                    "item"=> url('/about')
                                ]
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'contact',
                'meta_title' => 'Contact Us - Toolzy',
                'meta_description' => 'Get in touch with Toolzy for any inquiries, feedback, or suggestions. We’d love to hear from you!',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'ContactPage',
                            '@id' => url('/contact'),
                            'url' => url('/contact'),
                            'name' => 'Contact Toolzy',
                            'description' => 'Reach out to Toolzy for feedback, questions, or anything else.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/contact')
                        ],
                        [
                            '@type' => 'Organization',
                            'name' => 'Toolzy',
                            'url' => url('/'),
                            'logo' => url('/images/logo.webp'),
                            'contactPoint' => [
                                '@type' => 'ContactPoint',
                                'contactType' => 'Customer Support',
                                'email' => 'dheerajagarwal1995@gmail.com',
                                'availableLanguage' => 'en'
                            ],
                            'sameAs' => [
                                'https://twitter.com/Toolzy',
                                'https://facebook.com/Toolzy'
                            ]
                        ],
                        [
                            "@type"=> "BreadcrumbList",
                            "itemListElement"=> [
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 1,
                                    "name"=> "Home",
                                    "item"=> url('/')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 2,
                                    "name"=> "Contact Us",
                                    "item"=> url('/contact')
                                ]
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'privacy-policy',
                'meta_title' => 'Privacy Policy - Toolzy',
                'meta_description' => 'Read about how Toolzy collects, uses, and protects your data while you use our free tools.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/privacy-policy'),
                            'url' => url('/privacy-policy'),
                            'name' => 'Privacy Policy of Toolzy',
                            'description' => 'This Privacy Policy explains how Toolzy handles your personal data and information.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/privacy-policy')
                        ],
                        [
                            '@type' => 'Organization',
                            'name' => 'Toolzy',
                            'url' => url('/'),
                            'logo' => url('/images/logo.webp'),
                            'contactPoint' => [
                                '@type' => 'ContactPoint',
                                'contactType' => 'Customer Support',
                                'email' => 'dheerajagarwal1995@gmail.com',
                                'availableLanguage' => 'en'
                            ],
                            'sameAs' => [
                                'https://twitter.com/Toolzy',
                                'https://facebook.com/Toolzy'
                            ]
                            ],
                            [
                                "@type"=> "BreadcrumbList",
                                "itemListElement"=> [
                                    [
                                    "@type"=> "ListItem",
                                    "position"=> 1,
                                    "name"=> "Home",
                                    "item"=> url('/')
                                    ],
                                    [
                                    "@type"=> "ListItem",
                                    "position"=> 2,
                                    "name"=> "Privacy Policy",
                                    "item"=> url("/privacy-policy")
                                    ]
                                ]
                            ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'terms-and-conditions',
                'meta_title' => 'Terms and Conditions - Toolzy',
                'meta_description' => 'Read the terms and conditions for using Toolzy’s free online tools and services.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/terms-and-conditions'),
                            'name' => 'Terms and Conditions of Toolzy',
                            'url' => url('/terms-and-conditions'),
                            'description' => 'The terms and conditions for using the online tools available on Toolzy.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/terms-and-conditions')
                        ],
                        [
                            '@type' => 'Organization',
                            'name' => 'Toolzy',
                            'url' => url('/'),
                            'logo' => url('/images/logo.webp'),
                            'contactPoint' => [
                                '@type' => 'ContactPoint',
                                'contactType' => 'Customer Support',
                                'email' => 'dheerajagarwal1995@gmail.com',
                                'availableLanguage' => 'en'
                            ],
                            'sameAs' => [
                                'https://twitter.com/Toolzy',
                                'https://facebook.com/Toolzy'
                            ]
                        ],
                        [
                            "@type"=> "BreadcrumbList",
                            "itemListElement"=> [
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 1,
                                    "name"=> "Home",
                                    "item"=> url('/')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 2,
                                    "name"=> "Terms And Conditions",
                                    "item"=> url("/terms-and-conditions")
                                ]
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'disclaimer',
                'meta_title' => 'Disclaimer - Toolzy',
                'meta_description' => 'Read the disclaimer regarding the accuracy, reliability, and liability of the tools and services provided by Toolzy.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/disclaimer'),
                            'url' => url('/disclaimer'),
                            'name' => 'Disclaimer of Toolzy',
                            'description' => 'The disclaimer statement related to the tools provided by Toolzy.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/disclaimer')
                        ],
                        [
                            '@type' => 'Organization',
                            'name' => 'Toolzy',
                            'url' => url('/'),
                            'logo' => url('/images/logo.webp'),
                            'contactPoint' => [
                                '@type' => 'ContactPoint',
                                'contactType' => 'Customer Support',
                                'email' => 'dheerajagarwal1995@gmail.com',
                                'availableLanguage' => 'en'
                            ],
                            'sameAs' => [
                                'https://twitter.com/Toolzy',
                                'https://facebook.com/Toolzy'
                            ]
                        ],
                        [
                            "@type"=> "BreadcrumbList",
                            "itemListElement"=> [
                                [
                                "@type"=> "ListItem",
                                "position"=> 1,
                                "name"=> "Home",
                                "item"=> url('/')
                                ],
                                [
                                "@type"=> "ListItem",
                                "position"=> 2,
                                "name"=> "Disclaimer",
                                "item"=> url("/disclaimer")
                                ]
                            ]
                        ]
                    ]
                    
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'home',
                'meta_title' => 'Toolzy - Free Online Tools for Image Conversion, PDF Editing & More',
                'meta_description' => 'Explore a variety of free online tools on Toolzy: image conversions, PDF editing, and much more — all available at your fingertips.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/'),
                            'url' => url('/'),
                            'name' => 'Home Page - Toolzy',
                            'description' => 'Explore a variety of free online tools on Toolzy: image conversions, PDF editing, and much more — all available at your fingertips.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/')
                        ],
                        [
                            '@type' => 'Organization',
                            'name' => 'Toolzy',
                            'url' => url('/'),
                            'logo' => url('/images/logo.webp'),
                            'contactPoint' => [
                                '@type' => 'ContactPoint',
                                'contactType' => 'Customer Support',
                                'email' => 'dheerajagarwal1995@gmail.com',
                                'availableLanguage' => 'en'
                            ],
                            'sameAs' => [
                                'https://twitter.com/Toolzy',
                                'https://facebook.com/Toolzy'
                            ]
                        ],
                        [
                            "@type"=> "BreadcrumbList",
                            "itemListElement"=> [
                                [
                                "@type"=> "ListItem",
                                "position"=> 1,
                                "name"=> "Home",
                                "item"=> url("/")
                                ],
                            ]
                        ]
                    ]  
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            ],  
            [
                'page_name' => 'tools',
                'meta_title' => 'Free Online Tools - Toolzy',
                'meta_description' => 'Discover a wide range of free online tools at Toolzy, designed to simplify your daily tasks — calculators, converters, productivity utilities, and more.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools'),
                            'url' => url('/tools'),
                            'name' => 'Free Online Tools - Toolzy',
                            'description' => 'Explore a collection of free online tools to help with tasks like calculations, conversions, and much more at Toolzy.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools')
                        ],
                        [
                            '@type' => 'Organization',
                            'name' => 'Toolzy',
                            'url' => url('/'),
                            'logo' => url('/images/logo.webp'),
                            'contactPoint' => [
                                '@type' => 'ContactPoint',
                                'contactType' => 'Customer Support',
                                'email' => 'dheerajagarwal1995@gmail.com',
                                'availableLanguage' => 'en'
                            ],
                            'sameAs' => [
                                'https://twitter.com/Toolzy',
                                'https://facebook.com/Toolzy'
                            ]
                        ],
                        [
                            "@type"=> "BreadcrumbList",
                            "itemListElement"=> [
                                [
                                "@type"=> "ListItem",
                                "position"=> 1,
                                "name"=> "Home",
                                "item"=> url('/')
                                ],
                                [
                                "@type"=> "ListItem",
                                "position"=> 2,
                                "name"=> "Tools",
                                "item"=> url("/tools")
                                ],
                            ]
                        ]
                    ]  
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],                                                                       
        ];
        foreach ($pages as $page) {
            DB::table('static_pages')->updateOrInsert(
                ['page_name' => $page['page_name']],
                $page
            );
        }
    }
}