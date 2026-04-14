<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAdminUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_admin_user(): void
    {
        $this->artisan('user:create-admin', [
            'name' => 'Admin Master',
            'email' => 'admin@example.com',
            '--password' => 'secret123',
        ])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_it_can_promote_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'role' => User::ROLE_USER,
        ]);

        $this->artisan('user:create-admin', [
            'name' => 'Existing Admin',
            'email' => $user->email,
            '--password' => 'secret123',
            '--promote-existing' => true,
        ])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'email' => 'existing@example.com',
            'role' => User::ROLE_ADMIN,
            'name' => 'Existing Admin',
        ]);
    }
}
