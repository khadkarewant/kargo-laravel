<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceRequestController extends Controller
{
    public function index()
    {
        $requests = auth()->user()
            ->serviceRequests()
            ->latest()
            ->paginate(2);

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
            'service_type' => ['required', 'string', Rule::in(ServiceRequest::SERVICE_TYPES)],
            'sender_name' => ['required', 'string', 'max:150'],
            'sender_country' => ['required', 'string', 'max:150'],
            'sender_contact' => ['required', 'string', 'max:150'],
            'receiver_name' => ['required', 'string', 'max:150'],
            'receiver_country' => ['required', 'string', 'max:150'],
            'receiver_contact' => ['required', 'string', 'max:150'],
            'notes' => ['required', 'string'],
        ]);

        $data['status'] = ServiceRequest::STATUS_REQUEST;

        auth()->user()->serviceRequests()->create($data);

        return redirect()
            ->route('requests.index')
            ->with('success', 'Service request submitted successfully.');
    }
}