<?php

namespace Tests\Feature\Customer;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRequestOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_view_another_customers_request(): void
    {
        $owner = User::factory()->customer()->create();
        $otherCustomer = User::factory()->customer()->create();

        $serviceRequest = ServiceRequest::factory()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this
            ->actingAs($otherCustomer)
            ->get(route('requests.show', $serviceRequest));

        $response->assertForbidden();
    }
}