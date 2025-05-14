<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\PageHelper;
use Lang;

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
        return view($viewFolder . '.' . $page_name, $data);
    }
}