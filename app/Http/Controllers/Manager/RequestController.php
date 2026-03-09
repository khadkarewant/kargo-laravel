<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        $serviceRequests = ServiceRequest::latest()->get();

        return view('manager.requests.index', compact('serviceRequests'));

    }

    public function approve(ServiceRequest $serviceRequest)
    {
        if (! $serviceRequest->canManagerApprove()) {
            return back()->with('error', 'This request cannot be approved.');
        }

        $oldStatus = $serviceRequest->status;
        $newStatus = ServiceRequest::STATUS_APPROVED;

        DB::transaction(function () use ($serviceRequest, $oldStatus, $newStatus) {
            $serviceRequest->update([
                'status' => ServiceRequest::STATUS_APPROVED,
            ]);

            $serviceRequest->activityLogs()->create([
                'user_id' => auth()->id(),
                'action' => ServiceRequest::ACTION_REQUEST_APPROVED,
                'field_changed' => 'status',
                'old_value' => $oldStatus,
                'new_value' => $newStatus,
                'description' => 'Manager approved request',
            ]);
            
        });

        return back()->with('success', 'Request approved successfully.');

    }

    public function markRevisionRequired(ServiceRequest $serviceRequest)
    {
        if (! $serviceRequest->canManagerMarkRevisionRequired()) {
            return back()->with('error', 'This request cannot be marked as revision required.');
        }

        $oldStatus = $serviceRequest->status;
        $newStatus = ServiceRequest::STATUS_REVISION_REQUIRED;

        DB::transaction(function () use ($serviceRequest, $oldStatus, $newStatus) {
            $serviceRequest->update([
                'status' => ServiceRequest::STATUS_REVISION_REQUIRED,
            ]);

            $serviceRequest->activityLogs()->create([
            'user_id' => auth()->id(),
            'action' => ServiceRequest::ACTION_REVISION_REQUIRED,
            'field_changed' => 'status',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'description' => 'Manager marked approved request as revision required',
            ]);
        });
        return back()->with('success', 'Request marked as revision required.');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load([
            'trackingEvents.updater',
            'activityLogs.user',
        ]);

        return view('manager.requests.show', compact('serviceRequest'));
    }
}