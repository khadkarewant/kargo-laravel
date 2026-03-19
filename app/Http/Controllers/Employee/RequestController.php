<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $serviceRequests = ServiceRequest::with([
                'customer',
                'trackingEvents.updater',
                'activityLogs.user',
            ])
            ->active()
            ->whereIn('status', [
                ServiceRequest::STATUS_REQUEST,
                ServiceRequest::STATUS_PENDING,
                ServiceRequest::STATUS_COMPLETED,
                ServiceRequest::STATUS_APPROVED,
                ServiceRequest::STATUS_REVISION_REQUIRED,
            ])
            ->filterStatus($request->string('status')->value())
            ->filterTrackingStatus($request->string('tracking_status')->value())
            ->filterServiceType($request->string('service_type')->value())
            ->filterTrackingId($request->string('tracking_id')->value())
            ->filterCustomerName($request->string('customer_name')->value())
            ->filterCreatedFrom($request->string('from')->value())
            ->filterCreatedTo($request->string('to')->value())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('employee.requests.index', compact('serviceRequests'));
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        if ($serviceRequest->isTrashed()) {
            abort(404);
        }

        if (! $serviceRequest->canEmployeeUpdateDetails()) {
            return back()->with('error', 'Employee cannot update this request details.');
        }

        $validated = $request->validate([
            'quantity' => ['nullable', 'string', 'max:255'],
            'product_detail' => ['nullable', 'string'],
            'weight' => ['nullable', 'string', 'max:255'],
            'dimension' => ['nullable', 'string', 'max:255'],
            'employee_note' => ['nullable', 'string'],
        ]);

        $serviceRequest->update($validated);

        return back()->with('success', 'Request details updated successfully.');
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest, NotificationService $notificationService)
    {
        if ($serviceRequest->isTrashed()) {
            abort(404);
        }

        if (! $serviceRequest->canEmployeeUpdateStatus()) {
            return back()->with('error', 'Employee cannot update this request status.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in([
                ServiceRequest::STATUS_PENDING,
                ServiceRequest::STATUS_COMPLETED,
            ])],
        ]);

        if ($validated['status'] !== $serviceRequest->nextEmployeeStatus()) {
            return back()->with('error', 'Invalid next status.');
        }

        if ($validated['status'] === ServiceRequest::STATUS_COMPLETED) {
            foreach (['quantity', 'product_detail', 'weight', 'dimension'] as $field) {
                if (blank($serviceRequest->{$field})) {
                    return back()->with(
                        'error',
                        ucfirst(str_replace('_', ' ', $field)) . ' is required before marking complete.'
                    );
                }
            }
        }

        $oldStatus = $serviceRequest->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($serviceRequest, $oldStatus, $newStatus) {
            
            $updateData = [
                'status' => $newStatus,
            ];

            if ($newStatus === ServiceRequest::STATUS_PENDING && $oldStatus === ServiceRequest::STATUS_REQUEST) {
                $updateData['processed_by'] = auth()->id();
            }

            if ($newStatus === ServiceRequest::STATUS_COMPLETED) {
                $updateData['processed_by'] = auth()->id();
                $updateData['processed_at'] = now();
            }

            $serviceRequest->update($updateData);

            $serviceRequest->activityLogs()->create([
                'user_id' => auth()->id(),
                'action' => 'status_updated',
                'field_changed' => 'status',
                'old_value' => $oldStatus,
                'new_value' => $newStatus,
                'description' => 'Employee updated request status',
            ]);

        });

        if ($newStatus === ServiceRequest::STATUS_COMPLETED) {
            if ($oldStatus === ServiceRequest::STATUS_REVISION_REQUIRED) {
                $notificationService->notifyRequestRecompleted($serviceRequest);
            } else {
                $notificationService->notifyRequestCompleted($serviceRequest);
            }
        }

        return back()->with('success', 'Request status updated successfully.');
    }

    public function updateTrackingStatus(Request $request, ServiceRequest $serviceRequest, NotificationService $notificationService)
    {
        if ($serviceRequest->isTrashed()) {
            abort(404);
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

        $notificationService->notifyTrackingUpdated($serviceRequest);

        return back()->with('success', 'Tracking status updated successfully.');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        if ($serviceRequest->isTrashed()) {
            abort(404);
        }

        $serviceRequest->load([
            'trackingEvents.updater',
            'activityLogs.user',
        ]);

        return view('employee.requests.show', compact('serviceRequest'));
    }

}