<?php

namespace Tests\Feature\Employee;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_update_tracking_status_for_approved_request(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->approved()->create([
            'service_type' => ServiceRequest::SERVICE_IMPORT,
            'tracking_status' => null,
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->patch(route('employee.requests.updateTrackingStatus', $serviceRequest), [
                'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
                'note' => 'Package received at warehouse.',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
        ]);

        $this->assertDatabaseHas('tracking_events', [
            'service_request_id' => $serviceRequest->id,
            'updated_by' => $employee->id,
            'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
            'note' => 'Package received at warehouse.',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'service_request_id' => $serviceRequest->id,
            'user_id' => $employee->id,
            'action' => 'tracking_status_updated',
            'old_value' => null,
            'new_value' => ServiceRequest::TRACKING_WAREHOUSE,
        ]);
    }

    public function test_employee_cannot_update_tracking_status_for_non_approved_request(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'status' => ServiceRequest::STATUS_PENDING,
            'tracking_status' => null,
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->from(route('employee.requests.show', $serviceRequest))
            ->patch(route('employee.requests.updateTrackingStatus', $serviceRequest), [
                'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
                'note' => 'Should fail.',
            ]);

        $response->assertRedirect(route('employee.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'Employee cannot update tracking status for this request.');
    }

    public function test_employee_cannot_skip_tracking_sequence(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->approved()->create([
            'service_type' => ServiceRequest::SERVICE_IMPORT,
            'tracking_status' => null,
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->from(route('employee.requests.show', $serviceRequest))
            ->patch(route('employee.requests.updateTrackingStatus', $serviceRequest), [
                'tracking_status' => ServiceRequest::TRACKING_CUSTOMS,
                'note' => 'Skipping first step.',
            ]);

        $response->assertRedirect(route('employee.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'Invalid next tracking status.');

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'tracking_status' => null,
        ]);
    }
}