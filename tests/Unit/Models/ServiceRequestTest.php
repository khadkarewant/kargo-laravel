<?php

namespace Tests\Unit\Models;

use App\Models\ServiceRequest;
use PHPUnit\Framework\TestCase;

class ServiceRequestTest extends TestCase
{
    public function test_can_employee_update_status_returns_true_for_allowed_statuses(): void
    {
        $requestStatuses = [
            ServiceRequest::STATUS_REQUEST,
            ServiceRequest::STATUS_PENDING,
            ServiceRequest::STATUS_REVISION_REQUIRED,
        ];

        foreach ($requestStatuses as $status) {
            $serviceRequest = new ServiceRequest([
                'status' => $status,
            ]);

            $this->assertTrue($serviceRequest->canEmployeeUpdateStatus());
        }
    }

    public function test_can_employee_update_status_returns_false_for_disallowed_statuses(): void
    {
        $requestStatuses = [
            ServiceRequest::STATUS_COMPLETED,
            ServiceRequest::STATUS_APPROVED,
        ];

        foreach ($requestStatuses as $status) {
            $serviceRequest = new ServiceRequest([
                'status' => $status,
            ]);

            $this->assertFalse($serviceRequest->canEmployeeUpdateStatus());
        }
    }

    public function test_can_employee_update_details_returns_true_for_allowed_statuses(): void
    {
        $requestStatuses = [
            ServiceRequest::STATUS_REQUEST,
            ServiceRequest::STATUS_PENDING,
            ServiceRequest::STATUS_REVISION_REQUIRED,
        ];

        foreach ($requestStatuses as $status) {
            $serviceRequest = new ServiceRequest([
                'status' => $status,
            ]);

            $this->assertTrue($serviceRequest->canEmployeeUpdateDetails());
        }
    }

    public function test_can_employee_update_details_returns_false_for_disallowed_statuses(): void
    {
        $requestStatuses = [
            ServiceRequest::STATUS_COMPLETED,
            ServiceRequest::STATUS_APPROVED,
        ];

        foreach ($requestStatuses as $status) {
            $serviceRequest = new ServiceRequest([
                'status' => $status,
            ]);

            $this->assertFalse($serviceRequest->canEmployeeUpdateDetails());
        }
    }

    public function test_can_employee_update_tracking_status_returns_true_only_for_approved_requests(): void
    {
        $approvedRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_APPROVED,
        ]);

        $pendingRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_PENDING,
        ]);

        $completedRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_COMPLETED,
        ]);

        $this->assertTrue($approvedRequest->canEmployeeUpdateTrackingStatus());
        $this->assertFalse($pendingRequest->canEmployeeUpdateTrackingStatus());
        $this->assertFalse($completedRequest->canEmployeeUpdateTrackingStatus());
    }

    public function test_can_manager_approve_returns_true_only_for_completed_requests(): void
    {
        $completedRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_COMPLETED,
        ]);

        $pendingRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_PENDING,
        ]);

        $approvedRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_APPROVED,
        ]);

        $this->assertTrue($completedRequest->canManagerApprove());
        $this->assertFalse($pendingRequest->canManagerApprove());
        $this->assertFalse($approvedRequest->canManagerApprove());
    }

    public function test_can_manager_mark_revision_required_returns_true_only_for_completed_requests(): void
    {
        $completedRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_COMPLETED,
        ]);

        $pendingRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_PENDING,
        ]);

        $approvedRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_APPROVED,
        ]);

        $this->assertTrue($completedRequest->canManagerMarkRevisionRequired());
        $this->assertFalse($pendingRequest->canManagerMarkRevisionRequired());
        $this->assertFalse($approvedRequest->canManagerMarkRevisionRequired());
    }

    public function test_is_revision_required_returns_true_only_for_revision_required_status(): void
    {
        $revisionRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_REVISION_REQUIRED,
        ]);

        $pendingRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_PENDING,
        ]);

        $this->assertTrue($revisionRequest->isRevisionRequired());
        $this->assertFalse($pendingRequest->isRevisionRequired());
    }

    public function test_is_trashed_returns_true_when_request_is_marked_trashed(): void
    {
        $trashedRequest = new ServiceRequest([
            'is_trashed' => true,
        ]);

        $activeRequest = new ServiceRequest([
            'is_trashed' => false,
        ]);

        $this->assertTrue($trashedRequest->isTrashed());
        $this->assertFalse($activeRequest->isTrashed());
    }

    public function test_can_be_worked_on_returns_false_for_trashed_request(): void
    {
        $trashedRequest = new ServiceRequest([
            'is_trashed' => true,
        ]);

        $activeRequest = new ServiceRequest([
            'is_trashed' => false,
        ]);

        $this->assertFalse($trashedRequest->canBeWorkedOn());
        $this->assertTrue($activeRequest->canBeWorkedOn());
    }

    public function test_next_employee_status_from_request_is_pending(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_REQUEST,
        ]);

        $this->assertSame(
            ServiceRequest::STATUS_PENDING,
            $serviceRequest->nextEmployeeStatus()
        );
    }

    public function test_next_employee_status_from_pending_is_completed(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_PENDING,
        ]);

        $this->assertSame(
            ServiceRequest::STATUS_COMPLETED,
            $serviceRequest->nextEmployeeStatus()
        );
    }

    public function test_next_employee_status_from_revision_required_is_completed(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_REVISION_REQUIRED,
        ]);

        $this->assertSame(
            ServiceRequest::STATUS_COMPLETED,
            $serviceRequest->nextEmployeeStatus()
        );
    }

    public function test_next_employee_status_returns_null_for_approved(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_APPROVED,
        ]);

        $this->assertNull($serviceRequest->nextEmployeeStatus());
    }

    public function test_import_flow_starts_at_warehouse(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_APPROVED,
            'service_type' => ServiceRequest::SERVICE_IMPORT,
            'tracking_status' => null,
        ]);

        $this->assertSame(
            ServiceRequest::TRACKING_WAREHOUSE,
            $serviceRequest->nextTrackingStatus()
        );
    }

    public function test_import_flow_moves_from_warehouse_to_customs(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_APPROVED,
            'service_type' => ServiceRequest::SERVICE_IMPORT,
            'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
        ]);

        $this->assertSame(
            ServiceRequest::TRACKING_CUSTOMS,
            $serviceRequest->nextTrackingStatus()
        );
    }

    public function test_export_flow_starts_at_office(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_APPROVED,
            'service_type' => ServiceRequest::SERVICE_EXPORT,
            'tracking_status' => null,
        ]);

        $this->assertSame(
            ServiceRequest::TRACKING_OFFICE,
            $serviceRequest->nextTrackingStatus()
        );
    }

    public function test_export_flow_moves_from_office_to_customs(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_APPROVED,
            'service_type' => ServiceRequest::SERVICE_EXPORT,
            'tracking_status' => ServiceRequest::TRACKING_OFFICE,
        ]);

        $this->assertSame(
            ServiceRequest::TRACKING_CUSTOMS,
            $serviceRequest->nextTrackingStatus()
        );
    }

    public function test_tracking_status_cannot_advance_when_request_is_not_approved(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_PENDING,
            'service_type' => ServiceRequest::SERVICE_IMPORT,
            'tracking_status' => null,
        ]);

        $this->assertNull($serviceRequest->nextTrackingStatus());
    }

    public function test_can_move_to_tracking_status_matches_only_the_next_step(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_APPROVED,
            'service_type' => ServiceRequest::SERVICE_IMPORT,
            'tracking_status' => null,
        ]);

        $this->assertTrue(
            $serviceRequest->canMoveToTrackingStatus(ServiceRequest::TRACKING_WAREHOUSE)
        );

        $this->assertFalse(
            $serviceRequest->canMoveToTrackingStatus(ServiceRequest::TRACKING_CUSTOMS)
        );
    }

    public function test_status_label_accessor_formats_status(): void
    {
        $serviceRequest = new ServiceRequest([
            'status' => ServiceRequest::STATUS_REVISION_REQUIRED,
        ]);

        $this->assertSame('Revision Required', $serviceRequest->status_label);
    }

    public function test_tracking_status_label_accessor_returns_not_started_when_null(): void
    {
        $serviceRequest = new ServiceRequest([
            'tracking_status' => null,
        ]);

        $this->assertSame('Not started', $serviceRequest->tracking_status_label);
    }

    public function test_tracking_status_label_accessor_formats_tracking_status(): void
    {
        $serviceRequest = new ServiceRequest([
            'tracking_status' => ServiceRequest::TRACKING_WAREHOUSE,
        ]);

        $this->assertSame('Warehouse', $serviceRequest->tracking_status_label);
    }

    public function test_service_type_label_accessor_formats_service_type(): void
    {
        $serviceRequest = new ServiceRequest([
            'service_type' => ServiceRequest::SERVICE_CLEARANCE,
        ]);

        $this->assertSame('Clearance', $serviceRequest->service_type_label);
    }
}