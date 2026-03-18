<?php

namespace Tests\Feature\Models;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRequestsTrackingIdTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_id_is_generated_when_service_request_is_created(): void
    {
        $customer = User::factory()->customer()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'user_id' => $customer->id,
        ]);

        $this->assertNotNull($serviceRequest->tracking_id);
        $this->assertMatchesRegularExpression(
            '/^KRG-\d{4}-\d{5}$/',
            $serviceRequest->tracking_id
        );
    }

    public function test_tracking_id_increments_for_multiple_requests_in_same_year(): void
    {
        $customer = User::factory()->customer()->create();

        $first = ServiceRequest::factory()->create([
            'user_id' => $customer->id,
        ]);

        $second = ServiceRequest::factory()->create([
            'user_id' => $customer->id,
        ]);

        $this->assertNotSame($first->tracking_id, $second->tracking_id);
        $this->assertStringEndsWith('00001', $first->tracking_id);
        $this->assertStringEndsWith('00002', $second->tracking_id);
    }
}