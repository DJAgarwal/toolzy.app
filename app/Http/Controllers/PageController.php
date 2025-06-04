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
        \Log::info('Page hit', [
            'ip' => substr(request()->ip(), 0, 7) . '...', // partial IP for privacy
            'user_agent' => request()->userAgent(),
            'path' => request()->path(),
            'referer' => request()->header('referer'),
            'timestamp' => now()->toDateTimeString(),
        ]);
        $viewName = $viewFolder . '.' . $page_name;
        if (!View::exists($viewName)) {
            abort(404);
        }
        return view($viewName, $data);
    }
}