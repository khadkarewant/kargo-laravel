<?php

namespace Tests\Feature;

use App\Models\ServiceRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_lookup_tracking_by_valid_tracking_id(): void
    {
        $serviceRequest = ServiceRequest::factory()->approved()->create([
            'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
        ]);

        $response = $this->get(route('tracking.show', [
            'tracking_id' => $serviceRequest->tracking_id,
        ]));

        $response->assertOk();
        $response->assertSee($serviceRequest->tracking_id);
    }

    public function test_public_user_gets_validation_error_when_tracking_id_is_missing(): void
    {
        $response = $this->get(route('tracking.show'));

        $response->assertSessionHasErrors('tracking_id');
    }

    public function test_public_user_is_redirected_when_tracking_id_is_not_found(): void
    {
        $response = $this
            ->from('/')
            ->get(route('tracking.show', [
                'tracking_id' => 'KRG-2099-99999',
            ]));

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('tracking_id');
    }
}