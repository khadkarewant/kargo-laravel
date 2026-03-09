<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index()
    {
        $requests = auth()->user()
            ->serviceRequests()
            ->latest()
            ->get();

        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        $this->authorize('create', ServiceRequest::class);

        return view('requests.create');
    }
    
    public function store(Request $request)
    {
        $this->authorize('create', ServiceRequest::class);

        $data = $request->validate([
            'service_type'  => 'required|string|max:50',
            'sender_name'   => 'required|string|max:150',
            'receiver_name' => 'required|string|max:150',
        ]);

        auth()->user()->serviceRequests()->create($data);

        return redirect()->route('requests.index');
    }
    
    public function destroy(ServiceRequest $serviceRequest)
    {
        $this->authorize('delete', $serviceRequest);

        $serviceRequest->delete();

        return redirect()->route('requests.index');
    }

    public function edit(ServiceRequest $serviceRequest)
    {
        $this->authorize('update', $serviceRequest);

        return view('requests.edit', compact('serviceRequest'));
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorize('update', $serviceRequest);

        $data = $request->validate([
            'service_type' => 'required|string|max:50',
            'sender_name' => 'required|string|max:150',
            'receiver_name' => 'required|string|max:150',
        ]);

        $serviceRequest->update($data);

        return redirect()->route('requests.index');
    }
}