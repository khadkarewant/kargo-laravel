<?php

namespace Database\Factories;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceRequest>
 */
class ServiceRequestFactory extends Factory
{
    protected $model = ServiceRequest::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->customer(),
            'service_type' => fake()->randomElement(ServiceRequest::SERVICE_TYPES),
            'sender_name' => fake()->name(),
            'sender_country' => fake()->country(),
            'sender_contact' => fake()->phoneNumber(),
            'receiver_name' => fake()->name(),
            'receiver_country' => fake()->country(),
            'receiver_contact' => fake()->phoneNumber(),
            'notes' => fake()->sentence(),
            'quantity' => null,
            'product_detail' => null,
            'weight' => null,
            'dimension' => null,
            'employee_note' => null,
            'manager_note' => null,
            'status' => ServiceRequest::STATUS_REQUEST,
            'tracking_status' => null,
            'processed_by' => null,
            'processed_at' => null,
            'is_trashed' => false,
            'trashed_at' => null,
            'trashed_by' => null,
            'trash_reason' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => ServiceRequest::STATUS_PENDING,
            'processed_by' => User::factory()->employee(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => ServiceRequest::STATUS_COMPLETED,
            'quantity' => '10 boxes',
            'product_detail' => 'Mixed logistics cargo',
            'weight' => '25 kg',
            'dimension' => '40x30x20 cm',
            'employee_note' => fake()->sentence(),
            'processed_by' => User::factory()->employee(),
            'processed_at' => now(),
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => ServiceRequest::STATUS_APPROVED,
            'quantity' => '10 boxes',
            'product_detail' => 'Mixed logistics cargo',
            'weight' => '25 kg',
            'dimension' => '40x30x20 cm',
            'employee_note' => fake()->sentence(),
            'processed_by' => User::factory()->employee(),
            'processed_at' => now(),
        ]);
    }

    public function revisionRequired(): static
    {
        return $this->state(fn () => [
            'status' => ServiceRequest::STATUS_REVISION_REQUIRED,
            'quantity' => '10 boxes',
            'product_detail' => 'Mixed logistics cargo',
            'weight' => '25 kg',
            'dimension' => '40x30x20 cm',
            'employee_note' => fake()->sentence(),
            'manager_note' => 'Please correct the shipment details.',
            'processed_by' => User::factory()->employee(),
            'processed_at' => now(),
        ]);
    }

    public function trashed(): static
    {
        return $this->state(fn () => [
            'is_trashed' => true,
            'trashed_at' => now(),
            'trashed_by' => User::factory()->manager(),
            'trash_reason' => 'Invalid request data.',
        ]);
    }
}