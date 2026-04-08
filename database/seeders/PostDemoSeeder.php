<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use Illuminate\Database\Seeder;

class PostDemoSeeder extends Seeder
{
    public function run(): void
    {
        $category = PostCategory::query()->firstOrCreate(
            ['slug' => 'seo-tecnico'],
            ['name' => 'SEO Técnico', 'meta_description' => 'Conteúdos de SEO técnico para sites escaláveis.']
        );

        $tagSeo = PostTag::query()->firstOrCreate(['slug' => 'seo'], ['name' => 'SEO']);
        $tagLaravel = PostTag::query()->firstOrCreate(['slug' => 'laravel'], ['name' => 'Laravel']);

        $post = Post::query()->firstOrCreate(
            ['slug' => 'guia-seo-laravel'],
            [
                'title' => 'Guia de SEO em Laravel: estrutura profissional',
                'excerpt' => 'Aprenda uma arquitetura SEO-first para conteúdos escaláveis em Laravel + Vue + Inertia.',
                'content_html' => '<h2>Introdução</h2><p>Este guia mostra como estruturar conteúdos para ranqueamento.</p><h2>Implementação</h2><p>Use slugs limpos, schema e interlinking.</p><h3>Checklist</h3><ul><li>Title</li><li>Meta description</li><li>Canonical</li></ul>',
                'meta_description' => 'Estrutura SEO-first com Laravel e Vue para crescer organicamente com qualidade.',
                'search_intent' => 'tutorial',
                'content_type' => 'guide',
                'category_id' => $category->id,
                'author_name' => 'Equipe Editorial',
                'is_published' => true,
                'status' => 'published',
                'is_indexable' => true,
                'faq_json' => [
                    ['question' => 'Preciso de schema em todo post?', 'answer' => 'Sim, quando fizer sentido editorial e factual.'],
                ],
            ]
        );

        $post->tags()->syncWithoutDetaching([$tagSeo->id, $tagLaravel->id]);
    }
}
