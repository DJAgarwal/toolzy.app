<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{PageMetadata};
use App\Helpers\PageHelper;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $data = PageHelper::pageMetadataAndBreadcrumbs('home');
        $searchQuery = $request->query('search');
        if ($searchQuery) {
            $tools = PageMetadata::where('page_type', 'tools')
                ->where(function ($query) use ($searchQuery) {
                    $query->where('meta_title', 'LIKE', "%{$searchQuery}%")
                        ->orWhere('meta_description', 'LIKE', "%{$searchQuery}%");
                })
                ->paginate(10);
        } else {
            $tools = PageMetadata::where('page_type', 'tools')->paginate(10);
        }
        return view('static.home', array_merge($data, ['tools' => $tools]));
    }
}
