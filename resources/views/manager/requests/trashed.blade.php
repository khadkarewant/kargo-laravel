<x-app-layout>
    <h1>Inactive Requests</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <p>
        <a href="{{ route('manager.requests.index') }}">Back to Active Requests</a>
    </p>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Tracking ID</th>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Service Type</th>
                <th>Status</th>
                <th>Trash Reason</th>
                <th>Trashed By</th>
                <th>Trashed At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($serviceRequests as $serviceRequest)
                <tr>
                    <td>{{ $serviceRequest->tracking_id }}</td>
                    <td>{{ $serviceRequest->sender_name }}</td>
                    <td>{{ $serviceRequest->receiver_name }}</td>
                    <td>{{ $serviceRequest->service_type_label }}</td>
                    <td>Inactive</td>
                    <td>{{ $serviceRequest->trash_reason }}</td>
                    <td>{{ $serviceRequest->trashedBy->name ?? 'N/A' }}</td>
                    <td>{{ $serviceRequest->trashed_at?->format('Y-m-d h:i A') ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('manager.requests.show', $serviceRequest) }}">
                            View Details
                        </a>

                        <form action="{{ route('manager.requests.restore', $serviceRequest) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" onclick="return confirm('Restore this request')">Restore</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No inactive requests found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $serviceRequests->links() }}
</x-app-layout>