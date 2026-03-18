<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FirstManagerSetupValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_manager_setup_requires_name_email_and_password(): void
    {
        $response = $this
            ->from(route('setup.manager.create'))
            ->post(route('setup.manager.store'), []);

        $response->assertRedirect(route('setup.manager.create'));
        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
        ]);
    }

    public function test_first_manager_setup_requires_unique_email(): void
    {
        User::factory()->create([
            'email' => 'manager@example.com',
            'role' => 'customer',
        ]);

        $response = $this
            ->from(route('setup.manager.create'))
            ->post(route('setup.manager.store'), [
                'name' => 'First Manager',
                'email' => 'manager@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertRedirect(route('setup.manager.create'));
        $response->assertSessionHasErrors('email');
    }

    public function test_first_manager_setup_requires_password_confirmation_to_match(): void
    {
        $response = $this
            ->from(route('setup.manager.create'))
            ->post(route('setup.manager.store'), [
                'name' => 'First Manager',
                'email' => 'manager@example.com',
                'password' => 'password123',
                'password_confirmation' => 'different-password',
            ]);

        $response->assertRedirect(route('setup.manager.create'));
        $response->assertSessionHasErrors('password');
    }
}