<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

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

        $serviceRequest->update([
            'status' => ServiceRequest::STATUS_APPROVED,
        ]);

        return back()->with('success', 'Request approved successfully.');

    }
}