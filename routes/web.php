<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $meta = [
        'title' => 'Toolzy - Free Online Tools for Everyone',
        'description' => 'Toolzy.app gives you instant access to free tools like PDF to Word, image compressor, word counter and more — no limits.',
        'keywords' => 'online tools, pdf to word, image compression, QR code generator, word counter, free tools'
    ];
    return view('home', compact('meta'));
});