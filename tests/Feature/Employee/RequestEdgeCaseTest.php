<?php

namespace Tests\Feature\Employee;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_cannot_update_details_on_approved_request(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->approved()->create([
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->from(route('employee.requests.show', $serviceRequest))
            ->patch(route('employee.requests.update', $serviceRequest), [
                'quantity' => '12 cartons',
            ]);

        $response->assertRedirect(route('employee.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'Employee cannot update this request details.');
    }

    public function test_employee_cannot_update_details_on_completed_request(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->completed()->create([
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->from(route('employee.requests.show', $serviceRequest))
            ->patch(route('employee.requests.update', $serviceRequest), [
                'quantity' => '12 cartons',
            ]);

        $response->assertRedirect(route('employee.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'Employee cannot update this request details.');
    }

    public function test_employee_cannot_update_status_on_approved_request(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->approved()->create([
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->from(route('employee.requests.show', $serviceRequest))
            ->patch(route('employee.requests.updateStatus', $serviceRequest), [
                'status' => ServiceRequest::STATUS_COMPLETED,
            ]);

        $response->assertRedirect(route('employee.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'Employee cannot update this request status.');
    }

    public function test_employee_cannot_update_status_on_trashed_request(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->trashed()->create([
            'status' => ServiceRequest::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($employee)
            ->patch(route('employee.requests.updateStatus', $serviceRequest), [
                'status' => ServiceRequest::STATUS_COMPLETED,
            ]);

        $response->assertNotFound();
    }

    public function test_employee_cannot_update_tracking_on_trashed_request(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->approved()->trashed()->create([
            'service_type' => ServiceRequest::SERVICE_IMPORT,
            'tracking_status' => null,
        ]);

        $response = $this
            ->actingAs($employee)
            ->patch(route('employee.requests.updateTrackingStatus', $serviceRequest), [
                'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
                'note' => 'Should fail.',
            ]);

        $response->assertNotFound();
    }

    public function test_employee_can_move_revision_required_request_back_to_completed_when_required_fields_exist(): void
    {
        $employee = User::factory()->employee()->create();

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

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'status' => ServiceRequest::STATUS_COMPLETED,
        ]);
    }
}