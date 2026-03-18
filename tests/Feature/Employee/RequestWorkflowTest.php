<?php

namespace Tests\Feature\Employee;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_update_request_details(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'status' => ServiceRequest::STATUS_REQUEST,
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->patch(route('employee.requests.update', $serviceRequest), [
                'quantity' => '12 cartons',
                'product_detail' => 'Mobile accessories',
                'weight' => '45 kg',
                'dimension' => '120x80x60 cm',
                'employee_note' => 'Packed and checked.',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'quantity' => '12 cartons',
            'product_detail' => 'Mobile accessories',
            'weight' => '45 kg',
            'dimension' => '120x80x60 cm',
            'employee_note' => 'Packed and checked.',
        ]);
    }

    public function test_employee_cannot_update_a_trashed_request(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->trashed()->create();

        $response = $this
            ->actingAs($employee)
            ->patch(route('employee.requests.update', $serviceRequest), [
                'quantity' => '12 cartons',
            ]);

        $response->assertNotFound();
    }

    public function test_employee_can_move_request_from_request_to_pending(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'status' => ServiceRequest::STATUS_REQUEST,
            'processed_by' => null,
            'processed_at' => null,
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->patch(route('employee.requests.updateStatus', $serviceRequest), [
                'status' => ServiceRequest::STATUS_PENDING,
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'status' => ServiceRequest::STATUS_PENDING,
            'processed_by' => $employee->id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'service_request_id' => $serviceRequest->id,
            'user_id' => $employee->id,
            'action' => 'status_updated',
            'old_value' => ServiceRequest::STATUS_REQUEST,
            'new_value' => ServiceRequest::STATUS_PENDING,
        ]);
    }

    public function test_employee_cannot_skip_directly_from_request_to_completed(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'status' => ServiceRequest::STATUS_REQUEST,
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->from(route('employee.requests.show', $serviceRequest))
            ->patch(route('employee.requests.updateStatus', $serviceRequest), [
                'status' => ServiceRequest::STATUS_COMPLETED,
            ]);

        $response->assertRedirect(route('employee.requests.show', $serviceRequest));
        $response->assertSessionHas('error', 'Invalid next status.');

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'status' => ServiceRequest::STATUS_REQUEST,
        ]);
    }

    public function test_employee_cannot_mark_completed_without_required_processing_fields(): void
    {
        $employee = User::factory()->employee()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'status' => ServiceRequest::STATUS_PENDING,
            'quantity' => null,
            'product_detail' => null,
            'weight' => null,
            'dimension' => null,
            'is_trashed' => false,
        ]);

        $response = $this
            ->actingAs($employee)
            ->from(route('employee.requests.show', $serviceRequest))
            ->patch(route('employee.requests.updateStatus', $serviceRequest), [
                'status' => ServiceRequest::STATUS_COMPLETED,
            ]);

        $response->assertRedirect(route('employee.requests.show', $serviceRequest));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('service_requests', [
            'id' => $serviceRequest->id,
            'status' => ServiceRequest::STATUS_PENDING,
        ]);
    }
}