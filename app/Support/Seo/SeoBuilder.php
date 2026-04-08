<?php

namespace App\Support\Seo;

use App\Support\Media\MediaSeoResolver;
use App\Support\Monetization\MonetizationPolicy;
use App\Support\Project\ModuleManager;
use App\Support\Project\PageTypeRegistry;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SeoBuilder
{
    public function __construct(
        protected ?SchemaBuilder $schemaBuilder = null,
        protected ?PostSeoMapper $postSeoMapper = null,
        protected ?CanonicalUrl $canonicalUrl = null,
        protected ?MediaSeoResolver $mediaSeoResolver = null,
        protected ?PageTypeRegistry $pageTypeRegistry = null,
        protected ?MonetizationPolicy $monetizationPolicy = null,
        protected ?ModuleManager $moduleManager = null,
    )
    {
        $this->schemaBuilder ??= new SchemaBuilder();
        $this->postSeoMapper ??= new PostSeoMapper();
        $this->canonicalUrl ??= new CanonicalUrl();
        $this->mediaSeoResolver ??= new MediaSeoResolver($this->canonicalUrl);
        $this->pageTypeRegistry ??= new PageTypeRegistry();
        $this->monetizationPolicy ??= new MonetizationPolicy();
        $this->moduleManager ??= new ModuleManager();
    }

    public function buildPage(array $data = []): array
    {
        $payload = $this->basePayload($data, 'website', $data['page_type'] ?? 'home');
        $payload['schema'] = $data['schema'] ?? $this->buildSchemaGraph($payload, [
            $this->schemaBuilder->webpage($payload),
            $this->schemaBuilder->breadcrumb($this->normalizeBreadcrumb($data['breadcrumb'] ?? [])),
        ]);

        return $payload;
    }

    public function buildArticle(array $data = []): array
    {
        $payload = $this->basePayload($data, 'article', $data['page_type'] ?? 'post');

        $payload['published_time'] = $data['published_time'] ?? $data['published_at'] ?? null;
        $payload['modified_time'] = $data['modified_time'] ?? $data['updated_at'] ?? null;
        $payload['section'] = $data['section'] ?? Arr::get($data, 'category.name');
        $payload['tags'] = $this->normalizeTags($data['tags'] ?? []);
        $payload['schema_type'] = $data['schema_type'] ?? 'Article';
        $payload['schema'] = $data['schema'] ?? $this->buildSchemaGraph($payload, [
            $this->schemaBuilder->webpage($payload),
            $this->schemaBuilder->article($payload),
            $this->schemaBuilder->faq($this->normalizeFaq($data['faq_json'] ?? [])),
            $this->schemaBuilder->breadcrumb($this->normalizeBreadcrumb($data['breadcrumb'] ?? [])),
        ]);

        return $payload;
    }

    public function buildPost(array|object $post): array
    {
        return $this->buildArticle($this->postSeoMapper->map($post));
    }

    public function buildTool(array $data = []): array
    {
        $payload = $this->basePayload($data, 'website', $data['page_type'] ?? 'tool');
        $payload['schema'] = $data['schema'] ?? $this->buildSchemaGraph($payload, [
            $this->schemaBuilder->webpage($payload),
            $this->schemaBuilder->tool($payload, $data),
            $this->schemaBuilder->faq($this->normalizeFaq($data['faq_json'] ?? [])),
            $this->schemaBuilder->howTo($data['how_to_steps'] ?? [], $payload),
            $this->schemaBuilder->breadcrumb($this->normalizeBreadcrumb($data['breadcrumb'] ?? [])),
        ]);

        return $payload;
    }

    public function buildCategory(array $data = []): array
    {
        $payload = $this->basePayload($data, 'website', $data['page_type'] ?? 'listing');
        $payload['tags'] = $this->normalizeTags($data['tags'] ?? []);
        $payload['section'] = $data['section'] ?? $payload['title'];
        $payload['schema'] = $data['schema'] ?? $this->buildSchemaGraph($payload, [
            $this->schemaBuilder->webpage($payload),
            $this->schemaBuilder->collectionPage($payload),
            $this->schemaBuilder->breadcrumb($this->normalizeBreadcrumb($data['breadcrumb'] ?? [])),
        ]);

        return $payload;
    }

    protected function basePayload(array $data, string $defaultType, string $pageType): array
    {
        $seo = config('seo');

        $title = trim((string) ($data['title'] ?? $seo['title_default'] ?? ''));
        $description = trim((string) ($data['description'] ?? $seo['description_default'] ?? ''));
        $isIndexable = array_key_exists('is_indexable', $data)
            ? (bool) $data['is_indexable']
            : $this->pageTypeRegistry->indexable($pageType);

        return [
            'title' => $title,
            'description' => $description,
            'image' => $this->mediaSeoResolver->resolveImage($data['image'] ?? $data['og_image'] ?? null, $pageType),
            'canonical' => $this->buildCanonical($data),
            'robots' => $this->buildRobots($data),
            'type' => $data['type'] ?? $defaultType,
            'author' => $data['author'] ?? $seo['author'] ?? null,
            'noindex' => $this->shouldNoindex([...$data, 'is_indexable' => $isIndexable]),
            'published_time' => null,
            'modified_time' => null,
            'tags' => [],
            'section' => null,
            'page_type' => $pageType,
            'layout' => $this->pageTypeRegistry->layoutFor($pageType),
            'modules' => $this->moduleManager->enabledModules(),
            'ads' => [
                'show_slots' => $this->monetizationPolicy->shouldShowSlots($pageType, $isIndexable),
                'slots' => $this->monetizationPolicy->slotsFor($pageType),
            ],
        ];
    }

    protected function buildCanonical(array $data): string
    {
        if (! empty($data['canonical'])) {
            return $this->canonicalUrl->normalize((string) $data['canonical']);
        }

        if (! empty($data['path'])) {
            return $this->canonicalUrl->normalize((string) $data['path']);
        }

        return $this->canonicalUrl->normalize(url()->current());
    }

    protected function buildRobots(array $data): string
    {
        if ($this->shouldNoindex($data)) {
            return 'noindex, nofollow';
        }

        if (! empty($data['robots'])) {
            return (string) $data['robots'];
        }

        return 'index, follow';
    }

    protected function shouldNoindex(array $data): bool
    {
        if (array_key_exists('noindex', $data)) {
            return (bool) $data['noindex'];
        }

        if (array_key_exists('is_indexable', $data) && $data['is_indexable'] === false) {
            return true;
        }

        $indexation = config('seo.indexation', []);
        $enabled = (bool) Arr::get($indexation, 'enabled', true);
        $allowedEnvs = Arr::wrap(Arr::get($indexation, 'allow_in_env', ['production']));
        $isAllowedEnvironment = in_array(app()->environment(), $allowedEnvs, true);

        if (! $enabled || ! $isAllowedEnvironment) {
            return true;
        }

        $path = parse_url($data['canonical'] ?? $data['path'] ?? url()->current(), PHP_URL_PATH) ?: '/';
        $disallowPaths = Arr::wrap(config('seo.robots.disallow_paths', []));

        return collect($disallowPaths)->contains(
            static fn ($disallowPath) => str_starts_with($path, '/'.ltrim((string) $disallowPath, '/'))
        );
    }

    protected function normalizeFaq(array $items): array
    {
        return array_values(array_filter(array_map(static function ($item): ?array {
            if (! is_array($item)) {
                return null;
            }

            $question = trim((string) ($item['question'] ?? ''));
            $answer = trim((string) ($item['answer'] ?? ''));

            if ($question === '' || $answer === '') {
                return null;
            }

            return ['question' => $question, 'answer' => $answer];
        }, $items)));
    }

    protected function normalizeTags(array $tags): array
    {
        return array_values(array_filter(array_map(static fn ($tag) => trim((string) $tag), $tags)));
    }

    protected function normalizeBreadcrumb(array $items): array
    {
        return array_values(array_filter(array_map(
            static fn ($item) => [
                'name' => $item['name'] ?? null,
                'url' => $item['url'] ?? null,
            ],
            $items
        ), static fn ($item) => ! empty($item['name'])));
    }

    protected function buildSchemaGraph(array $payload, array $extraNodes = []): array
    {
        return $this->schemaBuilder->graph([
            $this->schemaBuilder->website(),
            $this->schemaBuilder->organization(),
            ...$extraNodes,
        ]);
    }
}
