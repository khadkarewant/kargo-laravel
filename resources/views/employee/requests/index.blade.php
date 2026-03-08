<x-app-layout>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <h1>Employee Request Dashboard</h1>

    <table border="1" cellpadding="10">
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
        @foreach($serviceRequests as $serviceRequest)
            <tr>
                <td>{{ $serviceRequest->tracking_id }}</td>
                <td>{{ $serviceRequest->sender_name }}</td>
                <td>{{ $serviceRequest->receiver_name }}</td>
                <td>{{ $serviceRequest->service_type }}</td>
                <td>{{ $serviceRequest->status }}</td>
                <td>{{ $serviceRequest->tracking_status ? ucfirst($serviceRequest->tracking_status) : 'Not started' }}</td>
                <td>
                    <a href="{{ route('employee.requests.show', $serviceRequest) }}">View Details</a>
                    <br><br>
                    @if ($serviceRequest->canEmployeeUpdateStatus())
                    <form action="{{ route('employee.requests.updateStatus', $serviceRequest) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status" value="{{ $serviceRequest->nextEmployeeStatus() }}">

                        <button class="submit">
                            Move to {{ ucfirst($serviceRequest->nextEmployeeStatus()) }}
                        </button>
                    </form>

                    @elseif ($serviceRequest->canEmployeeUpdateTrackingStatus() && $serviceRequest->nextTrackingStatus())
                        <form action="{{ route('employee.requests.updateTrackingStatus', $serviceRequest) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="tracking_status" value="{{ $serviceRequest->nextTrackingStatus() }}">

                            <textarea name="note" placeholder="Optional tracking note"></textarea>

                            <button class="submit">
                                Move to {{ ucfirst($serviceRequest->nextTrackingStatus()) }}
                            </button>
                        </form>
                    @else
                        No action available
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>

    </table>
</x-app-layout>
