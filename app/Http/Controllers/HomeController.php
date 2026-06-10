<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;
use App\Helpers\PageHelper;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $data = PageHelper::pageMetadataAndBreadcrumbs('home');
        $searchQuery = $request->query('search');

        if ($searchQuery) {
            $tools = Tool::where('meta_title', 'LIKE', "%{$searchQuery}%")
                ->orWhere('meta_description', 'LIKE', "%{$searchQuery}%")
                ->paginate(9);
        } else {
            $tools = Tool::paginate(9);
        }
        if ($request->ajax()) {
            return response(view('partials.tools_grid', ['tools' => $tools])->render())->header('Content-Type', 'text/html; charset=UTF-8');
        }

        return response()->view('static.home', array_merge($data, ['tools' => $tools]))->header('Content-Type', 'text/html; charset=UTF-8');
    }
}