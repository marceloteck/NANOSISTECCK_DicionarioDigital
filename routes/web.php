<?php

use App\Http\Controllers\Institutional\InstitutionalPageController;
use App\Http\Controllers\Pages\index\HomeContollerRoutes;
use App\Http\Controllers\Posts\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Search\SearchController;
use App\Http\Controllers\Seo\SeoController;
use App\Http\Controllers\Tools\ToolController;
use App\Http\Controllers\sendEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');

Route::get('/', [HomeContollerRoutes::class, 'index'])->name('index.home');

if ((bool) config('project.modules.posts', true)) {
    Route::redirect('/post/o-que-significa-pov', '/posts/o-que-significa-pov', 301)->name('posts.pov');
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/posts', [PostController::class, 'store'])->middleware('auth')->name('posts.store');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->middleware('auth')->name('posts.update');
}

if ((bool) config('project.modules.taxonomy', true)) {
    Route::get('/categoria/{category:slug}', [PostController::class, 'category'])->name('posts.category');
    Route::get('/tag/{tag:slug}', [PostController::class, 'tag'])->name('posts.tag');
}

if ((bool) config('project.modules.tools', false)) {
    Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
    Route::get('/tools/{tool:slug}', [ToolController::class, 'show'])->name('tools.show');
}

if ((bool) config('project.modules.institutional_pages', true)) {
    Route::get('/institucional/{slug}', [InstitutionalPageController::class, 'show'])->name('institutional.show');
}

if ((bool) config('project.modules.search_page', true)) {
    Route::get('/buscar', [SearchController::class, 'index'])->name('search.index');
}

Route::post('/contact/send', [sendEmailController::class, 'Send'])->name('contact.send');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
