<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageMetadata;

class SitemapController extends Controller
{
    public function index()
    {
        $pages = PageMetadata::all();
        return response()->view('sitemap', compact('pages'))
        ->header('Content-Type', 'application/xml');
    }

    public function notifySearchEngines()
    {
        $sitemapUrl = urlencode(url('/sitemap.xml'));
        @file_get_contents("https://www.google.com/ping?sitemap=$sitemapUrl");
        @file_get_contents("https://www.bing.com/ping?sitemap=$sitemapUrl");
    }
}