<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPostEditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_editor_pages(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)->get(route('admin.posts.index'))->assertOk();
        $this->actingAs($user)->get(route('admin.posts.create'))->assertOk();
        $this->actingAs($user)->get(route('admin.posts.edit', $post))->assertOk();
    }

    public function test_admin_can_import_valid_json_payload_with_tags_and_keep_contract(): void
    {
        $user = User::factory()->create();
        $category = PostCategory::factory()->create();
        $existingTag = PostTag::factory()->create();

        $payload = [
            'title' => 'Importado por JSON',
            'excerpt' => 'Resumo objetivo para o post.',
            'content_html' => '<h2>Contexto</h2><p>Conteúdo válido.</p>',
            'category_id' => $category->id,
            'seo_title' => 'Importado por JSON',
            'meta_description' => 'Descrição para SEO.',
            'status' => 'published',
            'is_published' => true,
            'tags' => [$existingTag->id, 'novo termo'],
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.posts.import-json'), ['payload_json' => json_encode($payload)]);

        $response->assertOk()
            ->assertJsonPath('data.title', 'Importado por JSON')
            ->assertJsonPath('data.tags.0', $existingTag->id)
            ->assertJsonPath('data.tags.1', 'novo termo');
    }

    public function test_failed_publish_does_not_create_tags_before_validation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'Post incompleto',
                'status' => 'published',
                'is_published' => true,
                'content_html' => '<p>ok</p>',
                'tags' => ['nao deve criar'],
            ])
            ->assertSessionHasErrors(['excerpt', 'category_id', 'seo_title', 'meta_description']);

        $this->assertDatabaseMissing('post_tags', ['slug' => 'nao-deve-criar']);
    }

    public function test_manual_create_persists_premium_fields_and_tags_and_preview_works_for_draft(): void
    {
        $user = User::factory()->create();
        $category = PostCategory::factory()->create();

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'Post premium',
                'slug' => 'post-premium',
                'excerpt' => 'Resumo premium.',
                'content_html' => '<h2>Título interno</h2><p>Conteúdo</p>',
                'category_id' => $category->id,
                'seo_title' => 'SEO premium',
                'meta_description' => 'Descrição SEO premium.',
                'status' => 'draft',
                'is_published' => false,
                'hero_title' => 'Hero custom',
                'hero_summary' => 'Resumo hero custom',
                'quick_answer' => 'Resposta rápida custom',
                'cta_title' => 'CTA custom',
                'cta_text' => 'Texto CTA custom',
                'cta_button_text' => 'Clique aqui',
                'cta_button_url' => 'https://example.com/cta',
                'tags' => ['seo tecnico'],
            ])
            ->assertRedirect();

        $post = Post::query()->where('slug', 'post-premium')->firstOrFail();

        $this->assertEquals('Hero custom', $post->hero_title);
        $this->assertEquals('Resposta rápida custom', $post->quick_answer);
        $this->assertEquals('CTA custom', $post->cta_title);
        $this->assertCount(1, $post->tags);

        $this->actingAs($user)
            ->get(route('admin.posts.preview', $post))
            ->assertOk()
            ->assertSee('Hero custom')
            ->assertSee('Resposta rápida custom')
            ->assertSee('CTA custom');
    }


    public function test_ui_auxiliary_tags_input_is_not_persisted_when_tags_array_is_missing(): void
    {
        $user = User::factory()->create();
        $category = PostCategory::factory()->create();

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'Post sem tags reais',
                'slug' => 'post-sem-tags-reais',
                'excerpt' => 'Resumo válido',
                'content_html' => '<h2>Conteúdo</h2><p>Teste</p>',
                'category_id' => $category->id,
                'seo_title' => 'SEO título',
                'meta_description' => 'SEO descrição',
                'status' => 'draft',
                'is_published' => false,
                'tags_input' => 'isso-nao-deve-persistir',
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('post_tags', ['slug' => 'isso-nao-deve-persistir']);

        $post = Post::query()->where('slug', 'post-sem-tags-reais')->firstOrFail();
        $this->assertCount(0, $post->tags);
    }

    public function test_publish_requires_critical_fields(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'Post incompleto',
                'status' => 'published',
                'is_published' => true,
                'content_html' => '<p>ok</p>',
            ])
            ->assertSessionHasErrors(['excerpt', 'category_id', 'seo_title', 'meta_description']);
    }
}
