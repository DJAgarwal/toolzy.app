<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;
use App\Helpers\PageHelper;

class ToolsController extends Controller
{
    public function index()
    {
        $data = PageHelper::pageMetadataAndBreadcrumbs('tools');
        $tools = Tool::all();
        return view('static.tools', array_merge($data, ['tools' => $tools]));
    }
}