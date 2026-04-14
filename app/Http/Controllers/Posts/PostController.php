<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostStoreRequest;
use App\Http\Requests\Posts\PostUpdateRequest;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Support\Posts\PostContentFormatter;
use App\Support\Posts\PostCreator;
use App\Support\Posts\PostTitleTemplate;
use App\Support\Monetization\MonetizationPolicy;
use App\Support\Related\RelatedContentResolver;
use App\Support\Seo\SeoBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function __construct(
        protected PostContentFormatter $contentFormatter,
        protected PostTitleTemplate $titleTemplate,
        protected PostCreator $postCreator,
        protected RelatedContentResolver $relatedContentResolver,
    ) {
    }

    public function index(Request $request, SeoBuilder $seoBuilder): Response
    {
        $page = max(1, (int) $request->integer('page', 1));

        $posts = Post::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->published()
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $seo = $seoBuilder->buildCategory([
            'title' => 'Posts',
            'description' => 'Conteúdos organizados para intenção de busca, escaneabilidade e crescimento orgânico.',
            'canonical' => route('posts.index'),
            'noindex' => $page > 1,
            'breadcrumb' => [
                ['name' => 'Início', 'url' => route('index.home')],
                ['name' => 'Posts', 'url' => route('posts.index')],
            ],
            'tags' => ['posts', 'conteúdo', 'seo'],
        ]);

        return Inertia::render('Pages/posts/index', [
            'seo' => $seo,
            'posts' => $posts,
            'pageType' => 'listing',
        ]);
    }

    public function povPreview(): Response
    {
        return Inertia::render('Pages/posts/pov', [
            'pageType' => 'post',
        ]);
    }

    public function store(PostStoreRequest $request): RedirectResponse
    {
        $post = $this->postCreator->create($request->validated(), $request->user()?->id);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post criado com sucesso.');
    }

    public function update(PostUpdateRequest $request, Post $post): RedirectResponse
    {
        $updatedPost = $this->postCreator->update($post, $request->validated(), $request->user()?->id);

        return redirect()->route('posts.show', $updatedPost)
            ->with('success', 'Post atualizado com sucesso.');
    }

    public function show(Post $post, SeoBuilder $seoBuilder, MonetizationPolicy $monetizationPolicy): Response
    {
        abort_unless($post->is_published && $post->status === 'published' && $post->published_at?->lte(now()), 404);

        $post->loadMissing(['category:id,name,slug', 'tags:id,name,slug']);

        $formatted = $this->contentFormatter->prepare($post->content_html);
        $faq = $this->contentFormatter->normalizeFaq($post->faq_json);

        $relatedPosts = $this->relatedContentResolver->relatedForPost($post);
        $topSearches = collect($post->related_keywords ?? [])
            ->map(fn ($keyword) => trim((string) $keyword))
            ->filter()
            ->values()
            ->take(6)
            ->all();

        $post->reading_time = $post->reading_time ?: $formatted['reading_time'];
        $postArray = $post->toArray();
        $categoryName = (string) ($postArray['category']['name'] ?? 'Sem categoria');
        $categorySlug = (string) ($postArray['category']['slug'] ?? '');
        $postUrl = route('posts.show', $post);

        $breadcrumbs = [
            ['name' => 'Início', 'url' => route('index.home')],
            ['name' => 'Posts', 'url' => route('posts.index')],
        ];

        if ($categorySlug !== '' && $categoryName !== '') {
            $breadcrumbs[] = ['name' => $categoryName, 'url' => route('posts.category', $categorySlug)];
        }

        $breadcrumbs[] = ['name' => (string) $post->title, 'url' => $postUrl];

        $sidebarCards = [
            [
                'label' => 'Resumo do termo',
                'title' => (string) $post->title,
                'description' => (string) ($post->excerpt ?: 'Conteúdo completo com contexto, exemplos práticos e respostas diretas.'),
            ],
            [
                'label' => 'Leitura estimada',
                'title' => "{$post->reading_time} min de leitura",
                'description' => 'Tempo médio calculado automaticamente com base no conteúdo sanitizado do artigo.',
            ],
            [
                'label' => 'Categoria',
                'title' => $categoryName,
                'description' => $categorySlug !== '' ? route('posts.category', $categorySlug) : route('posts.index'),
            ],
            [
                'label' => 'Publicação',
                'title' => optional($post->published_at)->format('d/m/Y') ?: 'Em atualização',
                'description' => 'Estrutura otimizada para SEO técnico, escaneabilidade e navegação interna.',
            ],
        ];

        $cta = [
            'stats' => [
                ['title' => 'Resposta imediata', 'description' => 'Significado, contexto e aplicação entregues de forma direta.'],
                ['title' => 'Navegação interna', 'description' => 'Relacionados, categoria e tag para ampliar retenção.'],
                ['title' => 'Escalável', 'description' => 'Contrato padronizado para publicar novos posts sem retrabalho.'],
            ],
        ];

        $seo = $seoBuilder->buildPost([
            ...$postArray,
            'content_html' => $formatted['content_html'],
            'faq_json' => $faq,
            'title_suggestions' => $this->titleTemplate->suggest($post->title, $post->search_intent),
        ]);

        return Inertia::render('Pages/posts/pov', [
            'seo' => $seo,
            'post' => [
                ...$postArray,
                'content_html' => $formatted['content_html'],
                'toc' => $formatted['toc'],
                'faq' => $faq,
                'title_suggestions' => $this->titleTemplate->suggest($post->title, $post->search_intent),
            ],
            'breadcrumbs' => $breadcrumbs,
            'relatedPosts' => $relatedPosts,
            'topSearches' => $topSearches,
            'sidebar' => [
                'cards' => $sidebarCards,
                'categories' => PostCategory::query()->where('is_indexable', true)->orderBy('name')->limit(8)->get(['name', 'slug']),
                'internalLinks' => collect($relatedPosts)->map(fn ($item) => [
                    'label' => $item['title'] ?? '',
                    'href' => isset($item['slug']) ? route('posts.show', $item['slug']) : ($item['url'] ?? '#'),
                ])->values()->all(),
            ],
            'cta' => $cta,
            'pageType' => 'post',
            'adSlots' => $monetizationPolicy->slotsFor('post'),
            'showAdSlots' => $monetizationPolicy->shouldShowSlots('post', (bool) $post->is_indexable),
        ]);
    }

    public function category(Request $request, PostCategory $category, SeoBuilder $seoBuilder): Response
    {
        $page = max(1, (int) $request->integer('page', 1));
        $posts = $category->posts()->published()->latest('published_at')->paginate(12)->withQueryString();

        $seo = $seoBuilder->buildCategory([
            'title' => $category->seo_title ?: $category->name,
            'description' => $category->meta_description ?: "Conteúdos da categoria {$category->name}.",
            'canonical' => $category->canonical_url ?: route('posts.category', $category),
            'is_indexable' => $category->is_indexable,
            'noindex' => $page > 1,
            'breadcrumb' => [
                ['name' => 'Início', 'url' => route('index.home')],
                ['name' => 'Posts', 'url' => route('posts.index')],
                ['name' => $category->name, 'url' => route('posts.category', $category)],
            ],
        ]);

        return Inertia::render('Pages/posts/taxonomy', [
            'seo' => $seo,
            'taxonomy' => ['type' => 'category', 'name' => $category->name],
            'posts' => $posts,
            'pageType' => 'listing',
        ]);
    }

    public function tag(Request $request, PostTag $tag, SeoBuilder $seoBuilder): Response
    {
        $page = max(1, (int) $request->integer('page', 1));
        $posts = $tag->posts()->published()->latest('published_at')->paginate(12)->withQueryString();

        $seo = $seoBuilder->buildCategory([
            'title' => $tag->seo_title ?: $tag->name,
            'description' => $tag->meta_description ?: "Posts relacionados à tag {$tag->name}.",
            'canonical' => $tag->canonical_url ?: route('posts.tag', $tag),
            'is_indexable' => $tag->is_indexable,
            'noindex' => $page > 1,
            'breadcrumb' => [
                ['name' => 'Início', 'url' => route('index.home')],
                ['name' => 'Posts', 'url' => route('posts.index')],
                ['name' => $tag->name, 'url' => route('posts.tag', $tag)],
            ],
        ]);

        return Inertia::render('Pages/posts/taxonomy', [
            'seo' => $seo,
            'taxonomy' => ['type' => 'tag', 'name' => $tag->name],
            'posts' => $posts,
            'pageType' => 'listing',
        ]);
    }
}
