<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'service_request_id' => ServiceRequest::factory(),
            'type' => 'request_submitted',
            'title' => fake()->sentence(3),
            'message' => fake()->sentence(),
            'read_at' => null,
        ];
    }

    public function unread(): static
    {
        return $this->state(fn () => [
            'read_at' => null,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn () => [
            'read_at' => now(),
        ]);
    }
}