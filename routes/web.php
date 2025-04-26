<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController,PageController,SitemapController};

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', fn () => redirect()->route('home'));
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    return response()->view('static.robots')->header('Content-Type', 'text/plain');
})->name('robots');
Route::get('/tools/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9\-]+')->name('tools.show');
Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9\-]+')->name('page.show');