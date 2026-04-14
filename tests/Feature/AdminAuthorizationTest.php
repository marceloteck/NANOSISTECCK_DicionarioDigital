<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_post_routes_are_registered(): void
    {
        $this->assertTrue(Route::has('admin.posts.index'));
        $this->assertTrue(Route::has('admin.posts.create'));
        $this->assertTrue(Route::has('admin.posts.edit'));
        $this->assertTrue(Route::has('admin.posts.preview'));
        $this->assertTrue(Route::has('admin.posts.import-json'));
    }

    public function test_guest_is_redirected_from_admin_posts_index_and_create(): void
    {
        $this->get('/admin/posts')->assertRedirect('/login');
        $this->get('/admin/posts/create')->assertRedirect('/login');
    }

    public function test_non_admin_user_cannot_access_admin_posts_index_and_create(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/posts')->assertForbidden();
        $this->actingAs($user)->get('/admin/posts/create')->assertForbidden();
    }

    public function test_admin_user_can_access_admin_posts_index_and_create(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/posts')->assertOk();
        $this->actingAs($admin)->get('/admin/posts/create')->assertOk();
    }
}
