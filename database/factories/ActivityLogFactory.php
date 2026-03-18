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
        return [
            'service_request_id' => ServiceRequest::factory(),
            'user_id' => User::factory(),
            'action' => fake()->randomElement([
                'status_updated',
                'tracking_status_updated',
                'request_approved',
                'revision_required',
            ]),
            'field_changed' => fake()->randomElement([
                'status',
                'tracking_status',
                'manager_note',
            ]),
            'old_value' => fake()->word(),
            'new_value' => fake()->word(),
            'description' => fake()->sentence(),
        ];
    }
}