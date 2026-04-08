<?php

namespace App\Support\Posts;

use App\Models\Post;
use Illuminate\Support\Collection;

class PostRelatedResolver
{
    public function resolve(Post $post, int $limit = 4): Collection
    {
        $post->loadMissing('tags:id,name');

        $tagIds = $post->tags->pluck('id');
        $keywords = collect($post->related_keywords ?? [])->map(fn ($keyword) => mb_strtolower((string) $keyword))->filter();

        return Post::query()
            ->published()
            ->whereKeyNot($post->id)
            ->with('tags:id,name')
            ->get(['id', 'title', 'slug', 'excerpt', 'published_at', 'category_id', 'related_keywords'])
            ->map(function (Post $candidate) use ($post, $tagIds, $keywords): Post {
                $score = 0;

                if ($candidate->category_id === $post->category_id && $candidate->category_id !== null) {
                    $score += 3;
                }

                $sharedTags = $candidate->tags->pluck('id')->intersect($tagIds)->count();
                $score += $sharedTags * 2;

                $candidateKeywords = collect($candidate->related_keywords ?? [])->map(fn ($keyword) => mb_strtolower((string) $keyword));
                $score += $candidateKeywords->intersect($keywords)->count();

                $candidate->related_score = $score;

                return $candidate;
            })
            ->filter(fn (Post $candidate) => ($candidate->related_score ?? 0) > 0)
            ->sortByDesc('related_score')
            ->take($limit)
            ->values();
    }
}
