<?php

namespace Tests\Feature;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_customer_request_pages(): void
    {
        $serviceRequest = ServiceRequest::factory()->create();

        $this->get(route('requests.index'))->assertRedirect(route('login'));
        $this->get(route('requests.create'))->assertRedirect(route('login'));
        $this->get(route('requests.show', $serviceRequest))->assertRedirect(route('login'));
        $this->post(route('requests.store'), [])->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_employee_routes(): void
    {
        $serviceRequest = ServiceRequest::factory()->create();

        $this->get(route('employee.requests.index'))->assertRedirect(route('login'));
        $this->get(route('employee.requests.show', $serviceRequest))->assertRedirect(route('login'));
        $this->patch(route('employee.requests.update', $serviceRequest), [])->assertRedirect(route('login'));
        $this->patch(route('employee.requests.updateStatus', $serviceRequest), [])->assertRedirect(route('login'));
        $this->patch(route('employee.requests.updateTrackingStatus', $serviceRequest), [])->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_manager_routes(): void
    {
        $serviceRequest = ServiceRequest::factory()->create();

        $this->get(route('manager.requests.index'))->assertRedirect(route('login'));
        $this->get(route('manager.requests.show', $serviceRequest))->assertRedirect(route('login'));
        $this->get(route('manager.requests.trashed'))->assertRedirect(route('login'));
        $this->patch(route('manager.requests.approve', $serviceRequest), [])->assertRedirect(route('login'));
        $this->patch(route('manager.requests.markRevisionRequired', $serviceRequest), [])->assertRedirect(route('login'));
        $this->post(route('manager.requests.trash', $serviceRequest), [])->assertRedirect(route('login'));
        $this->post(route('manager.requests.restore', $serviceRequest), [])->assertRedirect(route('login'));
        $this->get(route('manager.staff.index'))->assertRedirect(route('login'));
        $this->get(route('manager.staff.create'))->assertRedirect(route('login'));
        $this->post(route('manager.staff.store'), [])->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_employee_routes(): void
    {
        $customer = User::factory()->customer()->create();
        $serviceRequest = ServiceRequest::factory()->create();

        $this->actingAs($customer)
            ->get(route('employee.requests.index'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('employee.requests.show', $serviceRequest))
            ->assertForbidden();
    }

    public function test_customer_cannot_access_manager_routes(): void
    {
        $customer = User::factory()->customer()->create();
        $serviceRequest = ServiceRequest::factory()->create();

        $this->actingAs($customer)
            ->get(route('manager.requests.index'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('manager.requests.show', $serviceRequest))
            ->assertForbidden();
    }

    public function test_employee_cannot_access_manager_routes(): void
    {
        $employee = User::factory()->employee()->create();
        $serviceRequest = ServiceRequest::factory()->create();

        $this->actingAs($employee)
            ->get(route('manager.requests.index'))
            ->assertForbidden();

        $this->actingAs($employee)
            ->get(route('manager.requests.show', $serviceRequest))
            ->assertForbidden();
    }

    public function test_manager_cannot_access_employee_routes(): void
    {
        $manager = User::factory()->manager()->create();
        $serviceRequest = ServiceRequest::factory()->create();

        $this->actingAs($manager)
            ->get(route('employee.requests.index'))
            ->assertForbidden();

        $this->actingAs($manager)
            ->get(route('employee.requests.show', $serviceRequest))
            ->assertForbidden();
    }
}