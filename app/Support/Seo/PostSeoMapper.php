<?php

namespace App\Support\Seo;

use Illuminate\Support\Arr;

class PostSeoMapper
{
    /**
     * @param  array<string, mixed>|object  $post
     * @return array<string, mixed>
     */
    public function map(array|object $post): array
    {
        $data = (array) $post;
        $slug = (string) ($data['slug'] ?? '');

        return [
            'title' => $data['seo_title'] ?? $data['title'] ?? null,
            'path' => $slug !== '' ? '/posts/'.ltrim($slug, '/') : null,
            'canonical' => $data['canonical_url'] ?? null,
            'description' => $data['meta_description'] ?? $data['excerpt'] ?? null,
            'image' => $data['featured_image'] ?? null,
            'author' => $data['author_name'] ?? null,
            'section' => $this->extractCategory($data['category'] ?? null),
            'tags' => $this->extractTags($data['tags'] ?? []),
            'published_time' => $data['published_at'] ?? null,
            'modified_time' => $data['updated_at'] ?? null,
            'type' => 'article',
            'schema_type' => $data['schema_type'] ?? 'Article',
            'is_indexable' => $data['is_indexable'] ?? true,
            'faq_json' => $this->normalizeFaq($data['faq_json'] ?? []),
            'breadcrumb' => $this->buildBreadcrumb($data),
        ];
    }

    protected function extractCategory(mixed $category): ?string
    {
        if (is_array($category)) {
            return $category['name'] ?? null;
        }

        if (is_object($category) && isset($category->name)) {
            return (string) $category->name;
        }

        if (is_string($category)) {
            return $category;
        }

        return null;
    }

    protected function extractTags(mixed $tags): array
    {
        if (! is_array($tags)) {
            return [];
        }

        return array_values(array_filter(array_map(static function ($tag): ?string {
            if (is_array($tag)) {
                return Arr::get($tag, 'name');
            }

            if (is_object($tag) && isset($tag->name)) {
                return (string) $tag->name;
            }

            if (is_string($tag)) {
                return $tag;
            }

            return null;
        }, $tags)));
    }

    protected function normalizeFaq(mixed $faq): array
    {
        if (! is_array($faq)) {
            return [];
        }

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
        }, $faq)));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, array{name: string, url: string}>
     */
    protected function buildBreadcrumb(array $data): array
    {
        $slug = $data['slug'] ?? null;
        $title = $data['title'] ?? 'Post';

        if (! $slug) {
            return [];
        }

        $items = [
            ['name' => 'Início', 'url' => '/'],
            ['name' => 'Posts', 'url' => '/posts'],
        ];

        if (! empty($data['category']['slug'] ?? null) && ! empty($data['category']['name'] ?? null)) {
            $items[] = [
                'name' => (string) $data['category']['name'],
                'url' => '/categoria/'.ltrim((string) $data['category']['slug'], '/'),
            ];
        }

        $items[] = ['name' => (string) $title, 'url' => '/posts/'.ltrim((string) $slug, '/')];

        return $items;
    }
}
