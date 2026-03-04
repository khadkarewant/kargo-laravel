@extends('layouts.app')

@section('content')
<h1>Service Requests</h1>

<form method="POST" action="{{ route('requests.store') }}">
    @csrf

    <div>
        <label>Service Type</label>
        <input name="service_type" placeholder="import/export/clearance">
    </div>

    <div>
        <label>Sender Name</label>
        <input name="sender_name">
    </div>

    <div>
        <label>Receiver Name</label>
        <input name="receiver_name">
    </div>

    <div>
        <label>Tracking ID (optional)</label>
        <input name="tracking_id">
    </div>

    <button type="submit">Create Request</button>
</form>

<hr>

<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>ID</th>
            <th>Service Type</th>
            <th>Sender</th>
            <th>Receiver</th>
            <th>Tracking</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($requests as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->service_type }}</td>
                <td>{{ $r->sender_name }}</td>
                <td>{{ $r->receiver_name }}</td>
                <td>{{ $r->tracking_id }}</td>
                <td>{{ $r->status }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No requests yet</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection