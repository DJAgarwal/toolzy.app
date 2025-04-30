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
                'category' => 'File Utilities',
                'meta_title' => 'Online Image Converter - Toolzy',
                'meta_description' => 'Convert images to different formats (JPG, PNG, WebP, BMP, and more) instantly with Toolzy’s free Image Converter. Fast, secure, and easy to use.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Online Image Converter - Toolzy',
                    'url' => url('/tools/image-converter'),
                    'description' => 'Toolzy’s Online Image Converter lets you convert images between popular formats like JPG, PNG, WebP, and BMP with ease. No signup required.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'image-compressor',
                'category' => 'Image Utilities',
                'meta_title' => 'Image Compressor Online - Toolzy',
                'meta_description' => 'Compress images online for free with Toolzy. Reduce image file sizes without losing quality. Supports JPEG, PNG, WebP and more.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Image Compressor Online - Toolzy',
                    'url' => url('/tools/image-compressor'),
                    'description' => 'Toolzy’s Image Compressor helps you reduce image file size online quickly and efficiently. Compress multiple images while maintaining quality. Ideal for web use, email, and uploads.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            
            [
                'page_name' => 'pdf-converter',
                'category' => 'File Utilities',
                'meta_title' => 'PDF Converter - Convert Files to and from PDF Online | Toolzy',
                'meta_description' => 'Use Toolzy’s free PDF Converter to easily convert files to and from PDF format. Supports JPG, Word, Excel, and more. Fast, secure, and online.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'PDF Converter - Toolzy',
                    'url' => url('/tools/pdf-converter'),
                    'description' => 'Toolzy’s online PDF Converter lets you convert PDFs to JPG, Word, Excel, and other formats — or convert those files back to PDF. 100% free and user-friendly.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            
            [
                'page_name' => 'pdf-file-merger',
                'category' => 'File Utilities',
                'meta_title' => 'PDF File Merger - Merge PDF Files Online Free | Toolzy',
                'meta_description' => 'Easily merge multiple PDF files into one with Toolzy’s free online PDF File Merger. Fast, secure, and no watermark. Combine PDFs in seconds.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'PDF File Merger - Toolzy',
                    'url' => url('/tools/pdf-file-merger'),
                    'description' => 'Toolzy’s PDF File Merger allows you to easily combine multiple PDF documents into one seamless file. No installation, no watermark, 100% free.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            
            [
                'page_name' => 'text-file-merger',
                'category' => 'File Utilities',
                'meta_title' => 'Text File Merger - Toolzy',
                'meta_description' => 'Merge multiple files into one seamlessly with Toolzy’s free online Text File Merger. Quickly combine your documents without any software installation.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Text File Merger - Toolzy',
                    'url' => url('/tools/text-file-merger'),
                    'description' => 'Toolzy’s Text File Merger tool allows you to combine multiple text files into a single file effortlessly. Perfect for organizing documents or simplifying file management.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'qr-code-generator',
                'category' => 'Text Utilities',
                'meta_title' => 'QR Code Generator - Toolzy',
                'meta_description' => 'Generate QR codes easily with Toolzy’s free QR Code Generator. Convert any text, URL, or other information into a scannable QR code for seamless sharing and access.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'QR Code Generator - Toolzy',
                    'url' => url('/tools/qr-code-generator'),
                    'description' => 'Toolzy’s QR Code Generator creates scannable QR codes for URLs, text, or any other information to enhance digital accessibility and sharing.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'online-notepad',
                'category' => 'Productivity Tools',
                'meta_title' => 'Online Notepad - Toolzy',
                'meta_description' => 'Create and save notes easily with Toolzy’s free Online Notepad. Perfect for jotting down ideas, making lists, and keeping track of your thoughts on the go.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Online Notepad - Toolzy',
                    'url' => url('/tools/online-notepad'),
                    'description' => 'Toolzy’s Online Notepad lets you create, edit, and save notes instantly, making it a convenient and efficient tool for taking quick notes and keeping your thoughts organized.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            [
                'page_name' => 'base64-encoder-decoder',
                'category' => 'Text Utilities',
                'meta_title' => 'Base64 Encoder and Decoder - Toolzy',
                'meta_description' => 'Easily encode and decode Base64 with Toolzy’s free online Base64 Encoder and Decoder. Quickly convert text and files to Base64 and vice versa for secure transmission and storage.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Base64 Encoder and Decoder - Toolzy',
                    'url' => url('/tools/base64-encoder-decoder'),
                    'description' => 'Toolzy’s Base64 Encoder and Decoder allows you to quickly encode and decode Base64 strings. Perfect for encoding data for secure transmission or decoding encoded data for easier access.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
                        
            [
                'page_name' => 'json-formatter-validator',
                'category' => 'Text Utilities',
                'meta_title' => 'JSON Formatter and Validator - Toolzy',
                'meta_description' => 'Toolzy’s JSON Formatter and Validator helps you format and validate JSON data instantly. Ensure your JSON is properly structured and error-free with this easy-to-use online tool.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'JSON Formatter and Validator - Toolzy',
                    'url' => url('/tools/json-formatter-validator'),
                    'description' => 'Toolzy’s JSON Formatter and Validator helps you format and validate JSON data to ensure it’s properly structured and error-free.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],

            [
                'page_name' => 'random-password-generator',
                'category' => 'Text Utilities',
                'meta_title' => 'Random Password Generator - Toolzy',
                'meta_description' => 'Generate strong, secure random passwords with Toolzy’s free online Password Generator. Perfect for improving your online security quickly and easily.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Random Password Generator - Toolzy',
                    'url' => url('/tools/random-password-generator'),
                    'description' => 'Toolzy’s Random Password Generator creates strong and secure passwords instantly to help keep your online accounts safe and protected.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],  
            [
                'page_name' => 'word-character-counter',
                'category' => 'Text Utilities',
                'meta_title' => 'Word and Character Counter - Toolzy',
                'meta_description' => 'Use Toolzy’s Word and Character Counter to quickly calculate the number of words and characters in your text. Perfect for writing, editing, and content analysis.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Word and Character Counter Tool - Toolzy',
                    'url' => url('/word-character-counter'),
                    'description' => 'Toolzy’s Word and Character Counter helps you calculate word and character counts instantly. Useful for writers, editors, and anyone in need of text analysis.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],     
            [
                'page_name' => 'text-case-converter',
                'category' => 'Text Utilities',
                'meta_title' => 'Text Case Converter - Change Text to Uppercase, Lowercase, and More - Toolzy',
                'meta_description' => 'Instantly convert your text to uppercase, lowercase, sentence case, capitalized case, or toggle case with Toolzy’s free Text Case Converter. Easy, fast, and online.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Text Case Converter - Free Online Text Formatter - Toolzy',
                    'url' => url('/text-case-converter'),
                    'description' => 'Use Toolzy’s Text Case Converter to quickly switch your text between uppercase, lowercase, capitalized case, sentence case, and toggle case. Simplify your text formatting easily.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ], 
            [
                'page_name' => 'url-encoder-decoder',
                'category' => 'Text Utilities',
                'meta_title' => 'URL Encoder and Decoder - Toolzy',
                'meta_description' => 'Easily encode and decode URLs with Toolzy’s free online URL Encoder and Decoder tool. Simplify URL encoding and decoding for your web development needs.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'URL Encoder and Decoder - Toolzy',
                    'url' => url('/tools/url-encoder-decoder'),
                    'description' => 'Toolzy’s URL Encoder and Decoder tool helps you quickly and accurately encode and decode URLs for your web applications.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
            
            [
                'page_name' => 'text-to-slug-generator',
                'category' => 'Text Utilities',
                'meta_title' => 'Text to Slug Generator - Toolzy',
                'meta_description' => 'Generate SEO-friendly slugs for your content with Toolzy’s free Text to Slug Generator. Convert your text into clean, readable slugs for better URL optimization.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Text to Slug Generator - Toolzy',
                    'url' => url('/tools/text-to-slug-generator'),
                    'description' => 'Toolzy’s Text to Slug Generator converts your content into SEO-friendly slugs to help optimize URLs for better search engine ranking and readability.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
               
            [
                'page_name' => 'remove-duplicate-lines',
                'category' => 'Text Utilities',
                'meta_title' => 'Remove Duplicate Lines from Text - Toolzy',
                'meta_description' => 'Easily remove duplicate lines from any text with Toolzy’s online Duplicate Line Remover. Fast, simple, and effective for cleaning up your text.',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => 'Remove Duplicate Lines Tool - Toolzy',
                    'url' => url('/tools/remove-duplicate-lines'),
                    'description' => 'Toolzy’s Remove Duplicate Lines Tool helps you clean up your text by deleting repeated lines quickly and easily.',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],                                                                                   
        ];

        foreach ($pages as $page) {
            DB::table('tools')->updateOrInsert(
                ['page_name' => $page['page_name']],
                $page
            );
        }
    }
}
