<?php

namespace App\Support\Posts;

use App\Models\Post;
use App\Models\PostTag;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostCreator
{
    public function __construct(
        protected PostContentFormatter $formatter,
        protected PostCategoryResolver $categoryResolver,
    ) {
    }

    public function create(array $payload, ?int $userId = null): Post
    {
        $post = new Post();

        return $this->persist($post, $payload, $userId, true);
    }

    public function update(Post $post, array $payload, ?int $userId = null): Post
    {
        return $this->persist($post, $payload, $userId, false);
    }

    protected function persist(Post $post, array $payload, ?int $userId, bool $isCreating): Post
    {
        $contentHtml = (string) Arr::get($payload, 'content_html', $post->content_html ?? '');
        $formatted = $this->formatter->prepare($contentHtml);
        $faq = $this->formatter->normalizeFaq(Arr::get($payload, 'faq_json', $post->faq_json ?? []));
        $baseTitle = (string) Arr::get($payload, 'title', $post->title ?? 'post');
        $requestedSlug = (string) Arr::get($payload, 'slug', $post->slug ?? '');
        $categoryId = $this->categoryResolver->resolveId(
            Arr::get($payload, 'category_name'),
            Arr::get($payload, 'category_id')
        );
        $payloadWithoutCategoryName = Arr::except($payload, ['category_name']);

        $tagIds = $this->resolveTagIds(Arr::get($payload, 'tags', []));

        return DB::transaction(function () use ($post, $payloadWithoutCategoryName, $categoryId, $userId, $isCreating, $formatted, $faq, $baseTitle, $requestedSlug, $tagIds): Post {
            $post->fill([
                ...$payloadWithoutCategoryName,
                'slug' => $this->makeUniqueSlug($requestedSlug !== '' ? $requestedSlug : Str::slug($baseTitle), $post->id),
                'category_id' => $categoryId,
                'content_html' => $formatted['content_html'],
                'reading_time' => $formatted['reading_time'],
                'faq_json' => $faq,
                'status' => Arr::get($payloadWithoutCategoryName, 'status', Arr::get($payloadWithoutCategoryName, 'is_published', $post->is_published) ? 'published' : 'draft'),
                'updated_by' => $userId,
            ]);

            if ($isCreating) {
                $post->created_by = $userId;
            }

            $post->save();

            if (isset($payloadWithoutCategoryName['tags']) && is_array($payloadWithoutCategoryName['tags'])) {
                $post->tags()->sync($tagIds);
            }

            return $post->refresh()->load(['category', 'tags']);
        });
    }

    protected function resolveTagIds(array $tags): array
    {
        $ids = [];

        foreach ($tags as $tag) {
            if (is_int($tag) || ctype_digit((string) $tag)) {
                $ids[] = (int) $tag;
                continue;
            }

            if (! is_string($tag)) {
                continue;
            }

            $name = trim($tag);
            if ($name === '') {
                continue;
            }

            $model = PostTag::query()->firstOrCreate([
                'slug' => Str::slug($name),
            ], [
                'name' => $name,
            ]);

            $ids[] = $model->id;
        }

        return array_values(array_unique($ids));
    }

    protected function makeUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug) ?: 'post';
        $candidate = $base;
        $counter = 2;

        while (Post::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $base.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }
}
