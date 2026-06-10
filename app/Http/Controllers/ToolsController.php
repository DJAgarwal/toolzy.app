<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Helpers\PageHelper;
use App\Services\ToolService;

class ToolsController extends Controller
{
    protected ToolService $toolService;

    public function __construct(ToolService $toolService)
    {
        $this->toolService = $toolService;
    }

    public function index()
    {
        $data = PageHelper::pageMetadataAndBreadcrumbs('tools');
        $tools = Tool::all()->groupBy('category');
        return response()->view('static.tools', array_merge($data, ['tools' => $tools]))->header('Content-Type', 'text/html; charset=UTF-8');
    }
}