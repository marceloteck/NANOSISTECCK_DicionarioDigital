<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use Database\Seeders\PostDemoSeeder;
use Illuminate\Http\JsonResponse;

class SeedDevelopmentContentController extends Controller
{
    public function __invoke(): JsonResponse
    {
        abort_unless(app()->environment(['local', 'testing']), 404);

        app(PostDemoSeeder::class)->run();

        $post = Post::query()->where('slug', 'guia-seo-laravel')->first();

        return response()->json([
            'message' => 'Conteúdo de desenvolvimento inserido com sucesso.',
            'data' => [
                'posts_total' => Post::query()->count(),
                'categories_total' => PostCategory::query()->count(),
                'tags_total' => PostTag::query()->count(),
                'example_post_slug' => $post?->slug,
                'example_post_url' => $post ? route('posts.show', $post) : null,
                'example_category_url' => route('posts.category', 'seo-tecnico'),
                'example_tag_url' => route('posts.tag', 'seo'),
            ],
        ]);
    }
}
