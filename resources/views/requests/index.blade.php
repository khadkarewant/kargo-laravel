@extends('layouts.app')

@section('content')
<h1>Service Requests</h1>

<form method="POST" action="{{ route('requests.store') }}">
    @csrf

    <div>
        <label>Service Type</label>
        <input name="service_type" type="text" value="{{ old('service_type') }}" placeholder="import/export/clearance">
        @error('service_type') <div>{{ $message }}</div> @enderror
    </div>

    <div>
        <label>Sender Name</label>
        <input name="sender_name" type="text" value="{{ old('sender_name') }}" >
        @error('sender_name') <div>{{ $message }}</div> @enderror
    </div>

    <div>
        <label>Receiver Name</label>
        <input name="receiver_name" type="text" value="{{ old('receiver_name') }}">
        @error('receiver_name') <div>{{ $message }}</div> @enderror
    </div>

    <div>
        <label>Tracking ID (optional)</label>
        <input name="tracking_id" type="text" value="{{ old('tracking_id') }}">
        @error('tracking_id') <div>{{ $message }}</div> @enderror
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
                <td>{{ $r->tracking_id ?? '-' }}</td>
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