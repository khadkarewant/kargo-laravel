<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        $serviceRequests = ServiceRequest::with(['trackingEvents.updater', 'activityLogs.user'])->whereIn('status', [
            ServiceRequest::STATUS_REQUEST,
            ServiceRequest::STATUS_PENDING,
            ServiceRequest::STATUS_COMPLETED,
            ServiceRequest::STATUS_APPROVED,
            ServiceRequest::STATUS_REVISION_REQUIRED,
        ])->latest()->get();

        return view('employee.requests.index', compact('serviceRequests'));
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        if (! $serviceRequest->canEmployeeUpdateStatus()) {
            return back()->with('error', 'Employee cannot update this request status.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string'],
        ]);

        if ($validated['status'] !== $serviceRequest->nextEmployeeStatus()) {
            return back()->with('error', 'Invalid next status.');
        }

        $oldStatus = $serviceRequest->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($serviceRequest, $oldStatus, $newStatus) {
            $serviceRequest->update([
                'status' => $newStatus,
            ]);

            $serviceRequest->activityLogs()->create([
                'user_id' => auth()->id(),
                'action' => 'status_updated',
                'field_changed' => 'status',
                'old_value' => $oldStatus,
                'new_value' => $newStatus,
                'description' => 'Employee updated request status',
            ]);
        });

        return back()->with('success', 'Request status updated successfully.');
    }

    public function updateTrackingStatus(Request $request, ServiceRequest $serviceRequest)
    {
        if (! $serviceRequest->canEmployeeUpdateTrackingStatus()) {
            return back()->with('error', 'Employee cannot update tracking status for this request.');
        }

        $validated = $request->validate([
            'tracking_status' => ['required', 'string'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        if (! $serviceRequest->canMoveToTrackingStatus($validated['tracking_status'])) {
            return back()->with('error', 'Invalid next tracking status.');
        }

        $oldTrackingStatus = $serviceRequest->tracking_status;
        $newTrackingStatus = $validated['tracking_status'];

        DB::transaction(function () use ($serviceRequest, $validated, $oldTrackingStatus, $newTrackingStatus) {
            $serviceRequest->update([
                'tracking_status' => $validated['tracking_status'],
            ]);

            $serviceRequest->trackingEvents()->create([
                'updated_by' => auth()->id(),
                'tracking_status' => $validated['tracking_status'],
                'note' => $validated['note'] ?? null,
            ]);

            $serviceRequest->activityLogs()->create([
                'user_id' => auth()->id(),
                'action' => 'tracking_status_updated',
                'field_changed' => 'tracking_status',
                'old_value' => $oldTrackingStatus,
                'new_value' => $newTrackingStatus,
                'description' => 'Employee updated tracking status',
            ]);
        });

        return back()->with('success', 'Tracking status updated successfully.');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load([
            'trackingEvents.updater',
            'activityLogs.user',
        ]);

        return view('employee.requests.show', compact('serviceRequest'));
    }

}