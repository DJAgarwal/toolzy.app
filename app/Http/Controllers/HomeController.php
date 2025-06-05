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
$botKeywords = [
            'bot', 'crawl', 'spider', 'slurp', 'facebook', 'meta-externalagent',
            'headless', 'okhttp', 'go-http-client', 'secscan', 'python-requests',
            'wget', 'curl', 'node-fetch'
        ];
        $userAgent = request()->userAgent();
        $isBot = false;
        foreach ($botKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                $isBot = true;
                break;
            }
        }
        $type = $isBot ? 'bot' : 'human';

        \Log::info('Page hit', [
            'type' => $type,
            'userAgent' => $userAgent,
            'path' => request()->path(),
            'referer' => request()->header('referer'),
        ]);
        return view('static.home', array_merge($data, ['tools' => $tools]));
    }
}