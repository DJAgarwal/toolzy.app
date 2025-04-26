<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Category};
use App\Helpers\PageHelper;

class HomeController extends Controller
{
    public function index()
    {
        $data = PageHelper::pageMetadataAndBreadcrumbs('home');
        $categories = Category::all();

        return view('static.home', array_merge($data, [
            'categories' => $categories
        ]));
    }
}
