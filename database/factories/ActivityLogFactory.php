<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        $event = fake()->randomElement([
            [
                'action' => 'status_updated',
                'field_changed' => 'status',
                'old_value' => ServiceRequest::STATUS_REQUEST,
                'new_value' => ServiceRequest::STATUS_PENDING,
                'description' => 'Employee moved request from request to pending.',
            ],
            [
                'action' => 'status_updated',
                'field_changed' => 'status',
                'old_value' => ServiceRequest::STATUS_PENDING,
                'new_value' => ServiceRequest::STATUS_COMPLETED,
                'description' => 'Employee marked request as completed for manager review.',
            ],
            [
                'action' => 'tracking_status_updated',
                'field_changed' => 'tracking_status',
                'old_value' => ServiceRequest::TRACKING_WAREHOUSE,
                'new_value' => ServiceRequest::TRACKING_CUSTOMS,
                'description' => 'Employee updated shipment tracking from warehouse to customs.',
            ],
            [
                'action' => 'request_approved',
                'field_changed' => 'status',
                'old_value' => ServiceRequest::STATUS_COMPLETED,
                'new_value' => ServiceRequest::STATUS_APPROVED,
                'description' => 'Manager approved the completed request.',
            ],
            [
                'action' => 'revision_required',
                'field_changed' => 'status',
                'old_value' => ServiceRequest::STATUS_COMPLETED,
                'new_value' => ServiceRequest::STATUS_REVISION_REQUIRED,
                'description' => 'Manager requested revision before approval.',
            ],
        ]);

        return [
            'service_request_id' => ServiceRequest::factory(),
            'user_id' => User::factory(),
            'action' => $event['action'],
            'field_changed' => $event['field_changed'],
            'old_value' => $event['old_value'],
            'new_value' => $event['new_value'],
            'description' => $event['description'],
        ];
    }

    public function statusUpdated(
        string $oldStatus = ServiceRequest::STATUS_REQUEST,
        string $newStatus = ServiceRequest::STATUS_PENDING
    ): static {
        return $this->state(fn () => [
            'action' => 'status_updated',
            'field_changed' => 'status',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'description' => "Request status changed from {$oldStatus} to {$newStatus}.",
        ]);
    }

    public function trackingUpdated(
        ?string $oldStatus = null,
        string $newStatus = ServiceRequest::TRACKING_WAREHOUSE
    ): static {
        return $this->state(fn () => [
            'action' => 'tracking_status_updated',
            'field_changed' => 'tracking_status',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'description' => "Tracking status updated to {$newStatus}.",
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'action' => 'request_approved',
            'field_changed' => 'status',
            'old_value' => ServiceRequest::STATUS_COMPLETED,
            'new_value' => ServiceRequest::STATUS_APPROVED,
            'description' => 'Manager approved the completed request.',
        ]);
    }

    public function revisionRequired(): static
    {
        return $this->state(fn () => [
            'action' => 'revision_required',
            'field_changed' => 'status',
            'old_value' => ServiceRequest::STATUS_COMPLETED,
            'new_value' => ServiceRequest::STATUS_REVISION_REQUIRED,
            'description' => 'Manager requested revision before approval.',
        ]);
    }
}