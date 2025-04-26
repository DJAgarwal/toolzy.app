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
        return view('static.home', $data);
    }
}
