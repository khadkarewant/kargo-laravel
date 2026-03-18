<?php

namespace Tests\Feature\Manager;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_approve_a_completed_request(): void
    {
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->completed()->create([
            'is_trashed' => false,
            'manager_note' => 'Old note',
        ]);

        $response = $this
            ->actingAs($manager)
            ->patch(route('manager.requests.approve', $serviceRequest));

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'status' => ServiceRequest::STATUS_APPROVED,
            'manager_note' => null,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'service_request_id' => $serviceRequest->id,
            'user_id' => $manager->id,
            'action' => ServiceRequest::ACTION_REQUEST_APPROVED,
            'old_value' => ServiceRequest::STATUS_COMPLETED,
            'new_value' => ServiceRequest::STATUS_APPROVED,
        ]);
    }

    public function test_manager_cannot_approve_a_non_completed_request(): void
    {
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'status' => ServiceRequest::STATUS_PENDING,
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($manager)
            ->from(route('manager.requests.show', $serviceRequest))
            ->patch(route('manager.requests.approve', $serviceRequest));

        $response->assertRedirect(route('manager.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'This request cannot be approved.');

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'status' => ServiceRequest::STATUS_PENDING,
        ]);
    }

    public function test_manager_can_mark_revision_required_for_completed_request(): void
    {
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->completed()->create([
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($manager)
            ->patch(route('manager.requests.markRevisionRequired', $serviceRequest), [
                'manager_note' => 'Please fix the shipment dimensions.',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'status' => ServiceRequest::STATUS_REVISION_REQUIRED,
            'manager_note' => 'Please fix the shipment dimensions.',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'service_request_id' => $serviceRequest->id,
            'user_id' => $manager->id,
            'action' => ServiceRequest::ACTION_REVISION_REQUIRED,
            'old_value' => ServiceRequest::STATUS_COMPLETED,
            'new_value' => ServiceRequest::STATUS_REVISION_REQUIRED,
        ]);
    }
}