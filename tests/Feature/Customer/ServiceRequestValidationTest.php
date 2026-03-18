<?php

namespace Tests\Feature\Customer;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRequestValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_submit_request_with_missing_required_fields(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this
            ->actingAs($customer)
            ->from(route('requests.create'))
            ->post(route('requests.store'), []);

        $response->assertRedirect(route('requests.create'));
        $response->assertSessionHasErrors([
            'service_type',
            'sender_name',
            'sender_country',
            'sender_contact',
            'receiver_name',
            'receiver_country',
            'receiver_contact',
            'notes',
        ]);
    }

    public function test_customer_cannot_submit_request_with_invalid_service_type(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this
            ->actingAs($customer)
            ->from(route('requests.create'))
            ->post(route('requests.store'), [
                'service_type' => 'invalid_type',
                'sender_name' => 'Asraf Ansari',
                'sender_country' => 'Nepal',
                'sender_contact' => '9800000000',
                'receiver_name' => 'John Doe',
                'receiver_country' => 'UAE',
                'receiver_contact' => '971500000000',
                'notes' => 'Handle with care.',
            ]);

        $response->assertRedirect(route('requests.create'));
        $response->assertSessionHasErrors('service_type');
    }

    public function test_employee_cannot_submit_customer_request(): void
    {
        $employee = User::factory()->employee()->create();

        $response = $this
            ->actingAs($employee)
            ->post(route('requests.store'), [
                'service_type' => ServiceRequest::SERVICE_IMPORT,
                'sender_name' => 'Asraf Ansari',
                'sender_country' => 'Nepal',
                'sender_contact' => '9800000000',
                'receiver_name' => 'John Doe',
                'receiver_country' => 'UAE',
                'receiver_contact' => '971500000000',
                'notes' => 'Handle with care.',
            ]);

        $response->assertForbidden();
    }

    public function test_manager_cannot_submit_customer_request(): void
    {
        $manager = User::factory()->manager()->create();

        $response = $this
            ->actingAs($manager)
            ->post(route('requests.store'), [
                'service_type' => ServiceRequest::SERVICE_IMPORT,
                'sender_name' => 'Asraf Ansari',
                'sender_country' => 'Nepal',
                'sender_contact' => '9800000000',
                'receiver_name' => 'John Doe',
                'receiver_country' => 'UAE',
                'receiver_contact' => '971500000000',
                'notes' => 'Handle with care.',
            ]);

        $response->assertForbidden();
    }
}