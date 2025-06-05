<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\PageHelper;
use Lang;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
    public function show($slug = 'home')
    {
        $slug = strtolower(trim($slug, '/'));
        $page_name = $slug === '' ? 'home' : $slug;
        $data = PageHelper::pageMetadataAndBreadcrumbs($slug);

        $viewFolder = $data['page_type'] === 'tools' ? 'tools' : 'static';
        if ($viewFolder === 'tools') {
            $data['tool'] = Lang::get('tools.' . $page_name);
            $data['isToolPage'] = true;
        }
        $botKeywords = [
            'bot', 'crawl', 'spider', 'slurp', 'facebook', 'meta-externalagent',
            'headless', 'okhttp', 'go-http-client', 'secscan', 'python-requests',
            'wget', 'curl', 'node-fetch'
        ];
        $userAgent = request()->userAgent();
        $isBot = false;
        foreach ($botKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                $isBot = true;
                break;
            }
        }
        $type = $isBot ? 'bot' : 'human';

        \Log::info('Page hit', [
            'type' => $type,
            'userAgent' => $userAgent,
            'path' => request()->path(),
            'referer' => request()->header('referer'),
        ]);
        $viewName = $viewFolder . '.' . $page_name;
        if (!View::exists($viewName)) {
            abort(404);
        }
        return view($viewName, $data);
    }
}