<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{StaticPage,Tool};

class SitemapController extends Controller
{
    public function index()
    {
        $pages = StaticPage::all();
        $tools = Tool::all();

        return response()->view('static.sitemap', compact('pages', 'tools'))
            ->header('Content-Type', 'application/xml');
    }
}