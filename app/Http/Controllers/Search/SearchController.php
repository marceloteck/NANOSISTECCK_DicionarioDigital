<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tools\Tool;
use App\Support\Listing\ListingSeoFactory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(Request $request, ListingSeoFactory $listingSeoFactory): Response
    {
        $q = trim((string) $request->query('q', ''));

        $posts = Post::query()
            ->published()
            ->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"))
            ->limit(8)
            ->get(['id', 'title', 'slug', 'excerpt'])
            ->map(fn (Post $post) => [
                'type' => 'post',
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'url' => route('posts.show', $post),
            ]);

        $tools = Tool::query()
            ->published()
            ->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"))
            ->limit(8)
            ->get(['id', 'title', 'slug', 'excerpt'])
            ->map(fn (Tool $tool) => [
                'type' => 'tool',
                'title' => $tool->title,
                'excerpt' => $tool->excerpt,
                'url' => route('tools.show', $tool),
            ]);

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
            'results' => $posts->concat($tools)->values(),
        ]);
    }
}
