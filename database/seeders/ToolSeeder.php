<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToolSeeder extends Seeder
{
    public function run()
    {
        $pages = [  
            [
                'page_name' => 'image-converter',
                'category' => 'Image Tools',
                'meta_title' => 'Online Image Converter - Toolzy',
                'meta_description' => 'Convert images to JPG, PNG, WebP, BMP, GIF, and more instantly with Toolzy’s free Image Converter. Batch process, reorder, resize, add watermarks, and preview images—all securely in your browser with no uploads or signups required. Fast, private, and easy to use.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/image-converter'),
                            'name' => 'Online Image Converter - Toolzy',
                            'url' => url('/tools/image-converter'),
                            'description' => 'Convert images to JPG, PNG, WebP, BMP, GIF, and more instantly with Toolzy’s free Image Converter. Batch process, reorder, resize, add watermarks, and preview images—all securely in your browser with no uploads or signups required. Fast, private, and easy to use.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/image-converter')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Image Converter",
                                    "item"=> url('tools/image-converter')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Image Converter",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Convert images to JPG, PNG, WebP, BMP, GIF, and more instantly with Toolzy’s free Image Converter. Batch process, reorder, resize, add watermarks, and preview images—all securely in your browser with no uploads or signups required. Fast, private, and easy to use.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'image-compressor',
                'category' => 'Image Tools',
                'meta_title' => 'Online Image Compressor - Toolzy',
                'meta_description' => 'Compress images online instantly with Toolzy. Reduce JPG, PNG, WebP, and AVIF file sizes without quality loss. Fast, free, secure, no uploads—works 100% in your browser. Perfect for web, social, and government forms.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/image-compressor'),
                            'name' => 'Online Image Compressor - Toolzy',
                            'url' => url('/tools/image-compressor'),
                            'description' => 'Compress images online instantly with Toolzy. Reduce JPG, PNG, WebP, and AVIF file sizes without quality loss. Fast, free, secure, no uploads—works 100% in your browser. Perfect for web, social, and government forms.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/image-compressor')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Image Compressor",
                                    "item"=> url('/tools/image-compressor')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Image Compressor",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Compress images online instantly with Toolzy. Reduce JPG, PNG, WebP, and AVIF file sizes without quality loss. Fast, free, secure, no uploads—works 100% in your browser. Perfect for web, social, and government forms.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ], 
            [
                'page_name' => 'pdf-file-merger',
                'category' => 'PDF & Document Tools',
                'meta_title' => 'Online PDF File Merger - Toolzy',
                'meta_description' => 'Easily merge multiple PDF files into one with Toolzy’s free online PDF File Merger. Fast, secure, private and no watermark. Combine PDFs in seconds.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/pdf-file-merger'),
                            'name' => 'Online PDF File Merger - Toolzy',
                            'url' => url('/tools/pdf-file-merger'),
                            'description' => 'Toolzy’s PDF File Merger allows you to easily combine multiple PDF documents into one seamless file. No installation, no watermark, private and 100% free.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/pdf-file-merger')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Pdf File Merger",
                                    "item"=> url('/tools/pdf-file-merger')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Pdf File Merger",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s PDF File Merger allows you to easily combine multiple PDF documents into one seamless file. No installation, no watermark, private and 100% free.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'text-file-merger',
                'category' => 'PDF & Document Tools',
                'meta_title' => 'Online Text File Merger - Toolzy',
                'meta_description' => 'Merge multiple files into one seamlessly with Toolzy’s free online Text File Merger. Quickly combine your documents without any software installation.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/text-file-merger'),
                            'name' => 'Online Text File Merger - Toolzy',
                            'url' => url('/tools/text-file-merger'),
                            'description' => 'Toolzy’s Text File Merger tool allows you to combine multiple text files into a single file effortlessly. Perfect for organizing documents or simplifying file management.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/text-file-merger')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Text File Merger",
                                    "item"=> url('/tools/text-file-merger')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Text File Merger",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s Text File Merger tool allows you to combine multiple text files into a single file effortlessly. Perfect for organizing documents or simplifying file management.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'qr-code-generator',
                'category' => 'Miscellaneous',
                'meta_title' => 'Online QR Code Generator - Toolzy',
                'meta_description' => 'Generate QR codes easily with Toolzy’s free QR Code Generator. Convert any text, URL, or other information into a scannable QR code for seamless sharing and access.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/qr-code-generator'),
                            'name' => 'Online QR Code Generator - Toolzy',
                            'url' => url('/tools/qr-code-generator'),
                            'description' => 'Toolzy’s QR Code Generator creates scannable QR codes for URLs, text, or any other information to enhance digital accessibility and sharing.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/qr-code-generator')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "QR Code Generator",
                                    "item"=> url('/tools/qr-code-generator')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "QR Code Generator",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s QR Code Generator creates scannable QR codes for URLs, text, or any other information to enhance digital accessibility and sharing.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'online-notepad',
                'category' => 'Text Utilities',
                'meta_title' => 'Online Notepad - Toolzy',
                'meta_description' => 'Create and save notes easily with Toolzy’s free Online Notepad. Perfect for jotting down ideas, making lists, and keeping track of your thoughts on the go.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/online-notepad'),
                            'name' => 'Online Notepad - Toolzy',
                            'url' => url('/tools/online-notepad'),
                            'description' => 'Toolzy’s Online Notepad lets you create, edit, and save notes instantly, making it a convenient and efficient tool for taking quick notes and keeping your thoughts organized.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/online-notepad')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Online Notepad",
                                    "item"=> url('/tools/online-notepad')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Online Notepad",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s Online Notepad lets you create, edit, and save notes instantly, making it a convenient and efficient tool for taking quick notes and keeping your thoughts organized.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'base64-encoder-decoder',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online Base64 Encoder and Decoder - Toolzy',
                'meta_description' => 'Easily encode and decode Base64 with Toolzy’s free online Base64 Encoder and Decoder. Quickly convert text and files to Base64 and vice versa for secure transmission and storage.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/base64-encoder-decoder'),
                            'name' => 'Online Base64 Encoder and Decoder - Toolzy',
                            'url' => url('/tools/base64-encoder-decoder'),
                            'description' => 'Toolzy’s Base64 Encoder and Decoder allows you to quickly encode and decode Base64 strings. Perfect for encoding data for secure transmission or decoding encoded data for easier access.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/base64-encoder-decoder')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Base64 Encoder Decoder",
                                    "item"=> url('/tools/base64-encoder-decoder')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Base64 Encoder Decoder",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s Base64 Encoder and Decoder allows you to quickly encode and decode Base64 strings. Perfect for encoding data for secure transmission or decoding encoded data for easier access.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],            
            [
                'page_name' => 'json-formatter-validator',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online JSON Formatter and Validator - Toolzy',
                'meta_description' => 'Toolzy’s JSON Formatter and Validator helps you format and validate JSON data instantly. Ensure your JSON is properly structured and error-free with this easy-to-use online tool.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/json-formatter-validator'),
                            'name' => 'Online JSON Formatter and Validator - Toolzy',
                            'url' => url('/tools/json-formatter-validator'),
                            'description' => 'Toolzy’s JSON Formatter and Validator helps you format and validate JSON data to ensure it’s properly structured and error-free.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/json-formatter-validator')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Json Formatter Validator",
                                    "item"=> url('/tools/json-formatter-validator')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Json Formatter Validator",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s JSON Formatter and Validator helps you format and validate JSON data to ensure it’s properly structured and error-free.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'random-password-generator',
                'category' => 'Security',
                'meta_title' => 'Online Random Password Generator - Toolzy',
                'meta_description' => 'Generate strong, secure random passwords with Toolzy’s free online Password Generator. Perfect for improving your online security quickly and easily.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/random-password-generator'),
                            'name' => 'Online Random Password Generator - Toolzy',
                            'url' => url('/tools/random-password-generator'),
                            'description' => 'Toolzy’s Random Password Generator creates strong and secure passwords instantly to help keep your online accounts safe and protected.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/random-password-generator')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Random Password Generator",
                                    "item"=> url('/tools/random-password-generator')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Random Password Generator",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s Random Password Generator creates strong and secure passwords instantly to help keep your online accounts safe and protected.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],  
            [
                'page_name' => 'word-character-counter',
                'category' => 'Text Utilities',
                'meta_title' => 'Online Word and Character Counter - Toolzy',
                'meta_description' => 'Use Toolzy’s Word and Character Counter to quickly calculate the number of words and characters in your text. Perfect for writing, editing, and content analysis.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/word-character-counter'),
                            'name' => 'Online Word and Character Counter - Toolzy',
                            'url' => url('/word-character-counter'),
                            'description' => 'Toolzy’s Word and Character Counter helps you calculate word and character counts instantly. Useful for writers, editors, and anyone in need of text analysis.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/word-character-counter')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Word Character Counter",
                                    "item"=> url('/tools/word-character-counter')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Word Character Counter",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s Word and Character Counter helps you calculate word and character counts instantly. Useful for writers, editors, and anyone in need of text analysis.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],     
            [
                'page_name' => 'text-case-converter',
                'category' => 'Text Utilities',
                'meta_title' => 'Online Text Case Converter - Toolzy',
                'meta_description' => 'Instantly convert your text to uppercase, lowercase, sentence case, capitalized case, or toggle case with Toolzy’s free Text Case Converter. Easy, fast, and online.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('tools/text-case-converter'),
                            'name' => 'Online Text Case Converter - Toolzy',
                            'url' => url('tools/text-case-converter'),
                            'description' => 'Use Toolzy’s Text Case Converter to quickly switch your text between uppercase, lowercase, capitalized case, sentence case, and toggle case. Simplify your text formatting easily.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('tools/text-case-converter')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Text Case Converter",
                                    "item"=> url('/tools/text-case-converter')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Text Case Converter",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Use Toolzy’s Text Case Converter to quickly switch your text between uppercase, lowercase, capitalized case, sentence case, and toggle case. Simplify your text formatting easily.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ], 
            [
                'page_name' => 'url-encoder-decoder',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online URL Encoder and Decoder - Toolzy',
                'meta_description' => 'Easily encode and decode URLs with Toolzy’s free online URL Encoder and Decoder tool. Simplify URL encoding and decoding for your web development needs.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/url-encoder-decoder'),
                            'name' => 'Online URL Encoder and Decoder - Toolzy',
                            'url' => url('/tools/url-encoder-decoder'),
                            'description' => 'Toolzy’s URL Encoder and Decoder tool helps you quickly and accurately encode and decode URLs for your web applications.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/url-encoder-decoder')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Url Encoder Decoder",
                                    "item"=> url('/tools/url-encoder-decoder')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Url Encoder Decoder",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s URL Encoder and Decoder tool helps you quickly and accurately encode and decode URLs for your web applications.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            
            [
                'page_name' => 'text-to-slug-generator',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online Text to Slug Generator - Toolzy',
                'meta_description' => 'Generate SEO-friendly slugs for your content with Toolzy’s free Text to Slug Generator. Convert your text into clean, readable slugs for better URL optimization.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' =>[
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/text-to-slug-generator'),
                            'name' => 'Online Text to Slug Generator - Toolzy',
                            'url' => url('/tools/text-to-slug-generator'),
                            'description' => 'Toolzy’s Text to Slug Generator converts your content into SEO-friendly slugs to help optimize URLs for better search engine ranking and readability.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/text-to-slug-generator')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Text To Slug Generator",
                                    "item"=> url('/tools/text-to-slug-generator')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Text To Slug Generator",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s Text to Slug Generator converts your content into SEO-friendly slugs to help optimize URLs for better search engine ranking and readability.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],   
            [
                'page_name' => 'remove-duplicate-lines',
                'category' => 'Text Utilities',
                'meta_title' => 'Online Remove Duplicate Lines from Text - Toolzy',
                'meta_description' => 'Easily remove duplicate lines from any text with Toolzy’s online Duplicate Line Remover. Fast, simple, and effective for cleaning up your text.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/remove-duplicate-lines'),
                            'name' => 'Online Remove Duplicate Lines from Text - Toolzy',
                            'url' => url('/tools/remove-duplicate-lines'),
                            'description' => 'Toolzy’s Remove Duplicate Lines Tool helps you clean up your text by deleting repeated lines quickly and easily.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/remove-duplicate-lines')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Remove Duplicate Lines",
                                    "item"=> url('/tools/remove-duplicate-lines')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Remove Duplicate Lines",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "UtilitiesApplication",
                            "description"=> "Toolzy’s Remove Duplicate Lines Tool helps you clean up your text by deleting repeated lines quickly and easily.",
                            "offers"=> [
                            "@type"=> "Offer",
                            "price"=> "0",
                            "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'jwt-decoder',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online JWT Decoder & Validator - Toolzy',
                'meta_description' => 'Decode and validate JWT tokens instantly in your browser. View claims, expiration dates, headers, payloads, and verify signatures securely without uploading data.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/jwt-decoder'),
                            'name' => 'Online JWT Decoder & Validator - Toolzy',
                            'url' => url('/tools/jwt-decoder'),
                            'description' => 'Decode and validate JWT tokens instantly in your browser. View claims, expiration dates, headers, payloads, and verify signatures securely without uploading data.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/jwt-decoder')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "JWT Decoder",
                                    "item"=> url('/tools/jwt-decoder')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "JWT Decoder & Validator",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "DeveloperApplication",
                            "description"=> "Decode and validate JWT tokens instantly in your browser. View claims, expiration dates, headers, payloads, and verify signatures securely without uploading data.",
                            "offers"=> [
                                "@type"=> "Offer",
                                "price"=> "0",
                                "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'regex-tester',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online Live Regex Match Tester and Validator - Toolzy',
                'meta_description' => 'Test regular expressions instantly in your browser. Validate regex patterns, highlight matches, inspect capture groups, and debug expressions with live results.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/regex-tester'),
                            'name' => 'Online Live Regex Match Tester and Validator - Toolzy',
                            'url' => url('/tools/regex-tester'),
                            'description' => 'Test regular expressions instantly in your browser. Validate regex patterns, highlight matches, inspect capture groups, and debug expressions with live results.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/regex-tester')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Regex Tester & Validator",
                                    "item"=> url('/tools/regex-tester')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Regex Tester & Validator",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "DeveloperApplication",
                            "description"=> "Test regular expressions instantly in your browser. Validate regex patterns, highlight matches, inspect capture groups, and debug expressions with live results.",
                            "offers"=> [
                                "@type"=> "Offer",
                                "price"=> "0",
                                "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'uuid-generator',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online UUID v1 v4 Bulk Generator - Toolzy',
                'meta_description' => 'Generate UUIDs instantly in your browser. Supports UUID v1 and v4, bulk generation, duplicate checking, export options, and developer-friendly formatting.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/uuid-generator'),
                            'name' => 'Online UUID v1 v4 Bulk Generator - Toolzy',
                            'url' => url('/tools/uuid-generator'),
                            'description' => 'Generate UUIDs instantly in your browser. Supports UUID v1 and v4, bulk generation, duplicate checking, export options, and developer-friendly formatting.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/uuid-generator')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "UUID Generator",
                                    "item"=> url('/tools/uuid-generator')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "UUID Generator",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "DeveloperApplication",
                            "description"=> "Generate UUIDs instantly in your browser. Supports UUID v1 and v4, bulk generation, duplicate checking, export options, and developer-friendly formatting.",
                            "offers"=> [
                                "@type"=> "Offer",
                                "price"=> "0",
                                "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],

            // [
            //     'page_name' => 'timestamp-converter',
            //     'category' => 'Developer & SEO Tools',
            //     'meta_title' => 'Unix Timestamp Converter & Epoch Time Tool - Free Online | Toolzy',
            //     'meta_description' => 'Convert Unix timestamps to human-readable dates and dates to timestamps instantly. Supports UTC, local time, milliseconds, timezone conversion, and analysis.',
            //     'json_ld' => json_encode([
            //         '@context' => 'https://schema.org',
            //         '@graph' => [
            //             [
            //                 '@type' => 'WebPage',
            //                 '@id' => url('/tools/timestamp-converter'),
            //                 'name' => 'Unix Timestamp Converter & Epoch Time Tool - Free Online | Toolzy',
            //                 'url' => url('/tools/timestamp-converter'),
            //                 'description' => 'Convert Unix timestamps to human-readable dates and dates to timestamps instantly. Supports UTC, local time, milliseconds, timezone conversion, and analysis.',
            //                 'inLanguage' => 'en',
            //                 'mainEntityOfPage' => url('/tools/timestamp-converter')
            //             ],
            //             [
            //                 '@type' => 'Organization',
            //                 'name' => 'Toolzy',
            //                 'url' => url('/'),
            //                 'logo' => url('/images/logo.webp'),
            //                 'contactPoint' => [
            //                     '@type' => 'ContactPoint',
            //                     'contactType' => 'Customer Support',
            //                     'email' => 'dheerajagarwal1995@gmail.com',
            //                     'availableLanguage' => 'en'
            //                 ],
            //                 'sameAs' => [
            //                     'https://twitter.com/Toolzy',
            //                     'https://facebook.com/Toolzy'
            //                 ]
            //             ],
            //             [
            //                 "@type"=> "BreadcrumbList",
            //                 "itemListElement"=> [
            //                     [
            //                         "@type"=> "ListItem",
            //                         "position"=> 1,
            //                         "name"=> "Home",
            //                         "item"=> url('/')
            //                     ],
            //                     [
            //                         "@type"=> "ListItem",
            //                         "position"=> 2,
            //                         "name"=> "Tools",
            //                         "item"=> url('/tools')
            //                     ],
            //                     [
            //                         "@type"=> "ListItem",
            //                         "position"=> 3,
            //                         "name"=> "Unix Timestamp Converter",
            //                         "item"=> url('/tools/timestamp-converter')
            //                     ]
            //                 ]
            //             ],
            //             [
            //                 "@type"=> "SoftwareApplication",
            //                 "name"=> "Unix Timestamp Converter",
            //                 "operatingSystem"=> "All",
            //                 "applicationCategory"=> "DeveloperApplication",
            //                 "description"=> "Convert Unix timestamps to human-readable dates and dates to timestamps instantly. Supports UTC, local time, milliseconds, timezone conversion, and analysis.",
            //                 "offers"=> [
            //                     "@type"=> "Offer",
            //                     "price"=> "0",
            //                     "priceCurrency"=> "USD"
            //                 ]
            //             ]
            //         ]
            //     ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            // ],
            [
                'page_name' => 'json-csv-converter',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online Free JSON to CSV & CSV to JSON Converter - Toolzy',
                'meta_description' => 'Convert JSON to CSV and CSV to JSON instantly in your browser. Supports nested objects, arrays, file uploads, data validation, and downloadable exports.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/json-csv-converter'),
                            'name' => 'Online Free JSON to CSV & CSV to JSON Converter - Toolzy',
                            'url' => url('/tools/json-csv-converter'),
                            'description' => 'Convert JSON to CSV and CSV to JSON instantly in your browser. Supports nested objects, arrays, file uploads, data validation, and downloadable exports.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/json-csv-converter')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "JSON ↔ CSV Converter",
                                    "item"=> url('/tools/json-csv-converter')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "JSON ↔ CSV Converter",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "DeveloperApplication",
                            "description"=> "Convert JSON to CSV and CSV to JSON instantly in your browser. Supports nested objects, arrays, file uploads, data validation, and downloadable exports.",
                            "offers"=> [
                                "@type"=> "Offer",
                                "price"=> "0",
                                "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'cron-generator',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online Cron Expression Generator & Validator - Toolzy',
                'meta_description' => 'Build, validate, parse, and understand cron expressions instantly. Generate cron schedules, preview next run times, and convert cron syntax into human-readable descriptions.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/cron-generator'),
                            'name' => 'Online Cron Expression Generator & Validator - Toolzy',
                            'url' => url('/tools/cron-generator'),
                            'description' => 'Build, validate, parse, and understand cron expressions instantly. Generate cron schedules, preview next run times, and convert cron syntax into human-readable descriptions.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/cron-generator')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Cron Expression Generator",
                                    "item"=> url('/tools/cron-generator')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Cron Expression Generator",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "DeveloperApplication",
                            "description"=> "Build, validate, parse, and understand cron expressions instantly. Generate cron schedules, preview next run times, and convert cron syntax into human-readable descriptions.",
                            "offers"=> [
                                "@type"=> "Offer",
                                "price"=> "0",
                                "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'json-diff-checker',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online JSON Diff Checker & Compare Tool - Toolzy',
                'meta_description' => 'Compare two JSON documents instantly in your browser. Detect changed values, added fields, removed fields, and structural differences with side-by-side reports.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/json-diff-checker'),
                            'name' => 'Online JSON Diff Checker & Compare Tool - Toolzy',
                            'url' => url('/tools/json-diff-checker'),
                            'description' => 'Compare two JSON documents instantly in your browser. Detect changed values, added fields, removed fields, and structural differences with side-by-side reports.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/json-diff-checker')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "JSON Diff Checker",
                                    "item"=> url('/tools/json-diff-checker')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "JSON Diff Checker",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "DeveloperApplication",
                            "description"=> "Compare two JSON documents instantly in your browser. Detect changed values, added fields, removed fields, and structural differences with side-by-side reports.",
                            "offers"=> [
                                "@type"=> "Offer",
                                "price"=> "0",
                                "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'hash-generator',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online Hash Generator & Checker (MD5, SHA1, SHA256, SHA512) - Toolzy',
                'meta_description' => 'Generate and verify cryptographic hashes instantly. Supports MD5, SHA1, SHA256, SHA512, file hashing, checksum verification, and secure browser-based processing.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/hash-generator'),
                            'name' => 'Online Hash Generator & Checker (MD5, SHA1, SHA256, SHA512) - Toolzy',
                            'url' => url('/tools/hash-generator'),
                            'description' => 'Generate and verify cryptographic hashes instantly. Supports MD5, SHA1, SHA256, SHA512, file hashing, checksum verification, and secure browser-based processing.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/hash-generator')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "Hash Generator",
                                    "item"=> url('/tools/hash-generator')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "Hash Generator & Checker",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "DeveloperApplication",
                            "description"=> "Generate and verify cryptographic hashes instantly. Supports MD5, SHA1, SHA256, SHA512, file hashing, checksum verification, and secure browser-based processing.",
                            "offers"=> [
                                "@type"=> "Offer",
                                "price"=> "0",
                                "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            // [
            //     'page_name' => 'sql-formatter',
            //     'category' => 'Developer & SEO Tools',
            //     'meta_title' => 'SQL Formatter, Beautifier & Validator Online - Free | Toolzy',
            //     'meta_description' => 'Format, beautify, minify, and validate SQL queries instantly in your browser. Supports MySQL, PostgreSQL, SQL Server, syntax highlighting, and query analysis.',
            //     'json_ld' => json_encode([
            //         '@context' => 'https://schema.org',
            //         '@graph' => [
            //             [
            //                 '@type' => 'WebPage',
            //                 '@id' => url('/tools/sql-formatter'),
            //                 'name' => 'SQL Formatter, Beautifier & Validator Online - Free | Toolzy',
            //                 'url' => url('/tools/sql-formatter'),
            //                 'description' => 'Format, beautify, minify, and validate SQL queries instantly in your browser. Supports MySQL, PostgreSQL, SQL Server, syntax highlighting, and query analysis.',
            //                 'inLanguage' => 'en',
            //                 'mainEntityOfPage' => url('/tools/sql-formatter')
            //             ],
            //             [
            //                 '@type' => 'Organization',
            //                 'name' => 'Toolzy',
            //                 'url' => url('/'),
            //                 'logo' => url('/images/logo.webp'),
            //                 'contactPoint' => [
            //                     '@type' => 'ContactPoint',
            //                     'contactType' => 'Customer Support',
            //                     'email' => 'dheerajagarwal1995@gmail.com',
            //                     'availableLanguage' => 'en'
            //                 ],
            //                 'sameAs' => [
            //                     'https://twitter.com/Toolzy',
            //                     'https://facebook.com/Toolzy'
            //                 ]
            //             ],
            //             [
            //                 "@type"=> "BreadcrumbList",
            //                 "itemListElement"=> [
            //                     [
            //                         "@type"=> "ListItem",
            //                         "position"=> 1,
            //                         "name"=> "Home",
            //                         "item"=> url('/')
            //                     ],
            //                     [
            //                         "@type"=> "ListItem",
            //                         "position"=> 2,
            //                         "name"=> "Tools",
            //                         "item"=> url('/tools')
            //                     ],
            //                     [
            //                         "@type"=> "ListItem",
            //                         "position"=> 3,
            //                         "name"=> "SQL Formatter",
            //                         "item"=> url('/tools/sql-formatter')
            //                     ]
            //                 ]
            //             ],
            //             [
            //                 "@type"=> "SoftwareApplication",
            //                 "name"=> "SQL Formatter & Validator",
            //                 "operatingSystem"=> "All",
            //                 "applicationCategory"=> "DeveloperApplication",
            //                 "description"=> "Format, beautify, minify, and validate SQL queries instantly in your browser. Supports MySQL, PostgreSQL, SQL Server, syntax highlighting, and query analysis.",
            //                 "offers"=> [
            //                     "@type"=> "Offer",
            //                     "price"=> "0",
            //                     "priceCurrency"=> "USD"
            //                 ]
            //             ]
            //         ]
            //     ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            // ],
            [
                'page_name' => 'xml-formatter',
                'category' => 'Developer & SEO Tools',
                'meta_title' => 'Online XML Formatter, Validator & Beautifier - Toolzy',
                'meta_description' => 'Format, beautify, validate, minify, and analyze XML documents instantly. Supports XML validation, tree view visualization, and downloadable exports.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'WebPage',
                            '@id' => url('/tools/xml-formatter'),
                            'name' => 'XML Formatter, Validator & Beautifier Online - Free | Toolzy',
                            'url' => url('/tools/xml-formatter'),
                            'description' => 'Format, beautify, validate, minify, and analyze XML documents instantly. Supports XML validation, tree view visualization, and downloadable exports.',
                            'inLanguage' => 'en',
                            'mainEntityOfPage' => url('/tools/xml-formatter')
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
                                    "item"=> url('/tools')
                                ],
                                [
                                    "@type"=> "ListItem",
                                    "position"=> 3,
                                    "name"=> "XML Formatter",
                                    "item"=> url('/tools/xml-formatter')
                                ]
                            ]
                        ],
                        [
                            "@type"=> "SoftwareApplication",
                            "name"=> "XML Formatter & Validator",
                            "operatingSystem"=> "All",
                            "applicationCategory"=> "DeveloperApplication",
                            "description"=> "Format, beautify, validate, minify, and analyze XML documents instantly. Supports XML validation, tree view visualization, and downloadable exports.",
                            "offers"=> [
                                "@type"=> "Offer",
                                "price"=> "0",
                                "priceCurrency"=> "USD"
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],                                                                                   
        ];
        foreach ($pages as $page) {
            \App\Models\Tool::updateOrCreate(
                ['page_name' => $page['page_name']],
                $page
            );
        }
    }
}
