<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class RequestController extends Controller
{

    public function dashboard()
    {
        $base = ServiceRequest::active()
            ->whereIn('status', [
                ServiceRequest::STATUS_REQUEST,
                ServiceRequest::STATUS_PENDING,
                ServiceRequest::STATUS_COMPLETED,
                ServiceRequest::STATUS_APPROVED,
                ServiceRequest::STATUS_REVISION_REQUIRED,
            ]);

        $totalRequests = (clone $base)->count();

        $pendingReview = (clone $base)
            ->whereIn('status', [
                ServiceRequest::STATUS_COMPLETED,
            ])->count();

        $inactiveRequests = ServiceRequest::inactive()->count();

        $activeStaff = User::whereIn('role', ['manager', 'employee'])->count();

        $recentRequests = (clone $base)
            ->with('customer')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('manager.dashboard', compact(
            'totalRequests',
            'pendingReview',
            'inactiveRequests',
            'activeStaff',
            'recentRequests',
        ));
    }

    public function index(Request $request)
    {
        $serviceRequests = ServiceRequest::with('customer')
            ->active()
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

        return view('manager.requests.index', compact('serviceRequests'));
    }

    public function approve(ServiceRequest $serviceRequest, NotificationService $notificationService)
    {
        if ($serviceRequest->isTrashed()) {
            return back()->with('error', 'This request is inactive.');
        }

        if (! $serviceRequest->canManagerApprove()) {
            return back()->with('error', 'This request cannot be approved.');
        }

        $oldStatus = $serviceRequest->status;
        $newStatus = ServiceRequest::STATUS_APPROVED;

        DB::transaction(function () use ($serviceRequest, $oldStatus, $newStatus) {
            $serviceRequest->update([
                'status' => ServiceRequest::STATUS_APPROVED,
                'manager_note' => null,
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

        $notificationService->notifyRequestApproved($serviceRequest);

        return back()->with('success', 'Request approved successfully.');

    }

    public function markRevisionRequired(ServiceRequest $serviceRequest, NotificationService $notificationService)
    {
        if ($serviceRequest->isTrashed()) {
            return back()->with('error', 'This request is inactive.');
        }
        $validated = request()->validate([
            'manager_note' => ['required', 'string'],
        ]);

        if (! $serviceRequest->canManagerMarkRevisionRequired()) {
            return back()->with('error', 'This request cannot be marked as revision required.');
        }

        $oldStatus = $serviceRequest->status;
        $newStatus = ServiceRequest::STATUS_REVISION_REQUIRED;

        DB::transaction(function () use ($serviceRequest, $oldStatus, $newStatus, $validated) {
            
            $serviceRequest->update([
                'status' => ServiceRequest::STATUS_REVISION_REQUIRED,
                'manager_note' => $validated['manager_note'],
            ]);

            $serviceRequest->activityLogs()->create([
            'user_id' => auth()->id(),
            'action' => ServiceRequest::ACTION_REVISION_REQUIRED,
            'field_changed' => 'status',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'description' => 'Manager marked request as revision required',
            ]);
        });

        $notificationService->notifyRevisionRequired($serviceRequest);

        return back()->with('success', 'Request marked as revision required.');
    }

    public function trash(Request $request, ServiceRequest $serviceRequest)
    {
        if ($serviceRequest->isTrashed()) {
            return back()->with('error', 'This request is already inactive.');
        }

        $validated = $request->validate([
            'trash_reason' => ['required', 'string', 'max:1000'],
        ]);

        $serviceRequest->update([
            'is_trashed' => true,
            'trashed_at' => now(),
            'trashed_by' => auth()->id(),
            'trash_reason' => $validated['trash_reason'],
        ]);
        
        return back()->with('success', 'Request moved to trash successfully.');
    }

    public function trashed()
    {
        $serviceRequests = ServiceRequest::with('trashedBy')
            ->where('is_trashed', true)
            ->latest('trashed_at')
            ->paginate(10);
        return view('manager.requests.trashed', compact('serviceRequests'));
    }

    public function restore(ServiceRequest $serviceRequest) 
    {
        if (! $serviceRequest->isTrashed()) {
            return back()->with('error', 'This request is not inactive.');
        }
        
        $serviceRequest->update([
            'is_trashed' => false,
            'trashed_at' => null,
            'trashed_by' => null,
            'trash_reason' => null,
        ]);

        return back()->with('success', 'Request restored successfully.');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load([
            'trackingEvents.updater',
            'activityLogs.user',
            'processor',
        ]);

        return view('manager.requests.show', compact('serviceRequest'));
    }
}