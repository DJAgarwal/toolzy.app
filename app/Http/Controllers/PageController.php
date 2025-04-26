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
        $data = PageHelper::pageMetadataAndBreadcrumbs($slug);
        return view($data['page_type'].'.'.$page_name, $data);
    }  
}
