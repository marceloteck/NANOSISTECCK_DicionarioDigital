<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tools\Tool;
use App\Support\Listing\ListingSeoFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    /**
     * Termos fracos que não ajudam na relevância.
     *
     * @var array<int, string>
     */
    private array $stopWords = [
        'a', 'o', 'as', 'os',
        'um', 'uma', 'uns', 'umas',
        'de', 'da', 'do', 'das', 'dos',
        'e', 'é', 'em', 'no', 'na', 'nos', 'nas',
        'para', 'por', 'com', 'sem',
        'ao', 'aos',
        'que', 'como', 'sobre',
        'se', 'sua', 'seu', 'suas', 'seus',
        'ou', 'mais', 'menos',
    ];

    public function index(Request $request, ListingSeoFactory $listingSeoFactory): Response
    {
        $q = trim((string) $request->query('q', ''));
        $normalizedQuery = $this->normalizeSearchTerm($q);
        $terms = $this->extractRelevantTerms($normalizedQuery);

        $postsQuery = Post::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->published();

        $this->applyPostSearch($postsQuery, $q, $normalizedQuery, $terms);

        $posts = $postsQuery
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
            $toolsQuery = Tool::query()->published();

            $this->applyToolSearch($toolsQuery, $q, $normalizedQuery, $terms);

            $tools = $toolsQuery
                ->limit(5)
                ->get(['id', 'title', 'slug', 'excerpt', 'published_at'])
                ->map(fn (Tool $tool) => [
                    'type' => 'tool',
                    'title' => $tool->title,
                    'excerpt' => $tool->excerpt,
                    'url' => route('tools.show', $tool),
                ])
                ->values();
        }

        $seo = $listingSeoFactory->build(
            title: $q !== '' ? "Buscar por: {$q}" : 'Buscar',
            description: $q !== ''
                ? "Resultados da busca interna para: {$q}."
                : 'Busca interna para conteúdos e ferramentas do projeto.',
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
            'normalizedQuery' => $normalizedQuery,
            'searchTerms' => $terms->values()->all(),
            'results' => $posts,
            'tools' => $tools,
        ]);
    }

    private function normalizeSearchTerm(string $value): string
    {
        $value = mb_strtolower($value);
        $value = preg_replace('/[[:punct:]]+/u', ' ', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value);
    }

    /**
     * @return Collection<int, string>
     */
    private function extractRelevantTerms(string $normalizedQuery): Collection
    {
        if ($normalizedQuery === '') {
            return collect();
        }

        return collect(explode(' ', $normalizedQuery))
            ->map(fn ($term) => trim($term))
            ->filter()
            ->reject(fn ($term) => mb_strlen($term) < 2)
            ->reject(fn ($term) => in_array($term, $this->stopWords, true))
            ->unique()
            ->values();
    }

    private function applyPostSearch(
        Builder $query,
        string $originalQuery,
        string $normalizedQuery,
        Collection $terms
    ): void {
        if ($originalQuery === '') {
            $query->latest('published_at');
            return;
        }

        $likeFullOriginal = '%' . $originalQuery . '%';
        $likeFullNormalized = '%' . $normalizedQuery . '%';

        $query->where(function (Builder $mainQuery) use ($likeFullOriginal, $likeFullNormalized, $terms): void {
            $mainQuery
                ->where('title', 'like', $likeFullOriginal)
                ->orWhere('title', 'like', $likeFullNormalized)
                ->orWhere('excerpt', 'like', $likeFullOriginal)
                ->orWhere('excerpt', 'like', $likeFullNormalized)
                ->orWhere('content_html', 'like', $likeFullOriginal)
                ->orWhere('content_html', 'like', $likeFullNormalized)
                ->orWhereHas('category', function (Builder $categoryQuery) use ($likeFullOriginal, $likeFullNormalized): void {
                    $categoryQuery
                        ->where('name', 'like', $likeFullOriginal)
                        ->orWhere('name', 'like', $likeFullNormalized);
                })
                ->orWhereHas('tags', function (Builder $tagQuery) use ($likeFullOriginal, $likeFullNormalized): void {
                    $tagQuery
                        ->where('name', 'like', $likeFullOriginal)
                        ->orWhere('name', 'like', $likeFullNormalized);
                });

            foreach ($terms as $term) {
                $likeTerm = '%' . $term . '%';

                $mainQuery->orWhere(function (Builder $termQuery) use ($likeTerm): void {
                    $termQuery
                        ->where('title', 'like', $likeTerm)
                        ->orWhere('excerpt', 'like', $likeTerm)
                        ->orWhere('content_html', 'like', $likeTerm)
                        ->orWhereHas('category', fn (Builder $categoryQuery) => $categoryQuery->where('name', 'like', $likeTerm))
                        ->orWhereHas('tags', fn (Builder $tagQuery) => $tagQuery->where('name', 'like', $likeTerm));
                });
            }
        });

        $scoreParts = [];
        $bindings = [];

        $scoreParts[] = "CASE WHEN LOWER(title) LIKE ? THEN 120 ELSE 0 END";
        $bindings[] = $likeFullNormalized;

        $scoreParts[] = "CASE WHEN LOWER(excerpt) LIKE ? THEN 80 ELSE 0 END";
        $bindings[] = $likeFullNormalized;

        $scoreParts[] = "CASE WHEN LOWER(content_html) LIKE ? THEN 35 ELSE 0 END";
        $bindings[] = $likeFullNormalized;

        foreach ($terms as $term) {
            $likeTerm = '%' . $term . '%';

            $scoreParts[] = "CASE WHEN LOWER(title) LIKE ? THEN 40 ELSE 0 END";
            $bindings[] = $likeTerm;

            $scoreParts[] = "CASE WHEN LOWER(excerpt) LIKE ? THEN 25 ELSE 0 END";
            $bindings[] = $likeTerm;

            $scoreParts[] = "CASE WHEN LOWER(content_html) LIKE ? THEN 10 ELSE 0 END";
            $bindings[] = $likeTerm;
        }

        $scoreSql = implode(' + ', $scoreParts);

        $query
            ->select('posts.*')
            ->selectRaw("({$scoreSql}) as relevance_score", $bindings);

        if ($terms->isNotEmpty()) {
            $query->withExists([
                'category as category_match' => function (Builder $categoryQuery) use ($terms): void {
                    $categoryQuery->where(function (Builder $nested) use ($terms): void {
                        foreach ($terms as $term) {
                            $nested->orWhere('name', 'like', '%' . $term . '%');
                        }
                    });
                },
                'tags as tags_match' => function (Builder $tagQuery) use ($terms): void {
                    $tagQuery->where(function (Builder $nested) use ($terms): void {
                        foreach ($terms as $term) {
                            $nested->orWhere('name', 'like', '%' . $term . '%');
                        }
                    });
                },
            ]);

            $query
                ->orderByDesc('category_match')
                ->orderByDesc('tags_match');
        }

        $query
            ->orderByDesc('relevance_score')
            ->orderByDesc('published_at');
    }

    private function applyToolSearch(
        Builder $query,
        string $originalQuery,
        string $normalizedQuery,
        Collection $terms
    ): void {
        if ($originalQuery === '') {
            $query->latest('published_at');
            return;
        }

        $likeFullOriginal = '%' . $originalQuery . '%';
        $likeFullNormalized = '%' . $normalizedQuery . '%';

        $query->where(function (Builder $mainQuery) use ($likeFullOriginal, $likeFullNormalized, $terms): void {
            $mainQuery
                ->where('title', 'like', $likeFullOriginal)
                ->orWhere('title', 'like', $likeFullNormalized)
                ->orWhere('excerpt', 'like', $likeFullOriginal)
                ->orWhere('excerpt', 'like', $likeFullNormalized);

            foreach ($terms as $term) {
                $likeTerm = '%' . $term . '%';

                $mainQuery->orWhere(function (Builder $termQuery) use ($likeTerm): void {
                    $termQuery
                        ->where('title', 'like', $likeTerm)
                        ->orWhere('excerpt', 'like', $likeTerm);
                });
            }
        });

        $scoreParts = [];
        $bindings = [];

        $scoreParts[] = "CASE WHEN LOWER(title) LIKE ? THEN 100 ELSE 0 END";
        $bindings[] = $likeFullNormalized;

        $scoreParts[] = "CASE WHEN LOWER(excerpt) LIKE ? THEN 60 ELSE 0 END";
        $bindings[] = $likeFullNormalized;

        foreach ($terms as $term) {
            $likeTerm = '%' . $term . '%';

            $scoreParts[] = "CASE WHEN LOWER(title) LIKE ? THEN 30 ELSE 0 END";
            $bindings[] = $likeTerm;

            $scoreParts[] = "CASE WHEN LOWER(excerpt) LIKE ? THEN 18 ELSE 0 END";
            $bindings[] = $likeTerm;
        }

        $scoreSql = implode(' + ', $scoreParts);

        $query
            ->select('tools.*')
            ->selectRaw("({$scoreSql}) as relevance_score", $bindings)
            ->orderByDesc('relevance_score')
            ->orderByDesc('published_at');
    }
}