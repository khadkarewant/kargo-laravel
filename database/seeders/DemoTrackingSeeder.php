<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\ServiceRequest;
use App\Models\TrackingEvent;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoTrackingSeeder extends Seeder
{
    public function run(): void
    {
        $employees = User::where('role', 'employee')->get();

        if ($employees->isEmpty()) {
            return;
        }

        $approvedRequests = ServiceRequest::where('status', ServiceRequest::STATUS_APPROVED)
            ->where('is_trashed', false)
            ->get();

        foreach ($approvedRequests as $serviceRequest) {
            $employee = $employees->random();

            $statuses = $this->trackingSequenceFor($serviceRequest);

            $lastStatus = null;

            foreach ($statuses as $status) {
                TrackingEvent::factory()
                    ->for($serviceRequest)
                    ->create([
                        'updated_by' => $employee->id,
                        'tracking_status' => $status,
                        'note' => $this->noteForStatus($status),
                    ]);

                ActivityLog::factory()
                    ->trackingUpdated($lastStatus, $status)
                    ->create([
                        'service_request_id' => $serviceRequest->id,
                        'user_id' => $employee->id,
                    ]);

                $lastStatus = $status;
            }

            $serviceRequest->update([
                'tracking_status' => $lastStatus,
            ]);
        }
    }

    protected function trackingSequenceFor(ServiceRequest $serviceRequest): array
    {
        if (in_array($serviceRequest->service_type, ServiceRequest::IN_FLOW_SERVICE_TYPES, true)) {
            return collect([
                ServiceRequest::TRACKING_WAREHOUSE,
                ServiceRequest::TRACKING_CUSTOMS,
                ServiceRequest::TRACKING_OFFICE,
                ServiceRequest::TRACKING_DESTINATION,
            ])->take(rand(1, 4))->values()->all();
        }

        return collect([
            ServiceRequest::TRACKING_OFFICE,
            ServiceRequest::TRACKING_CUSTOMS,
            ServiceRequest::TRACKING_ROUTE,
            ServiceRequest::TRACKING_DESTINATION,
        ])->take(rand(1, 4))->values()->all();
    }

    protected function noteForStatus(string $status): string
    {
        return match ($status) {
            ServiceRequest::TRACKING_WAREHOUSE => 'Shipment received at warehouse and logged for processing.',
            ServiceRequest::TRACKING_CUSTOMS => 'Shipment moved to customs checkpoint for clearance review.',
            ServiceRequest::TRACKING_OFFICE => 'Shipment arrived at office hub for internal handling.',
            ServiceRequest::TRACKING_ROUTE => 'Shipment dispatched and currently in transit to destination.',
            ServiceRequest::TRACKING_DESTINATION => 'Shipment reached final destination and is ready for collection.',
            default => 'Tracking status updated.',
        };
    }
}