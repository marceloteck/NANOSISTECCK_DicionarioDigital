<?php

namespace App\Http\Controllers\Pages\index;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\Institutional\InstitutionalPageRegistry;
use App\Support\Monetization\MonetizationPolicy;
use App\Support\Project\ModuleManager;
use App\Support\Seo\SeoBuilder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class HomeContollerRoutes extends Controller
{
    public function index(SeoBuilder $seoBuilder, ModuleManager $moduleManager, InstitutionalPageRegistry $institutionalPageRegistry, MonetizationPolicy $monetizationPolicy)
    {
        $seo = $seoBuilder->buildPage([
            'title' => 'Dicionário Digital',
            'description' => 'Descubra o significado de gírias, abreviações, emojis e termos digitais com respostas rápidas, exemplos reais e SEO avançado.',
            'path' => '/',
            'tags' => ['dicionário digital', 'gírias', 'abreviações', 'emojis', 'termos da internet'],
            'breadcrumb' => [
                ['name' => 'Início', 'url' => route('index.home')],
            ],
            'is_indexable' => true,
        ]);

        $recentPosts = Schema::hasTable('posts')
            ? Post::query()
                ->published()
                ->with(['category:id,name,slug'])
                ->latest('published_at')
                ->limit(12)
                ->get(['id', 'title', 'slug', 'excerpt', 'category_id', 'published_at'])
            : collect();

        $featuredTerms = $recentPosts->take(4)->map(fn (Post $post) => [
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'url' => route('posts.show', $post),
        ])->values();

        $trendingTerms = $recentPosts->slice(4, 4)->map(fn (Post $post) => [
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'url' => route('posts.show', $post),
        ])->values();

        $recentTerms = $recentPosts->slice(8, 4)->map(fn (Post $post) => [
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'url' => route('posts.show', $post),
            'category' => $post->category?->name,
        ])->values();

        $categories = [
            ['name' => 'Gírias da Internet', 'description' => 'Termos populares de memes, chats e redes sociais.', 'url' => route('posts.index')],
            ['name' => 'Gírias do TikTok', 'description' => 'Vocabulário viral e expressões de vídeos curtos.', 'url' => route('posts.index')],
            ['name' => 'Abreviações', 'description' => 'Siglas comuns para conversas online rápidas.', 'url' => route('posts.index')],
            ['name' => 'Emojis e símbolos', 'description' => 'Interpretação correta de contexto e intenção.', 'url' => route('posts.index')],
            ['name' => 'Termos de tecnologia', 'description' => 'Conceitos técnicos em linguagem simples.', 'url' => route('posts.index')],
        ];

        $faq = [
            ['question' => 'Como os termos são escolhidos?', 'answer' => 'Usamos clusters de busca com foco em dúvidas reais da internet e potencial de tráfego orgânico.'],
            ['question' => 'O conteúdo é atualizado?', 'answer' => 'Sim. Novos termos e revisões são publicados continuamente para manter relevância.'],
            ['question' => 'O site tem foco em SEO e retenção?', 'answer' => 'Sim. Cada página é planejada para responder rápido, aprofundar e conectar conteúdos relacionados.'],
        ];

        return Inertia::render('Pages/index/index', [
            'seo' => $seo,
            'pageType' => 'home',
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'enabledModules' => $moduleManager->enabledModules(),
            'institutionalPages' => $institutionalPageRegistry->enabledPages(),
            'showAdSlots' => $monetizationPolicy->shouldShowSlots('home'),
            'featuredTerms' => $featuredTerms,
            'trendingTerms' => $trendingTerms,
            'recentTerms' => $recentTerms,
            'categories' => $categories,
            'faq' => $faq,
            'popularSearches' => ['POV', 'FYP', 'NPC', 'Ratio', 'IYKYK', 'AFK'],
        ]);
    }
}
