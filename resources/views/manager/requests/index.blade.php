<x-app-layout>
    <h1>Manager Request Dashboard</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <table border="1" cellpadding="10" >
        <thead>
            <tr>
                <th>Tracking ID</th>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Service Type</th>
                <th>Status</th>
                <th>Tracking Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($serviceRequests as $serviceRequest)
                <tr>
                    <td>{{ $serviceRequest->tracking_id }}</td>
                    <td>{{ $serviceRequest->sender_name }}</td>
                    <td>{{ $serviceRequest->receiver_name }}</td>
                    <td>{{ $serviceRequest->service_type }}</td>
                    <td>{{ $serviceRequest->status }}</td>
                    <td>{{ $serviceRequest->tracking_status }}</td>
                    <td>
                        <a href="{{ route('manager.requests.show', $serviceRequest) }}">
                            View Details
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $serviceRequests->links() }}
</x-app-layout>