<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = ucfirst($this->faker->words(6, true));

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numberBetween(10, 9999),
            'excerpt' => $this->faker->sentence(18),
            'content_html' => '<h2>Introdução</h2><p>'.$this->faker->paragraph(4).'</p><h2>Como aplicar</h2><p>'.$this->faker->paragraph(6).'</p>',
            'seo_title' => $title,
            'meta_description' => $this->faker->sentence(18),
            'search_intent' => 'informational',
            'content_type' => 'guide',
            'category_id' => PostCategory::factory(),
            'author_name' => $this->faker->name(),
            'published_at' => now()->subDays(2),
            'is_published' => true,
            'is_indexable' => true,
            'reading_time' => 4,
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => 'draft',
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
