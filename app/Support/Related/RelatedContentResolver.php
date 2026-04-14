<?php

namespace App\Support\Related;

use App\Models\Post;
use App\Models\Tools\Tool;

class RelatedContentResolver
{
    public function relatedForPost(Post $post, int $limit = 4): array
    {
        $posts = Post::query()
            ->published()
            ->whereKeyNot($post->getKey())
            ->when($post->category_id, fn ($query) => $query->where('category_id', $post->category_id))
            ->latest('published_at')
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'excerpt'])
            ->map(fn (Post $item) => [
                'type' => 'post',
                'id' => $item->id,
                'slug' => $item->slug,
                'title' => $item->title,
                'excerpt' => $item->excerpt,
                'url' => route('posts.show', $item),
            ]);

        return $posts->values()->all();
    }

    public function relatedForTool(Tool $tool, int $limit = 4): array
    {
        $tools = Tool::query()
            ->published()
            ->whereKeyNot($tool->getKey())
            ->latest('updated_at')
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'excerpt'])
            ->map(fn (Tool $item) => [
                'type' => 'tool',
                'id' => $item->id,
                'title' => $item->title,
                'excerpt' => $item->excerpt,
                'url' => route('tools.show', $item),
            ]);

        return $tools->values()->all();
    }
}
