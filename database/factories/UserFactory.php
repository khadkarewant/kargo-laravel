<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        $first = fake()->firstName();
        $last = fake()->lastName();

        return [
            'name' => $first . ' ' . $last,
            'email' => Str::lower($first . '.' . $last . fake()->unique()->numberBetween(10, 999) . '@kargo-demo.test'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'customer',
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'customer',
        ]);
    }

    public function employee(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'employee',
        ]);
    }

    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'manager',
        ]);
    }

    public function demoCustomer(?string $name = null, ?string $email = null): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name ?? 'Demo Customer',
            'email' => $email ?? 'customer@kargo.test',
            'role' => 'customer',
        ]);
    }

    public function demoEmployee(?string $name = null, ?string $email = null): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name ?? 'Operations Staff',
            'email' => $email ?? 'employee@kargo.test',
            'role' => 'employee',
        ]);
    }

    public function demoManager(?string $name = null, ?string $email = null): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name ?? 'Operations Manager',
            'email' => $email ?? 'manager@kargo.test',
            'role' => 'manager',
        ]);
    }
}