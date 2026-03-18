<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FirstManagerSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_manager_setup_page_is_accessible_when_no_manager_exists(): void
    {
        $response = $this->get(route('setup.manager.create'));

        $response->assertOk();
    }

    public function test_first_manager_can_be_created_when_no_manager_exists(): void
    {
        $response = $this->post(route('setup.manager.store'), [
            'name' => 'First Manager',
            'email' => 'manager@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('manager.dashboard'));

        $this->assertDatabaseHas('users', [
            'name' => 'First Manager',
            'email' => 'manager@example.com',
            'role' => 'manager',
        ]);
    }

    public function test_first_manager_setup_page_is_forbidden_when_manager_already_exists(): void
    {
        User::factory()->manager()->create();

        $response = $this->get(route('setup.manager.create'));

        $response->assertForbidden();
    }

    public function test_first_manager_creation_is_forbidden_when_manager_already_exists(): void
    {
        User::factory()->manager()->create();

        $response = $this->post(route('setup.manager.store'), [
            'name' => 'Another Manager',
            'email' => 'another-manager@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertForbidden();
    }
}