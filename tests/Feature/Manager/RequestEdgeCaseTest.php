<?php

namespace Tests\Feature\Manager;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_cannot_mark_revision_required_without_manager_note(): void
    {
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->completed()->create([
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($manager)
            ->from(route('manager.requests.show', $serviceRequest))
            ->patch(route('manager.requests.markRevisionRequired', $serviceRequest), [
                'manager_note' => '',
            ]);

        $response->assertRedirect(route('manager.requests.show', $serviceRequest));
        $response->assertSessionHasErrors('manager_note');

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'status' => ServiceRequest::STATUS_COMPLETED,
        ]);
    }

    public function test_manager_cannot_approve_trashed_request(): void
    {
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->completed()->trashed()->create();

        $response = $this
            ->actingAs($manager)
            ->from(route('manager.requests.show', $serviceRequest))
            ->patch(route('manager.requests.approve', $serviceRequest));

        $response->assertRedirect(route('manager.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'This request is inactive.');

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'status' => ServiceRequest::STATUS_COMPLETED,
            'is_trashed' => true,
        ]);
    }

    public function test_manager_cannot_mark_revision_required_on_trashed_request(): void
    {
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->completed()->trashed()->create();

        $response = $this
            ->actingAs($manager)
            ->from(route('manager.requests.show', $serviceRequest))
            ->patch(route('manager.requests.markRevisionRequired', $serviceRequest), [
                'manager_note' => 'Please revise this.',
            ]);

        $response->assertRedirect(route('manager.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'This request is inactive.');

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'status' => ServiceRequest::STATUS_COMPLETED,
            'is_trashed' => true,
        ]);
    }

    public function test_manager_cannot_restore_a_non_trashed_request(): void
    {
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($manager)
            ->from(route('manager.requests.show', $serviceRequest))
            ->post(route('manager.requests.restore', $serviceRequest));

        $response->assertRedirect(route('manager.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'This request is not inactive.');

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'is_trashed' => false,
        ]);
    }

    public function test_manager_cannot_trash_an_already_trashed_request(): void
    {
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->trashed()->create();

        $response = $this
            ->actingAs($manager)
            ->from(route('manager.requests.show', $serviceRequest))
            ->post(route('manager.requests.trash', $serviceRequest), [
                'trash_reason' => 'Trying again.',
            ]);

        $response->assertRedirect(route('manager.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'This request is already inactive.');

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'is_trashed' => true,
        ]);
    }
}