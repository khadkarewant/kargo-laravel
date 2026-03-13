<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class PublicTrackingController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'tracking_id' => ['required', 'string', 'max:255'],
        ]);

        $trackingId = trim($request->tracking_id);

        $serviceRequest = ServiceRequest::select([
                'id',
                'tracking_id',
                'service_type',
                'status',
                'tracking_status',
            ])
            ->with('trackingEvents')
            ->where('tracking_id', $trackingId)
            ->first();

        if (!$serviceRequest) {
            return redirect('/')
                ->withInput()
                ->withErrors([
                    'tracking_id' => 'Tracking ID not found.',
                ]);
        }

        return view('tracking.show', compact('serviceRequest'));
    }
}