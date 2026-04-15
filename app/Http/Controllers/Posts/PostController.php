<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Support\Monetization\MonetizationPolicy;
use App\Support\Posts\PostContentFormatter;
use App\Support\Posts\PostCreator;
use App\Support\Posts\PostInputNormalizer;
use App\Support\Posts\PostPayloadValidator;
use App\Support\Posts\PostTitleTemplate;
use App\Support\Posts\PostUpdater;
use App\Support\Related\RelatedContentResolver;
use App\Support\Seo\SeoBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function __construct(
        protected PostContentFormatter $contentFormatter,
        protected PostTitleTemplate $titleTemplate,
        protected PostCreator $postCreator,
        protected PostUpdater $postUpdater,
        protected PostInputNormalizer $inputNormalizer,
        protected PostPayloadValidator $payloadValidator,
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

    public function adminIndex(Request $request): Response
    {
        $perPage = max(10, min(100, (int) $request->integer('per_page', 20)));

        $posts = Post::query()
            ->select([
                'id',
                'title',
                'slug',
                'status',
                'is_published',
                'published_at',
                'updated_at',
                'category_id',
            ])
            ->with('category:id,name')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->through(fn (Post $post) => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'status' => $post->status,
                'is_published' => (bool) $post->is_published,
                'published_at' => optional($post->published_at)->toDateTimeString(),
                'updated_at' => optional($post->updated_at)->toDateTimeString(),
                'category' => $post->category?->name,
                'preview_url' => route('admin.posts.preview', $post),
            ])
            ->withQueryString();

        return Inertia::render('Pages/posts/admin/index', [
            'posts' => $posts,
        ]);
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        try {
            $post->delete();
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('admin.posts.index', $request->query())
                ->with('error', 'Não foi possível excluir o post. Tente novamente.');
        }

        return redirect()
            ->route('admin.posts.index', $request->query())
            ->with('success', 'Post excluído com sucesso.');
    }

    public function create(): Response
    {
        return Inertia::render('Pages/posts/admin/form', [
            'mode' => 'create',
            'post' => null,
            ...$this->editorLookups(),
        ]);
    }

    public function edit(Post $post): Response
    {
        $post->load(['tags:id,name', 'category:id,name']);

        return Inertia::render('Pages/posts/admin/form', [
            'mode' => 'edit',
            'post' => [
                ...$post->toArray(),
                'category_name' => $post->category?->name,
                'published_at' => optional($post->published_at)->format('Y-m-d\TH:i'),
                'tags' => $post->tags->map(fn ($tag) => ['id' => $tag->id, 'name' => $tag->name])->all(),
            ],
            ...$this->editorLookups(),
        ]);
    }

    public function importJson(Request $request): JsonResponse
    {
        $request->validate(['payload_json' => ['required', 'string']]);

        $rawPayload = (string) $request->input('payload_json');
        $parsed = $this->decodePostPayload($rawPayload);

        if (! is_array($parsed)) {
            return response()->json([
                'message' => 'JSON inválido. Verifique a estrutura enviada.',
                'diagnostics' => $this->diagnoseJsonPayload($rawPayload),
            ], 422);
        }

        $normalized = $this->inputNormalizer->normalize($parsed);

        try {
            $validated = $this->payloadValidator->validate($normalized, false);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'JSON válido, mas com erros de validação por campo.',
                'diagnostics' => $this->formatValidationDiagnostics($exception),
                'errors' => $exception->errors(),
            ], 422);
        }

        return response()->json([
            'message' => 'JSON importado com sucesso. Revise os dados antes de salvar.',
            'data' => $validated,
        ]);
    }

    private function decodePostPayload(string $rawPayload): ?array
    {
        $attempts = [];
        $trimmed = trim($rawPayload);

        $attempts[] = $trimmed;
        $attempts[] = $this->stripCodeFence($trimmed);
        $attempts[] = $this->decodeEscapedJsonString($trimmed);
        $attempts[] = $this->decodeEscapedJsonString($this->stripCodeFence($trimmed));
        $attempts[] = $this->extractFirstJsonDocument($trimmed);

        foreach ($attempts as $candidate) {
            if (! is_string($candidate) || trim($candidate) === '') {
                continue;
            }

            $decoded = json_decode($candidate, true);

            if (! is_array($decoded)) {
                continue;
            }

            if (array_is_list($decoded)) {
                $first = $decoded[0] ?? null;

                return is_array($first) ? $first : null;
            }

            return $decoded;
        }

        return null;
    }

    private function stripCodeFence(string $payload): string
    {
        return (string) preg_replace('/^\s*```(?:json)?\s*|\s*```\s*$/i', '', trim($payload));
    }

    private function decodeEscapedJsonString(string $payload): string
    {
        $decoded = json_decode($payload, true);

        return is_string($decoded) ? $decoded : '';
    }

    private function extractFirstJsonDocument(string $payload): string
    {
        $source = $this->stripCodeFence(trim($payload));
        $length = strlen($source);
        $buffer = '';
        $depth = 0;
        $inString = false;
        $escaped = false;
        $started = false;

        for ($index = 0; $index < $length; $index++) {
            $char = $source[$index];

            if (! $started) {
                if ($char !== '{' && $char !== '[') {
                    continue;
                }

                $started = true;
                $depth = 1;
                $buffer .= $char;
                continue;
            }

            $buffer .= $char;

            if ($inString) {
                if ($escaped) {
                    $escaped = false;
                    continue;
                }

                if ($char === '\\') {
                    $escaped = true;
                    continue;
                }

                if ($char === '"') {
                    $inString = false;
                }

                continue;
            }

            if ($char === '"') {
                $inString = true;
                continue;
            }

            if ($char === '{' || $char === '[') {
                $depth++;
                continue;
            }

            if ($char === '}' || $char === ']') {
                $depth--;

                if ($depth === 0) {
                    return $buffer;
                }
            }
        }

        return '';
    }

    /**
     * @return array<int, array{code:string, message:string, suggestion:string, context?:string}>
     */
    private function diagnoseJsonPayload(string $rawPayload): array
    {
        $trimmed = trim($rawPayload);
        $diagnostics = [];

        if ($trimmed === '') {
            return [[
                'code' => 'empty_payload',
                'message' => 'O conteúdo enviado está vazio.',
                'suggestion' => 'Cole um objeto JSON completo com chaves e valores.',
            ]];
        }

        if (preg_match('/^\s*```/i', $trimmed) === 1) {
            $diagnostics[] = [
                'code' => 'code_fence_detected',
                'message' => 'Foram encontrados delimitadores de bloco Markdown (```json).',
                'suggestion' => 'Remova os delimitadores ``` e envie somente JSON puro.',
            ];
        }

        if (preg_match('/]\\(https?:\\/\\/[^)]+\\)/i', $trimmed) === 1) {
            $diagnostics[] = [
                'code' => 'markdown_url_detected',
                'message' => 'Foi detectada URL em formato Markdown.',
                'suggestion' => 'Use URL como string simples, por exemplo: "https://...".',
            ];
        }

        if (preg_match('/}\\s*{/', $trimmed) === 1) {
            $diagnostics[] = [
                'code' => 'concatenated_objects',
                'message' => 'Foram detectados objetos JSON colados sem vírgula ou array.',
                'suggestion' => 'Use apenas 1 objeto ou envolva múltiplos objetos em um array JSON.',
                'context' => 'Padrão detectado: }{',
            ];
        }

        json_decode($trimmed, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $diagnostics[] = [
                'code' => 'json_parse_error',
                'message' => json_last_error_msg(),
                'suggestion' => 'Revise vírgulas, aspas duplas, colchetes/chaves e tipos de dados.',
            ];
        }

        if ($diagnostics === []) {
            $diagnostics[] = [
                'code' => 'unknown_json_issue',
                'message' => 'Não foi possível identificar a estrutura esperada para importação.',
                'suggestion' => 'Envie um objeto JSON compatível com o contrato do post.',
            ];
        }

        return $diagnostics;
    }

    /**
     * @return array<int, array{field:string, message:string}>
     */
    private function formatValidationDiagnostics(ValidationException $exception): array
    {
        $diagnostics = [];

        foreach ($exception->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $diagnostics[] = [
                    'field' => (string) $field,
                    'message' => (string) $message,
                ];
            }
        }

        return $diagnostics;
    }

    public function povPreview(): Response
    {
        return Inertia::render('Pages/posts/pov', [
            'pageType' => 'post',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $normalized = $this->inputNormalizer->normalize($request->all());
        $payload = $this->payloadValidator->validate($normalized, false);
        $post = $this->postCreator->create($payload, $request->user()?->id);

        return redirect()->route('admin.posts.edit', $post)
            ->with('success', 'Post criado com sucesso.');
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $normalized = $this->inputNormalizer->normalize($request->all(), $post);
        $payload = $this->payloadValidator->validate($normalized, true);
        $updatedPost = $this->postUpdater->update($post, $payload, $request->user()?->id);

        return redirect()->route('admin.posts.edit', $updatedPost)
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
            'title' => $post->cta_title,
            'text' => $post->cta_text,
            'button_text' => $post->cta_button_text,
            'button_url' => $post->cta_button_url,
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



    public function preview(Post $post, SeoBuilder $seoBuilder, MonetizationPolicy $monetizationPolicy): Response
    {
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
        $breadcrumbs = [
            ['name' => 'Admin', 'url' => route('admin.posts.index')],
            ['name' => 'Preview', 'url' => route('admin.posts.preview', $post)],
        ];

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
        ];

        $cta = [
            'title' => $post->cta_title,
            'text' => $post->cta_text,
            'button_text' => $post->cta_button_text,
            'button_url' => $post->cta_button_url,
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
            'pageType' => 'post-preview',
            'adSlots' => $monetizationPolicy->slotsFor('post'),
            'showAdSlots' => false,
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

    protected function editorLookups(): array
    {
        return [
            'categories' => PostCategory::query()->orderBy('name')->get(['id', 'name']),
            'tags' => PostTag::query()->orderBy('name')->get(['id', 'name']),
            'searchIntents' => ['informational', 'transactional', 'navigational', 'commercial', 'tool-support', 'tutorial', 'glossary'],
        ];
    }
}
