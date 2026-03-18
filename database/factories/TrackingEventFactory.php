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
        $status = fake()->randomElement(ServiceRequest::TRACKING_STATUSES);

        return [
            'service_request_id' => ServiceRequest::factory()->approved(),
            'updated_by' => User::factory()->employee(),
            'tracking_status' => $status,
            'note' => $this->noteForStatus($status),
        ];
    }

    public function warehouse(): static
    {
        return $this->state(fn () => [
            'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
            'note' => 'Shipment received at warehouse and logged for processing.',
        ]);
    }

    public function customs(): static
    {
        return $this->state(fn () => [
            'tracking_status' => ServiceRequest::TRACKING_CUSTOMS,
            'note' => 'Shipment moved to customs checkpoint for clearance review.',
        ]);
    }

    public function office(): static
    {
        return $this->state(fn () => [
            'tracking_status' => ServiceRequest::TRACKING_OFFICE,
            'note' => 'Shipment arrived at office hub for internal handling.',
        ]);
    }

    public function route(): static
    {
        return $this->state(fn () => [
            'tracking_status' => ServiceRequest::TRACKING_ROUTE,
            'note' => 'Shipment dispatched and currently in transit to destination.',
        ]);
    }

    public function destination(): static
    {
        return $this->state(fn () => [
            'tracking_status' => ServiceRequest::TRACKING_DESTINATION,
            'note' => 'Shipment reached final destination and is ready for collection.',
        ]);
    }

    protected function noteForStatus(string $status): string
    {
        return match ($status) {
            ServiceRequest::TRACKING_WAREHOUSE => 'Shipment received at warehouse and logged for processing.',
            ServiceRequest::TRACKING_CUSTOMS => 'Shipment moved to customs checkpoint for clearance review.',
            ServiceRequest::TRACKING_OFFICE => 'Shipment arrived at office hub for internal handling.',
            ServiceRequest::TRACKING_ROUTE => 'Shipment dispatched and currently in transit to destination.',
            ServiceRequest::TRACKING_DESTINATION => 'Shipment reached final destination and is ready for collection.',
            default => 'Tracking status updated.',
        };
    }
}