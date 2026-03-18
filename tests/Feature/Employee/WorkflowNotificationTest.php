<?php

namespace Tests\Feature\Employee;

use App\Models\ServiceRequest;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_completing_a_request_creates_notifications_for_managers(): void
    {
        $employee = User::factory()->employee()->create();
        $managerA = User::factory()->manager()->create();
        $managerB = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'status' => ServiceRequest::STATUS_PENDING,
            'quantity' => '10 boxes',
            'product_detail' => 'Electronics',
            'weight' => '25 kg',
            'dimension' => '40x30x20 cm',
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->patch(route('employee.requests.updateStatus', $serviceRequest), [
                'status' => ServiceRequest::STATUS_COMPLETED,
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $managerA->id,
            'service_request_id' => $serviceRequest->id,
            'type' => NotificationService::TYPE_REQUEST_COMPLETED,
            'title' => 'Request Completed',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $managerB->id,
            'service_request_id' => $serviceRequest->id,
            'type' => NotificationService::TYPE_REQUEST_COMPLETED,
            'title' => 'Request Completed',
        ]);
    }

    public function test_recompleting_a_revision_required_request_creates_recompleted_notifications_for_managers(): void
    {
        $employee = User::factory()->employee()->create();
        $manager = User::factory()->manager()->create();

        $serviceRequest = ServiceRequest::factory()->revisionRequired()->create([
            'is_trashed' => false,
            'quantity' => '10 boxes',
            'product_detail' => 'Electronics',
            'weight' => '25 kg',
            'dimension' => '40x30x20 cm',
        ]);

        $response = $this
            ->actingAs($employee)
            ->patch(route('employee.requests.updateStatus', $serviceRequest), [
                'status' => ServiceRequest::STATUS_COMPLETED,
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $manager->id,
            'service_request_id' => $serviceRequest->id,
            'type' => NotificationService::TYPE_REQUEST_RECOMPLETED,
            'title' => 'Request Re-completed',
        ]);
    }

    public function test_updating_tracking_status_creates_notification_for_customer(): void
    {
        $employee = User::factory()->employee()->create();
        $customer = User::factory()->customer()->create();

        $serviceRequest = ServiceRequest::factory()->approved()->create([
            'user_id' => $customer->id,
            'service_type' => ServiceRequest::SERVICE_IMPORT,
            'tracking_status' => null,
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->patch(route('employee.requests.updateTrackingStatus', $serviceRequest), [
                'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
                'note' => 'Arrived at warehouse.',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'service_request_id' => $serviceRequest->id,
            'type' => NotificationService::TYPE_TRACKING_UPDATED,
            'title' => 'Tracking Updated',
        ]);
    }
}