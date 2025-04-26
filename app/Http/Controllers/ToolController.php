<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function show($slug)
    {
        $tools = [
            'pdf-to-word' => 'PDF to Word Converter',
            'image-compressor' => 'Image Compressor',
            'word-counter' => 'Word Counter',
            'qr-code-generator' => 'QR Code Generator',
            'text-case-converter' => 'Text Case Converter',
            'json-formatter' => 'JSON Formatter',
        ];

        if (!array_key_exists($slug, $tools)) {
            abort(404);
        }

        $title = $tools[$slug];

        $meta = [
            'title' => "$title | Toolzy",
            'description' => "Use our $title online. 100% free, unlimited usage, and no signup required.",
            'keywords' => "$slug, $title, toolzy, free tools",
        ];

        return view('tools.' . $slug, compact('title', 'meta'));
    }

}