<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_posts(): void
    {
        $this->get('/admin/posts')->assertRedirect('/login');
    }

    public function test_non_admin_user_cannot_access_admin_posts(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/posts')->assertForbidden();
    }

    public function test_admin_user_can_access_admin_posts(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/posts')->assertOk();
    }
}
