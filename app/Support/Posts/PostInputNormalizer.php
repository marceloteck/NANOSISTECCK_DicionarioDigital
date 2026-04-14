<?php

namespace App\Support\Posts;

use App\Models\Post;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PostInputNormalizer
{
    public function normalize(array $input, ?Post $post = null): array
    {
        $title = $this->cleanString(Arr::get($input, 'title', $post?->title));
        $excerpt = $this->cleanString(Arr::get($input, 'excerpt', $post?->excerpt));
        $contentHtml = trim((string) Arr::get($input, 'content_html', $post?->content_html ?? ''));
        $status = (string) Arr::get($input, 'status', Arr::get($input, 'is_published') ? 'published' : ($post?->status ?? 'draft'));
        $status = in_array($status, ['draft', 'published'], true) ? $status : 'draft';
        $isPublished = filter_var(Arr::get($input, 'is_published', $status === 'published'), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

        return [
            'title' => $title,
            'slug' => $this->normalizeSlug((string) Arr::get($input, 'slug', $post?->slug ?: $title)),
            'excerpt' => $excerpt,
            'hero_title' => $this->cleanString(Arr::get($input, 'hero_title', $title)),
            'hero_summary' => $this->cleanString(Arr::get($input, 'hero_summary', $excerpt)),
            'quick_answer' => $this->cleanString(Arr::get($input, 'quick_answer', $excerpt)),
            'author_name' => $this->cleanString(Arr::get($input, 'author_name', $post?->author_name)),
            'status' => $status,
            'published_at' => Arr::get($input, 'published_at', $post?->published_at),
            'category_id' => $this->nullableInt(Arr::get($input, 'category_id', $post?->category_id)),
            'related_keywords' => $this->normalizeStringList(Arr::get($input, 'related_keywords', $post?->related_keywords ?? [])),
            'search_intent' => $this->cleanString(Arr::get($input, 'search_intent', $post?->search_intent ?? 'informational')),
            'content_type' => $this->cleanString(Arr::get($input, 'content_type', $post?->content_type ?? 'guide')),
            'featured_image' => $this->cleanString(Arr::get($input, 'featured_image', $post?->featured_image)),
            'featured_image_alt' => $this->cleanString(Arr::get($input, 'featured_image_alt', $post?->featured_image_alt)),
            'content_html' => $contentHtml,
            'faq_json' => $this->normalizeFaq(Arr::get($input, 'faq_json', $post?->faq_json ?? [])),
            'seo_title' => $this->cleanString(Arr::get($input, 'seo_title', $title)),
            'meta_description' => $this->cleanString(Arr::get($input, 'meta_description', $excerpt)),
            'meta_keywords' => $this->cleanString(Arr::get($input, 'meta_keywords', $post?->meta_keywords)),
            'canonical_url' => $this->cleanString(Arr::get($input, 'canonical_url', $post?->canonical_url)),
            'schema_type' => $this->cleanString(Arr::get($input, 'schema_type', $post?->schema_type ?? 'Article')),
            'is_indexable' => filter_var(Arr::get($input, 'is_indexable', $post?->is_indexable ?? true), FILTER_VALIDATE_BOOL),
            'is_published' => $isPublished ?? false,
            'cta_title' => $this->cleanString(Arr::get($input, 'cta_title', $post?->cta_title)),
            'cta_text' => $this->cleanString(Arr::get($input, 'cta_text', $post?->cta_text)),
            'cta_button_text' => $this->cleanString(Arr::get($input, 'cta_button_text', $post?->cta_button_text)),
            'cta_button_url' => $this->cleanString(Arr::get($input, 'cta_button_url', $post?->cta_button_url)),
            'tags' => $this->normalizeTags(Arr::get($input, 'tags', $post?->tags?->pluck('id')->all() ?? [])),
        ];
    }

    protected function normalizeSlug(string $value): string
    {
        return Str::slug(trim($value));
    }

    protected function normalizeStringList(mixed $input): array
    {
        if (is_string($input)) {
            $input = explode(',', $input);
        }

        if (! is_array($input)) {
            return [];
        }

        return array_values(array_filter(array_map(fn ($item) => $this->cleanString($item), $input)));
    }

    protected function normalizeFaq(mixed $input): array
    {
        if (! is_array($input)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($item): ?array {
            if (! is_array($item)) {
                return null;
            }

            $question = $this->cleanString($item['question'] ?? null);
            $answer = $this->cleanString($item['answer'] ?? null);
            if ($question === null && $answer === null) {
                return null;
            }

            return ['question' => $question, 'answer' => $answer];
        }, $input)));
    }

    protected function normalizeTags(mixed $tags): array
    {
        if (is_string($tags)) {
            $tags = explode(',', $tags);
        }

        if (! is_array($tags)) {
            return [];
        }

        $normalized = [];

        foreach ($tags as $tag) {
            if (is_numeric($tag)) {
                $normalized[] = (int) $tag;
                continue;
            }

            if (is_array($tag)) {
                if (isset($tag['id']) && is_numeric($tag['id'])) {
                    $normalized[] = (int) $tag['id'];
                    continue;
                }

                $name = $this->cleanString($tag['name'] ?? null);
                if ($name !== null) {
                    $normalized[] = $name;
                }

                continue;
            }

            $name = $this->cleanString($tag);
            if ($name !== null) {
                $normalized[] = $name;
            }
        }

        return array_values(array_unique($normalized, SORT_REGULAR));
    }

    protected function cleanString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $cleaned = trim((string) $value);

        return $cleaned === '' ? null : $cleaned;
    }

    protected function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }
}
