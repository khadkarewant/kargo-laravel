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
                <th>Tracking</th>
                <th>Status</th>
                <th>Action</th>
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
                    <td>
                        <a href="{{ route('requests.edit', $r->id) }}">Edit</a>

                        <form method="POST" action="{{ route('requests.destroy', $r->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>   
            @empty
                <tr>
                    <td colspan="7">No requests yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
