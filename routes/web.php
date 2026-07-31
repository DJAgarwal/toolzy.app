<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController,PageController,SitemapController,ToolsController,SqlOptimizerController,BlogController,InstagramDownloaderController};

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', fn () => redirect()->route('home'));
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap', fn () => redirect()->route('sitemap'));
Route::get('/robots.txt', function () {
    return response()->view('static.robots')->header('Content-Type', 'text/plain');
})->name('robots');
Route::get('/robots', fn () => redirect()->route('robots'));
Route::get('/tools', [ToolsController::class, 'index'])->name('tools.index');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->where('slug', '[a-z0-9\-]+')->name('blog.show');
Route::post('/api/sql-optimizer/analyze', [SqlOptimizerController::class, 'analyze'])->name('sql-optimizer.analyze');
Route::middleware(['throttle:30,1'])->group(function () {
    Route::post('/api/instagram-downloader/process', [InstagramDownloaderController::class, 'process'])->name('instagram-downloader.process');
    Route::get('/api/instagram-downloader/download', [InstagramDownloaderController::class, 'download'])->name('instagram-downloader.download');
});
Route::get('/tools/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9\-]+')->name('tools.show');
Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9\-]+')->name('page.show');