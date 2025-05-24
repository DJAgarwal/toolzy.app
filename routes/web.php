<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController,PageController,SitemapController,ToolsController};

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', fn () => redirect()->route('home'));
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap', fn () => redirect()->route('sitemap'));
Route::get('/robots.txt', function () {
    return response()->view('static.robots')->header('Content-Type', 'text/plain');
})->name('robots');
Route::get('/robots', fn () => redirect()->route('robots'));
Route::get('/tools', [ToolsController::class, 'index'])->name('tools.index');
Route::post('/tools/pdf-converter/convert', [ToolsController::class, 'pdfConverter'])->name('tools.pdf-converter.convert');
Route::post('/tools/image-compressor', [ToolsController::class, 'processImageCompressor'])->name('tools.image-compressor');

Route::get('/tools/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9\-]+')->name('tools.show');
Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9\-]+')->name('page.show');