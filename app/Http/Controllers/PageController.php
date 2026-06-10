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

        if (!$data) {
            abort(404);
        }

        $viewFolder = $data['page_type'] === 'tools' ? 'tools' : 'static';
        if ($viewFolder === 'tools') {
            $data['tool'] = Lang::get('tools.' . $page_name);
            $data['isToolPage'] = true;
        }

        $viewName = $viewFolder . '.' . $page_name;
        if (!View::exists($viewName)) {
            abort(404);
        }
        return response()->view($viewName, $data)->header('Content-Type', 'text/html; charset=UTF-8');
    }
}