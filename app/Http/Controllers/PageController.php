<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\PageHelper;

class PageController extends Controller
{
    public function show($slug = 'home')
    {
        $slug = strtolower(trim($slug, '/'));
        $page_name = $slug === '' ? 'home' : $slug;

        if (!view()->exists('static.'.$page_name)) {
            abort(404);
        }

        $data = PageHelper::pageMetadataAndBreadcrumbs($slug);

        return view('static.'.$page_name, $data);
    }  
}
