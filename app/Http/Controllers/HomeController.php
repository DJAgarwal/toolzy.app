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
\Log::info('Page hit', [
            'ip' => substr(request()->ip(), 0, 7) . '...', // partial IP for privacy
            'user_agent' => request()->userAgent(),
            'path' => request()->path(),
            'referer' => request()->header('referer'),
            'timestamp' => now()->toDateTimeString(),
        ]);
        return view('static.home', array_merge($data, ['tools' => $tools]));
    }
}