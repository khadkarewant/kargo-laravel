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
        $type = fake()->randomElement([
            'request_submitted',
            'request_approved',
            'tracking_updated',
            'revision_required',
            'request_completed',
            'request_recompleted',
        ]);

        $content = $this->contentForType($type);

        return [
            'user_id' => User::factory(),
            'service_request_id' => ServiceRequest::factory(),
            'type' => $type,
            'title' => $content['title'],
            'message' => $content['message'],
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

    public function submitted(): static
    {
        return $this->state(fn () => [
            'type' => 'request_submitted',
            'title' => 'New Request Submitted',
            'message' => 'A new request has been submitted and is waiting for processing.',
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'type' => 'request_approved',
            'title' => 'Request Approved',
            'message' => 'Your request has been approved and is ready for the next workflow stage.',
        ]);
    }

    public function trackingUpdated(): static
    {
        return $this->state(fn () => [
            'type' => 'tracking_updated',
            'title' => 'Tracking Updated',
            'message' => 'The tracking status for your shipment has been updated.',
        ]);
    }

    public function revisionRequired(): static
    {
        return $this->state(fn () => [
            'type' => 'revision_required',
            'title' => 'Revision Required',
            'message' => 'This request requires correction before approval can continue.',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'type' => 'request_completed',
            'title' => 'Request Completed',
            'message' => 'A request has been marked completed and is ready for review.',
        ]);
    }

    public function recompleted(): static
    {
        return $this->state(fn () => [
            'type' => 'request_recompleted',
            'title' => 'Request Re-completed',
            'message' => 'A revised request has been completed again after correction.',
        ]);
    }

    protected function contentForType(string $type): array
    {
        return match ($type) {
            'request_submitted' => [
                'title' => 'New Request Submitted',
                'message' => 'A new request has been submitted and is waiting for processing.',
            ],
            'request_approved' => [
                'title' => 'Request Approved',
                'message' => 'Your request has been approved and is ready for the next workflow stage.',
            ],
            'tracking_updated' => [
                'title' => 'Tracking Updated',
                'message' => 'The tracking status for your shipment has been updated.',
            ],
            'revision_required' => [
                'title' => 'Revision Required',
                'message' => 'This request requires correction before approval can continue.',
            ],
            'request_completed' => [
                'title' => 'Request Completed',
                'message' => 'A request has been marked completed and is ready for review.',
            ],
            'request_recompleted' => [
                'title' => 'Request Re-completed',
                'message' => 'A revised request has been completed again after correction.',
            ],
            default => [
                'title' => 'Notification',
                'message' => 'There is an update related to your request.',
            ],
        };
    }
}