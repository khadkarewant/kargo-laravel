<?php

namespace Tests\Feature\Manager;

use App\Models\ServiceRequest;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_approving_a_request_creates_notification_for_customer_and_employees(): void
    {
        $manager = User::factory()->manager()->create();
        $employeeA = User::factory()->employee()->create();
        $employeeB = User::factory()->employee()->create();
        $customer = User::factory()->customer()->create();

        $serviceRequest = ServiceRequest::factory()->completed()->create([
            'user_id' => $customer->id,
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($manager)
            ->patch(route('manager.requests.approve', $serviceRequest));

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'service_request_id' => $serviceRequest->id,
            'type' => NotificationService::TYPE_REQUEST_APPROVED,
            'title' => 'Request Approved',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $employeeA->id,
            'service_request_id' => $serviceRequest->id,
            'type' => NotificationService::TYPE_REQUEST_APPROVED,
            'title' => 'Request Approved',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $employeeB->id,
            'service_request_id' => $serviceRequest->id,
            'type' => NotificationService::TYPE_REQUEST_APPROVED,
            'title' => 'Request Approved',
        ]);
    }

    public function test_marking_revision_required_creates_notifications_for_employees(): void
    {
        $manager = User::factory()->manager()->create();
        $employeeA = User::factory()->employee()->create();
        $employeeB = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->completed()->create([
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($manager)
            ->patch(route('manager.requests.markRevisionRequired', $serviceRequest), [
                'manager_note' => 'Please correct the shipment details.',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $employeeA->id,
            'service_request_id' => $serviceRequest->id,
            'type' => NotificationService::TYPE_REVISION_REQUIRED,
            'title' => 'Revision Required',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $employeeB->id,
            'service_request_id' => $serviceRequest->id,
            'type' => NotificationService::TYPE_REVISION_REQUIRED,
            'title' => 'Revision Required',
        ]);
    }
}