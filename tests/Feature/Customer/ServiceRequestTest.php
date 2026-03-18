<?php

namespace Tests\Feature\Customer;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_their_request_list(): void
    {
        $customer = User::factory()->customer()->create();

        $ownRequestA = ServiceRequest::factory()->create([
            'user_id' => $customer->id,
            'sender_name' => 'Alice Sender',
        ]);

        $ownRequestB = ServiceRequest::factory()->create([
            'user_id' => $customer->id,
            'sender_name' => 'Bob Sender',
        ]);

        $otherCustomerRequest = ServiceRequest::factory()->create([
            'user_id' => User::factory()->customer()->create()->id,
            'sender_name' => 'Hidden Sender',
        ]);

        $response = $this
            ->actingAs($customer)
            ->get(route('requests.index'));

        $response->assertOk();
        $response->assertSee($ownRequestA->tracking_id);
        $response->assertSee($ownRequestB->tracking_id);
        $response->assertDontSee($otherCustomerRequest->tracking_id);
    }

    public function test_customer_can_view_their_own_request(): void
    {
        $customer = User::factory()->customer()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'user_id' => $customer->id,
        ]);

        $response = $this
            ->actingAs($customer)
            ->get(route('requests.show', $serviceRequest));

        $response->assertOk();
        $response->assertSee($serviceRequest->tracking_id);
    }

    public function test_customer_can_create_a_request(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this
            ->actingAs($customer)
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

        $response->assertRedirect(route('requests.index'));

        $this->assertDatabaseHas('service_requests', [
            'user_id' => $customer->id,
            'service_type' => ServiceRequest::SERVICE_IMPORT,
            'sender_name' => 'Asraf Ansari',
            'receiver_name' => 'John Doe',
            'status' => ServiceRequest::STATUS_REQUEST,
        ]);
    }
}