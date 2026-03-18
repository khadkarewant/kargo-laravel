<?php

namespace Tests\Unit\Models;

use App\Models\ServiceRequest;
use PHPUnit\Framework\TestCase;

class ServiceRequestTest extends TestCase
{
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
}