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
                'page_name' => 'word-character-counter',
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
            // [
            //     'page_name' => 'remove-duplicate-lines',
            //     'meta_title' => 'Remove Duplicate Lines from Text - Toolzy',
            //     'meta_description' => 'Easily remove duplicate lines from any text with Toolzy’s online Duplicate Line Remover. Fast, simple, and effective for cleaning up your text.',
            //     'json_ld' => json_encode([
            //         '@context' => 'https://schema.org',
            //         '@type' => 'WebPage',
            //         'name' => 'Remove Duplicate Lines Tool - Toolzy',
            //         'url' => url('/tools/remove-duplicate-lines'),
            //         'description' => 'Toolzy’s Remove Duplicate Lines Tool helps you clean up your text by deleting repeated lines quickly and easily.',
            //     ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            // ],                                                                
        ];

        foreach ($pages as $page) {
            DB::table('tools')->updateOrInsert(
                ['page_name' => $page['page_name']],
                $page
            );
        }
    }
}
