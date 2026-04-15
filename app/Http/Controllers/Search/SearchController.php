<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tools\Tool;
use App\Support\Listing\ListingSeoFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(Request $request, ListingSeoFactory $listingSeoFactory): Response
    {
        $q = trim((string) $request->query('q', ''));

        $posts = Post::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->published()
            ->when($q !== '', function ($query) use ($q): void {
                $query->where(function ($nested) use ($q): void {
                    $nested
                        ->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%")
                        ->orWhere('content_html', 'like', "%{$q}%")
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', "%{$q}%"))
                        ->orWhereHas('tags', fn ($tagQuery) => $tagQuery->where('name', 'like', "%{$q}%"));
                });
            })
            ->latest('published_at')
            ->paginate(12)
            ->through(fn (Post $post) => [
                'id' => $post->id,
                'type' => 'post',
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'url' => route('posts.show', $post),
                'category' => $post->category?->name,
                'category_url' => $post->category ? route('posts.category', $post->category) : null,
                'tags' => $post->tags->map(fn ($tag) => [
                    'name' => $tag->name,
                    'url' => route('posts.tag', $tag),
                ])->all(),
            ])
            ->withQueryString();

        $tools = collect();

        if (Route::has('tools.show')) {
            $tools = Tool::query()
                ->published()
                ->when($q !== '', function ($query) use ($q): void {
                    $query->where(function ($nested) use ($q): void {
                        $nested
                            ->where('title', 'like', "%{$q}%")
                            ->orWhere('excerpt', 'like', "%{$q}%");
                    });
                })
                ->latest('published_at')
                ->limit(5)
                ->get(['id', 'title', 'slug', 'excerpt'])
                ->map(fn (Tool $tool) => [
                    'type' => 'tool',
                    'title' => $tool->title,
                    'excerpt' => $tool->excerpt,
                    'url' => route('tools.show', $tool),
                ])
                ->values();
        }

        $seo = $listingSeoFactory->build(
            title: 'Buscar',
            description: 'Busca interna para conteúdos e ferramentas do projeto.',
            canonical: route('search.index', ['q' => $q]),
            page: 1,
            breadcrumb: [
                ['name' => 'Início', 'url' => route('index.home')],
                ['name' => 'Buscar', 'url' => route('search.index')],
            ],
        );

        return Inertia::render('Pages/search/index', [
            'seo' => [
                ...$seo,
                'noindex' => true,
            ],
            'pageType' => 'search',
            'query' => $q,
            'results' => $posts,
            'tools' => $tools,
        ]);
    }
}
