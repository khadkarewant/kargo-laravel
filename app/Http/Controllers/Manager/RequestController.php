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
        if (! auth()->user()->isAdmin()){
            abort(403);
        }

        $serviceRequests = ServiceRequest::latest()->get();

        return view('manager.requests.index', compact('serviceRequests'));

    }

    public function approve(ServiceRequest $serviceRequest)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

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
                'action' => 'request_approved',
                'field_changed' => 'status',
                'old_value' => $oldStatus,
                'new_value' => $newStatus,
                'description' => 'Manager approved completed request',
            ]);
            
        });

        return back()->with('success', 'Request approved successfully.');

    }
}