<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;
use App\Helpers\PageHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    private const TOOLS_PER_PAGE = 9;
    private const TOOLS_CACHE_TTL = 3600;

    public function index(Request $request)
    {
        $data = PageHelper::pageMetadataAndBreadcrumbs('home');
        $searchQuery = trim((string) $request->query('search', ''));
        $tools = $this->getPaginatedTools($request, $searchQuery);

        if ($request->ajax()) {
            return response(view('partials.tools_grid', ['tools' => $tools])->render())->header('Content-Type', 'text/html; charset=UTF-8');
        }

        return response()->view('static.home', array_merge($data, ['tools' => $tools]))->header('Content-Type', 'text/html; charset=UTF-8');
    }

    private function getPaginatedTools(Request $request, string $searchQuery): LengthAwarePaginator
    {
        $tools = Cache::remember(Tool::HOME_INDEX_CACHE_KEY, self::TOOLS_CACHE_TTL, function () {
            return Tool::query()
                ->select(['id', 'page_name', 'meta_title', 'meta_description'])
                ->orderBy('id')
                ->get();
        });

        if ($searchQuery !== '') {
            $needle = Str::lower($searchQuery);
            $tools = $tools->filter(function (Tool $tool) use ($needle) {
                return Str::contains(Str::lower($tool->meta_title ?? ''), $needle)
                    || Str::contains(Str::lower($tool->meta_description ?? ''), $needle);
            })->values();
        }

        $page = LengthAwarePaginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            $tools->forPage($page, self::TOOLS_PER_PAGE)->values(),
            $tools->count(),
            self::TOOLS_PER_PAGE,
            $page,
            [
                'path' => $request->url(),
                'query' => $searchQuery !== '' ? ['search' => $searchQuery] : [],
            ]
        );
    }
}
