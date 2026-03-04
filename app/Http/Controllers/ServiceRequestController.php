<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index()
    {
        $requests = ServiceRequest::latest()->get();
        return view('requests.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_type'  => 'required|string|max:50',
            'sender_name'   => 'required|string|max:150',
            'receiver_name' => 'required|string|max:150',
            'tracking_id'   => 'nullable|string|max:50',
        ]);

        $data['user_id'] = 1;

        ServiceRequest::create($data);

        return redirect()->route('requests.index');
    }
    public function destroy(ServiceRequest $request)
    {
        $request->delete();

        return redirect()->route('requests.index');
    }
}