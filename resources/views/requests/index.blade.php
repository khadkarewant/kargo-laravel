<x-app-layout>


    <h1>Service Requests</h1>

    <hr>

    <a href="{{ route('requests.create') }}">Create New Request</a>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>ID</th>
                <th>Service Type</th>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Tracking ID</th>
                <th>Tracking Status</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($requests as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->service_type_label }}</td>
                    <td>{{ $r->sender_name }}</td>
                    <td>{{ $r->receiver_name }}</td>
                    <td>{{ $r->tracking_id ?? '-' }}</td>
                    <td>{{ $r->tracking_status_label }}</td>
                    <td>{{ $r->status_label }}</td>
                    <td>
                        <a href="{{ route('requests.show', $r) }}">
                            View Details
                        </a>
                    </td>
                </tr>   
            @empty
                <tr>
                    <td colspan="8">No requests yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $requests->links() }}
</x-app-layout>
