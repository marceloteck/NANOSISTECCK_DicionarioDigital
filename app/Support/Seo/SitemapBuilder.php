<?php

namespace App\Support\Seo;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\Tools\Tool;
use App\Support\Institutional\InstitutionalPageRegistry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class SitemapBuilder
{
    public function __construct(protected InstitutionalPageRegistry $institutionalPageRegistry)
    {
    }

    public function build(): Collection
    {
        $baseItems = collect([
            ['loc' => route('index.home'), 'changefreq' => 'daily', 'priority' => '1.0', 'lastmod' => now()->toAtomString()],
        ]);

        $postListingItems = collect();

        if ((bool) config('project.modules.posts', true)) {
            $postListingItems->push(['loc' => route('posts.index'), 'changefreq' => 'daily', 'priority' => '0.9', 'lastmod' => now()->toAtomString()]);
        }

        $postItems = ((bool) config('project.modules.posts', true) && Schema::hasTable('posts'))
            ? Post::query()
                ->published()
                ->indexable()
                ->get(['slug', 'updated_at'])
                ->map(fn (Post $post) => [
                    'loc' => route('posts.show', $post),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                    'lastmod' => optional($post->updated_at)->toAtomString() ?? now()->toAtomString(),
                ])
            : collect();

        $categoryItems = ((bool) config('project.modules.taxonomy', true) && Schema::hasTable('post_categories'))
            ? PostCategory::query()
                ->where('is_indexable', true)
                ->get(['slug', 'updated_at'])
                ->map(fn (PostCategory $category) => [
                    'loc' => route('posts.category', $category),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                    'lastmod' => optional($category->updated_at)->toAtomString() ?? now()->toAtomString(),
                ])
            : collect();

        $tagItems = ((bool) config('project.modules.taxonomy', true) && Schema::hasTable('post_tags'))
            ? PostTag::query()
                ->where('is_indexable', true)
                ->get(['slug', 'updated_at'])
                ->map(fn (PostTag $tag) => [
                    'loc' => route('posts.tag', $tag),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                    'lastmod' => optional($tag->updated_at)->toAtomString() ?? now()->toAtomString(),
                ])
            : collect();

        $toolListingItems = collect();
        if ((bool) config('project.modules.tools', false)) {
            $toolListingItems->push(['loc' => route('tools.index'), 'changefreq' => 'weekly', 'priority' => '0.8', 'lastmod' => now()->toAtomString()]);
        }

        $toolItems = ((bool) config('project.modules.tools', false) && Schema::hasTable('tools'))
            ? Tool::query()
                ->published()
                ->indexable()
                ->get(['slug', 'updated_at'])
                ->map(fn (Tool $tool) => [
                    'loc' => route('tools.show', $tool),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                    'lastmod' => optional($tool->updated_at)->toAtomString() ?? now()->toAtomString(),
                ])
            : collect();

        $institutionalItems = (bool) config('project.modules.institutional_pages', true)
            ? collect($this->institutionalPageRegistry->enabledPages())
                ->map(fn ($page) => [
                    'loc' => route('institutional.show', $page['slug']),
                    'changefreq' => 'monthly',
                    'priority' => '0.3',
                    'lastmod' => now()->toAtomString(),
                ])
            : collect();

        return $baseItems
            ->merge($postListingItems)
            ->merge($postItems)
            ->merge($categoryItems)
            ->merge($tagItems)
            ->merge($toolListingItems)
            ->merge($toolItems)
            ->merge($institutionalItems)
            ->unique('loc')
            ->values();
    }
}
