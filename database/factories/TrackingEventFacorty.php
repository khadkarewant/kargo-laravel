<?php

namespace Database\Factories;

use App\Models\ServiceRequest;
use App\Models\TrackingEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrackingEvent>
 */
class TrackingEventFactory extends Factory
{
    protected $model = TrackingEvent::class;

    public function definition(): array
    {
        return [
            'service_request_id' => ServiceRequest::factory()->approved(),
            'updated_by' => User::factory()->employee(),
            'tracking_status' => fake()->randomElement(ServiceRequest::TRACKING_STATUSES),
            'note' => fake()->sentence(),
        ];
    }

    public function warehouse(): static
    {
        return $this->state(fn () => [
            'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
        ]);
    }

    public function customs(): static
    {
        return $this->state(fn () => [
            'tracking_status' => ServiceRequest::TRACKING_CUSTOMS,
        ]);
    }

    public function office(): static
    {
        return $this->state(fn () => [
            'tracking_status' => ServiceRequest::TRACKING_OFFICE,
        ]);
    }

    public function route(): static
    {
        return $this->state(fn () => [
            'tracking_status' => ServiceRequest::TRACKING_ROUTE,
        ]);
    }

    public function destination(): static
    {
        return $this->state(fn () => [
            'tracking_status' => ServiceRequest::TRACKING_DESTINATION,
        ]);
    }
}