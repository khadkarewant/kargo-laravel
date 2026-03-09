<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    public function view(User $user, ServiceRequest $serviceRequest): bool
    {
        return $this->ownsRequest($user, $serviceRequest);
    }

    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    public function update(User $user, ServiceRequest $serviceRequest): bool
    {
        return $this->ownsRequest($user, $serviceRequest);
    }

    public function delete(User $user, ServiceRequest $serviceRequest): bool
    {
        return $this->ownsRequest($user, $serviceRequest);
    }

    private function ownsRequest(User $user, ServiceRequest $serviceRequest): bool
    {
        return $serviceRequest->user_id === $user->id;
    }
}