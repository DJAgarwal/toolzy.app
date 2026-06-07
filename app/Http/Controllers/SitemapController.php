<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{StaticPage,Tool};

class SitemapController extends Controller
{
    public function index()
    {
        $xml = \Cache::remember('sitemap_xml', 86400, function () {
            $pages = StaticPage::select('page_name', 'updated_at')->get();
            $tools = Tool::select('page_name', 'updated_at')->get();

            return view('static.sitemap', compact('pages', 'tools'))->render();
        });

        return response($xml)->header('Content-Type', 'application/xml');
    }
}