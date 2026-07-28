<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\PageHelper;
use Illuminate\Support\Facades\View;

class BlogController extends Controller
{
    /**
     * Display the main blog listing page.
     */
    public function index()
    {
        $data = PageHelper::pageMetadataAndBreadcrumbs('blog');

        return response()->view('static.blog', $data)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Display a specific blog post.
     */
    public function show($slug)
    {
        $slug = strtolower(trim($slug, '/'));
        $pageName = 'blog/' . $slug;
        $viewName = 'blog.' . $slug;

        if (!View::exists($viewName)) {
            abort(404);
        }

        $data = PageHelper::pageMetadataAndBreadcrumbs($pageName);

        return response()->view($viewName, $data)->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
