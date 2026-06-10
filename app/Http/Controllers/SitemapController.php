<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{StaticPage,Tool};

class SitemapController extends Controller
{
    public function index()
    {
        $pages = StaticPage::select('page_name', 'updated_at')->get();
        $tools = Tool::select('page_name', 'updated_at')->get();

        $xml = view('static.sitemap', compact('pages', 'tools'))->render();

        return response($xml)->header('Content-Type', 'application/xml');
    }
}