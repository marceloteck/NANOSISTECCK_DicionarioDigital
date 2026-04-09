<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevSeedRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_dev_seed_route_populates_posts_categories_and_tags(): void
    {
        $response = $this->get(route('dev.seed.posts-taxonomy'));

        $response->assertOk();
        $response->assertJsonPath('data.example_post_slug', 'guia-seo-laravel');

        $this->assertDatabaseHas('post_categories', ['slug' => 'seo-tecnico']);
        $this->assertDatabaseHas('post_tags', ['slug' => 'seo']);
        $this->assertDatabaseHas('post_tags', ['slug' => 'laravel']);
        $this->assertDatabaseHas('posts', ['slug' => 'guia-seo-laravel']);
    }
}
