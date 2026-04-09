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
            'title' => 'Dicionário Digital | Significados, gírias e termos da internet',
            'description' => 'Consulte significados de gírias, abreviações, memes, emojis e termos online com linguagem clara, exemplos e conteúdo atualizado.',
            'path' => '/',
            'tags' => ['dicionário digital', 'significado de gírias', 'termos da internet', 'abreviações online', 'emojis'],
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
            ['name' => 'Gírias da internet', 'description' => 'Entenda expressões populares usadas em memes, comentários e redes sociais.', 'url' => route('posts.index')],
            ['name' => 'Abreviações online', 'description' => 'Descubra o que significam siglas como POV, FYP, TBH, IMO e muitas outras.', 'url' => route('posts.index')],
            ['name' => 'Emojis e símbolos', 'description' => 'Veja interpretações por contexto para usar emojis da forma certa.', 'url' => route('posts.index')],
            ['name' => 'Termos de tecnologia', 'description' => 'Aprenda conceitos digitais com explicações simples e práticas.', 'url' => route('posts.index')],
            ['name' => 'Tendências e cultura web', 'description' => 'Fique por dentro de palavras novas que viralizam na internet.', 'url' => route('posts.index')],
        ];

        $faq = [
            ['question' => 'Como encontro o significado de um termo rapidamente?', 'answer' => 'Use a busca no topo da página e digite a palavra exata. Você verá resultados com definição curta e explicação completa.'],
            ['question' => 'Os termos são atualizados com frequência?', 'answer' => 'Sim. Novas expressões e gírias são adicionadas conforme surgem nas redes e no uso cotidiano da internet.'],
            ['question' => 'Posso confiar nas explicações para usar no dia a dia?', 'answer' => 'Sim. O conteúdo é escrito em linguagem clara, com contexto real, para ajudar você a entender e usar cada termo com segurança.'],
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
