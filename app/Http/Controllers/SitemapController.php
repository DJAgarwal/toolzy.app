<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JsonldSchema;
use App\Models\PageMetadata;

class SitemapController extends Controller
{
    public function index()
    {
        $products = [];
        // $products = Product::all(); // You can add other models too
        $pages = PageMetadata::all();
        $content = view('sitemap', compact('products', 'pages'));
        return response()->view('sitemap', compact('products'))
        ->header('Content-Type', 'application/xml');
    }

    public function notifySearchEngines()
    {
        $sitemapUrl = urlencode(url('/sitemap.xml'));
        @file_get_contents("https://www.google.com/ping?sitemap=$sitemapUrl");
        @file_get_contents("https://www.bing.com/ping?sitemap=$sitemapUrl");
    }
}
