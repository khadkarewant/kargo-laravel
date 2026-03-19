<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $employees = User::where('role', 'employee')->get();
        $manager = User::where('role', 'manager')->first();

        if ($customers->isEmpty() || $employees->isEmpty() || ! $manager) {
            return;
        }

        foreach ($customers as $customer) {
            ServiceRequest::factory()->create([
                'user_id' => $customer->id,
            ]);

            $pendingRequest = ServiceRequest::factory()->pending()->create([
                'user_id' => $customer->id,
                'processed_by' => $employees->random()->id,
            ]);
            $this->seedPendingLogs($pendingRequest);

            $completedRequest = ServiceRequest::factory()->completed()->create([
                'user_id' => $customer->id,
                'processed_by' => $employees->random()->id,
            ]);
            $this->seedCompletedLogs($completedRequest);

            $approvedRequest = ServiceRequest::factory()->approved()->create([
                'user_id' => $customer->id,
                'processed_by' => $employees->random()->id,
            ]);
            $this->seedApprovedLogs($approvedRequest, $manager);

            $revisionRequest = ServiceRequest::factory()->revisionRequired()->create([
                'user_id' => $customer->id,
                'processed_by' => $employees->random()->id,
            ]);
            $this->seedRevisionRequiredLogs($revisionRequest, $manager);
        }

        for ($i = 0; $i < 4; $i++) {
            $approvedRequest = ServiceRequest::factory()->approved()->create([
                'user_id' => $customers->random()->id,
                'processed_by' => $employees->random()->id,
            ]);
            $this->seedApprovedLogs($approvedRequest, $manager);
        }

        for ($i = 0; $i < 2; $i++) {
            $trashedRequest = ServiceRequest::factory()->trashed()->create([
                'user_id' => $customers->random()->id,
                'processed_by' => $employees->random()->id,
                'trashed_by' => $manager->id,
            ]);
            $this->seedTrashedLogs($trashedRequest, $manager);
        }
    }

    protected function seedPendingLogs(ServiceRequest $serviceRequest): void
    {
        ActivityLog::factory()
            ->statusUpdated(ServiceRequest::STATUS_REQUEST, ServiceRequest::STATUS_PENDING)
            ->create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => $serviceRequest->processed_by,
            ]);
    }

    protected function seedCompletedLogs(ServiceRequest $serviceRequest): void
    {
        ActivityLog::factory()
            ->statusUpdated(ServiceRequest::STATUS_REQUEST, ServiceRequest::STATUS_PENDING)
            ->create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => $serviceRequest->processed_by,
            ]);

        ActivityLog::factory()
            ->statusUpdated(ServiceRequest::STATUS_PENDING, ServiceRequest::STATUS_COMPLETED)
            ->create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => $serviceRequest->processed_by,
            ]);
    }

    protected function seedApprovedLogs(ServiceRequest $serviceRequest, User $manager): void
    {
        $this->seedCompletedLogs($serviceRequest);

        ActivityLog::factory()
            ->approved()
            ->create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => $manager->id,
            ]);
    }

    protected function seedRevisionRequiredLogs(ServiceRequest $serviceRequest, User $manager): void
    {
        $this->seedCompletedLogs($serviceRequest);

        ActivityLog::factory()
            ->revisionRequired()
            ->create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => $manager->id,
            ]);
    }

    protected function seedTrashedLogs(ServiceRequest $serviceRequest, User $manager): void
    {
        ActivityLog::factory()
            ->statusUpdated(ServiceRequest::STATUS_REQUEST, ServiceRequest::STATUS_PENDING)
            ->create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => $serviceRequest->processed_by,
            ]);

        ActivityLog::factory()
            ->create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => $manager->id,
                'action' => 'trashed',
                'field_changed' => 'is_trashed',
                'old_value' => '0',
                'new_value' => '1',
                'description' => 'Manager moved request to trash.',
            ]);
    }
}