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

        return view('static.home', array_merge($data, ['tools' => $tools]));
    }
}