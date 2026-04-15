<?php

namespace App\Http\Controllers\Pages\index;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Support\Institutional\InstitutionalPageRegistry;
use App\Support\Monetization\MonetizationPolicy;
use App\Support\Project\ModuleManager;
use App\Support\Seo\SeoBuilder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeContollerRoutes extends Controller
{
    public function index(SeoBuilder $seoBuilder, ModuleManager $moduleManager, InstitutionalPageRegistry $institutionalPageRegistry, MonetizationPolicy $monetizationPolicy)
    {
        $latestPosts = Post::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->published()
            ->latest('published_at')
            ->limit(9)
            ->get();

        $featuredPosts = $latestPosts->take(3)->values();
        $postsForSection = $latestPosts->slice(3, 3)->values();

        $categories = PostCategory::query()
            ->withCount([
                'posts as posts_count' => fn ($query) => $query->published(),
            ])
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->limit(4)
            ->get();

        $topSearches = PostTag::query()
            ->withCount([
                'posts as posts_count' => fn ($query) => $query->published(),
            ])
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->limit(5)
            ->pluck('name')
            ->map(fn (string $name) => "o que significa {$name}")
            ->values()
            ->all();

        $seo = $seoBuilder->buildPage([
            'title' => config('project.name', 'Universal Site Engine'),
            'description' => 'Base mestre para múltiplos sites de conteúdo, ferramentas e híbridos com SEO centralizado e preparo para Adsense.',
            'path' => '/',
            'tags' => ['seo', 'laravel', 'vue', 'inertia', 'adsense', 'modular'],
            'breadcrumb' => [
                ['name' => 'Início', 'url' => route('index.home')],
            ],
            'is_indexable' => true,
        ]);

        return Inertia::render('Pages/index/Home', [
            'seo' => $seo,
            'pageType' => 'home',
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'enabledModules' => $moduleManager->enabledModules(),
            'institutionalPages' => $institutionalPageRegistry->enabledPages(),
            'showAdSlots' => $monetizationPolicy->shouldShowSlots('home'),
            'homeData' => [
                'searchSuggestions' => $topSearches,
                'searchPreviews' => $featuredPosts->map(fn (Post $post) => [
                    'label' => 'Resultado sugerido',
                    'title' => $post->title,
                    'description' => $post->excerpt,
                ])->all(),
                'categories' => $categories->map(fn (PostCategory $category) => [
                    'icon' => '#',
                    'title' => $category->name,
                    'description' => "Explore os conteúdos da categoria {$category->name}.",
                    'href' => route('posts.category', $category),
                ])->all(),
                'featured' => $featuredPosts->map(fn (Post $post) => [
                    'category' => $post->category?->name ?: 'Sem categoria',
                    'title' => $post->title,
                    'description' => $post->excerpt,
                    'action' => 'Ler artigo completo',
                    'href' => route('posts.show', $post),
                ])->all(),
                'posts' => $postsForSection->map(fn (Post $post) => [
                    'category' => $post->category?->name ?: 'Sem categoria',
                    'badge' => $post->published_at?->format('d/m/Y') ?: 'Publicado',
                    'title' => $post->title,
                    'description' => $post->excerpt,
                    'href' => route('posts.show', $post),
                ])->all(),
                'topSearches' => $topSearches,
            ],
        ]);
    }
}
