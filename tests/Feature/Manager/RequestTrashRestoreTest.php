<?php

namespace Tests\Feature\Manager;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestTrashRestoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_trash_a_request(): void
    {
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'is_trashed' => false,
            'trashed_at' => null,
            'trashed_by' => null,
            'trash_reason' => null,
        ]);

        $response = $this
            ->actingAs($manager)
            ->post(route('manager.requests.trash', $serviceRequest), [
                'trash_reason' => 'Duplicate submission.',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'is_trashed' => true,
            'trashed_by' => $manager->id,
            'trash_reason' => 'Duplicate submission.',
        ]);
    }

    public function test_manager_can_view_trashed_requests(): void
    {
        $manager = User::factory()->manager()->create();

        $trashedRequest = ServiceRequest::factory()->trashed()->create();
        $activeRequest = ServiceRequest::factory()->create(['is_trashed' => false]);

        $response = $this
            ->actingAs($manager)
            ->get(route('manager.requests.trashed'));

        $response->assertOk();
        $response->assertSee($trashedRequest->tracking_id);
        $response->assertDontSee($activeRequest->tracking_id);
    }

    public function test_manager_can_restore_a_trashed_request(): void
    {
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->trashed()->create();

        $response = $this
            ->actingAs($manager)
            ->post(route('manager.requests.restore', $serviceRequest));

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'is_trashed' => false,
            'trashed_at' => null,
            'trashed_by' => null,
            'trash_reason' => null,
        ]);
    }
}