<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Support\Posts\PostCreator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_post_is_accessible_and_draft_is_hidden(): void
    {
        $published = Post::factory()->create();
        $draft = Post::factory()->draft()->create();

        $this->get(route('posts.show', $published))->assertOk();
        $this->get(route('posts.show', $draft))->assertNotFound();
    }

    public function test_sitemap_includes_only_public_indexable_posts(): void
    {
        $public = Post::factory()->create();
        $notIndexable = Post::factory()->create(['is_indexable' => false]);

        $response = $this->get(route('seo.sitemap'));

        $response->assertOk();
        $response->assertSee(route('posts.show', $public), false);
        $response->assertDontSee(route('posts.show', $notIndexable), false);
    }

    public function test_post_creator_sanitizes_html_sets_reading_time_and_resolves_duplicate_slug(): void
    {
        Post::factory()->create(['title' => 'Post de segurança', 'slug' => 'post-de-seguranca']);

        $post = app(PostCreator::class)->create([
            'title' => 'Post de segurança',
            'slug' => 'post-de-seguranca',
            'content_html' => '<h2>Ok</h2><p>Texto seguro</p><script>alert(1)</script>',
            'status' => 'published',
            'is_published' => true,
        ]);

        $this->assertStringNotContainsString('<script>', $post->content_html);
        $this->assertGreaterThanOrEqual(1, $post->reading_time);
        $this->assertEquals('post-de-seguranca-2', $post->slug);
    }


    public function test_related_resolver_prioritizes_posts_with_shared_signals(): void
    {
        $main = Post::factory()->create(['related_keywords' => ['seo', 'laravel']]);
        $related = Post::factory()->create([
            'category_id' => $main->category_id,
            'related_keywords' => ['seo'],
        ]);

        $response = $this->get(route('posts.show', $main));

        $response->assertOk();
        $response->assertSee($related->title);
    }

    public function test_paginated_posts_list_uses_noindex(): void
    {
        Post::factory()->count(13)->create();

        $response = $this->get('/posts?page=2');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->where('seo.noindex', true)
            ->where('seo.canonical', route('posts.index'))
        );
    }
}
