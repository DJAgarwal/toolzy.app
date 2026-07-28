<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaticPage;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $posts = [
            [
                'page_name' => 'blog/optimize-token-usage-coding-agents',
                'meta_title' => 'How to Optimize Token Usage in AI Coding Agents: A Practical Developer\'s Guide - Toolzy Blog',
                'meta_description' => 'Learn how to reduce token consumption in AI coding agents using context engineering, prompt optimization, tool management, model selection, and workflow design. Discover practical techniques to lower costs, improve response speed, and increase development efficiency.',
                'meta_keywords' => 'optimize token usage, AI coding agents, context engineering, LLM cost reduction, prompt optimization, AI developer workflows, token efficiency, Claude Code, Cursor optimization',
                'json_ld' => json_encode([
                    '@context' => 'https://schema.org',
                    '@graph' => [
                        [
                            '@type' => 'BlogPosting',
                            '@id' => url('/blog/optimize-token-usage-coding-agents'),
                            'url' => url('/blog/optimize-token-usage-coding-agents'),
                            'headline' => 'How to Optimize Token Usage in AI Coding Agents: A Practical Developer\'s Guide',
                            'description' => 'Learn how to reduce token consumption in AI coding agents using context engineering, prompt optimization, tool management, model selection, and workflow design. Discover practical techniques to lower costs, improve response speed, and increase development efficiency.',
                            'inLanguage' => 'en',
                            'keywords' => 'optimize token usage, AI coding agents, context engineering, LLM cost reduction, prompt optimization, AI developer workflows',
                            'articleSection' => 'AI & Developer Tools',
                            'mainEntityOfPage' => url('/blog/optimize-token-usage-coding-agents'),
                            'author' => [
                                '@type' => 'Organization',
                                'name' => 'Toolzy Team',
                                'url' => url('/')
                            ],
                            'publisher' => [
                                '@type' => 'Organization',
                                'name' => 'Toolzy',
                                'url' => url('/'),
                                'logo' => url('/images/logo.webp')
                            ]
                        ],
                        [
                            '@type' => 'BreadcrumbList',
                            'itemListElement' => [
                                [
                                    '@type' => 'ListItem',
                                    'position' => 1,
                                    'name' => 'Home',
                                    'item' => url('/')
                                ],
                                [
                                    '@type' => 'ListItem',
                                    'position' => 2,
                                    'name' => 'Blog',
                                    'item' => url('/blog')
                                ],
                                [
                                    '@type' => 'ListItem',
                                    'position' => 3,
                                    'name' => 'Optimize Token Usage in AI Coding Agents',
                                    'item' => url('/blog/optimize-token-usage-coding-agents')
                                ]
                            ]
                        ]
                    ]
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ],
        ];

        $urls = [];
        StaticPage::withoutEvents(function () use ($posts, &$urls) {
            foreach ($posts as $post) {
                StaticPage::updateOrCreate(
                    ['page_name' => $post['page_name']],
                    $post
                );
                
                $urls[] = url('/' . $post['page_name']);
            }
        });

        if (!empty($urls)) {
            foreach (array_chunk($urls, 100) as $batch) {
                \App\Jobs\SubmitIndexNowJob::dispatch($batch);
            }
        }
    }
}
