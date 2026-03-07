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
        // block non-employees
        if (!auth()->user()->isEmployee()) {
            abort(403);
        }

        $serviceRequests = ServiceRequest::with(['trackingEvents.updater'])->whereIn('status', [
            ServiceRequest::STATUS_REQUEST,
            ServiceRequest::STATUS_PENDING,
            ServiceRequest::STATUS_COMPLETED,
            ServiceRequest::STATUS_APPROVED,
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

        $serviceRequest->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Request status updated successfully.');
    }

    public function updateTrackingStatus(Request $request, ServiceRequest $serviceRequest)
    {
        if (! auth()->user()->isEmployee()) {
            abort(403);
        }

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

        DB::transaction(function () use ($serviceRequest, $validated) {
            $serviceRequest->update([
                'tracking_status' => $validated['tracking_status'],
            ]);

            $serviceRequest->trackingEvents()->create([
                'updated_by' => auth()->id(),
                'tracking_status' => $validated['tracking_status'],
                'note' => $validated['note'] ?? null,
            ]);
            
        });
        
        return back()->with('success', 'Tracking status updated successfully.');
    }

}