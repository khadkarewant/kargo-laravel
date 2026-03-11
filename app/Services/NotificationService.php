<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    public const TYPE_REQUEST_SUBMITTED = 'request_submitted';
    public const TYPE_REQUEST_APPROVED = 'request_approved';
    public const TYPE_TRACKING_UPDATED = 'tracking_updated';
    public const TYPE_REVISION_REQUIRED = 'revision_required';
    public const TYPE_REQUEST_COMPLETED = 'request_completed';
    public const TYPE_REQUEST_RECOMPLETED = 'request_recompleted';

    public function notifyRequestSubmitted(ServiceRequest $serviceRequest): void
    {
        $managers = User::where('role', 'manager')->get();
        $employees = User::where('role', 'employee')->get();

        $recipients = $managers->concat($employees)->unique('id')->values();

        $this->createForUsers(
            $recipients,
            self::TYPE_REQUEST_SUBMITTED,
            'New Request Submitted',
            "A new request {$serviceRequest->tracking_id} has been submitted.",
            $serviceRequest
        );
    }

    public function notifyRequestApproved(ServiceRequest $serviceRequest): void
    {
        $employees = User::where('role', 'employee')->get();

        $this->createForUser(
            $serviceRequest->user,
            self::TYPE_REQUEST_APPROVED,
            'Request Approved',
            "Your request {$serviceRequest->tracking_id} has been approved.",
            $serviceRequest
        );

        $this->createForUsers(
            $employees,
            self::TYPE_REQUEST_APPROVED,
            'Request Approved',
            "Request {$serviceRequest->tracking_id} has been approved. You can now begin tracking updates.",
            $serviceRequest
        );
    }

    public function notifyTrackingUpdated(ServiceRequest $serviceRequest): void
    {
        $this->createForUser(
            $serviceRequest->user,
            self::TYPE_TRACKING_UPDATED,
            'Tracking Updated',
            "Tracking for request {$serviceRequest->tracking_id} has been updated.",
            $serviceRequest
        );
    }

    public function notifyRevisionRequired(ServiceRequest $serviceRequest): void
    {
        $employees = User::where('role', 'employee')->get();

        $this->createForUsers(
            $employees,
            self::TYPE_REVISION_REQUIRED,
            'Revision Required',
            "Request {$serviceRequest->tracking_id} requires revision.",
            $serviceRequest
        );
    }

    public function notifyRequestCompleted(ServiceRequest $serviceRequest): void
    {
        $managers = User::where('role', 'manager')->get();

        $this->createForUsers(
            $managers,
            self::TYPE_REQUEST_COMPLETED,
            'Request Completed',
            "Request {$serviceRequest->tracking_id} has been marked completed.",
            $serviceRequest
        );
    }

    public function notifyRequestRecompleted(ServiceRequest $serviceRequest): void
    {
        $managers = User::where('role', 'manager')->get();

        $this->createForUsers(
            $managers,
            self::TYPE_REQUEST_RECOMPLETED,
            'Request Re-completed',
            "Request {$serviceRequest->tracking_id} has been completed again after revision.",
            $serviceRequest
        );
    }

    protected function createForUser(
        User $user,
        string $type,
        string $title,
        string $message,
        ServiceRequest $serviceRequest
    ): void {
        Notification::create([
            'user_id' => $user->id,
            'service_request_id' => $serviceRequest->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }

    protected function createForUsers(
        Collection $users,
        string $type,
        string $title,
        string $message,
        ServiceRequest $serviceRequest
    ): void {
        foreach ($users as $user) {
            $this->createForUser($user, $type, $title, $message, $serviceRequest);
        }
    }
}