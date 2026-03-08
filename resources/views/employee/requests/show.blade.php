<x-app-layout>
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <h1>Request Details</h1>

    <p><strong>Tracking ID:</strong> {{ $serviceRequest->tracking_id }}</p>
    <p><strong>Sender:</strong> {{ $serviceRequest->sender_name }}</p>
    <p><strong>Receiver:</strong> {{ $serviceRequest->receiver_name }}</p>
    <p><strong>Service Type:</strong> {{ $serviceRequest->service_type }}</p>
    <p><strong>Status:</strong> {{ $serviceRequest->status }}</p>
    <p><strong>Tracking Status:</strong> {{ $serviceRequest->tracking_status ? ucfirst($serviceRequest->tracking_status) : 'Not started' }}</p>

    <hr>

    <h2>Action</h2>

    @if ($serviceRequest->canEmployeeUpdateStatus())
        <form action="{{ route('employee.requests.updateStatus', $serviceRequest) }}" method="POST">
            @csrf
            @method('PATCH')

            <input type="hidden" name="status" value="{{ $serviceRequest->nextEmployeeStatus() }}">

            <button type="submit">
                Move to {{ ucfirst($serviceRequest->nextEmployeeStatus()) }}
            </button>
        </form>
    @elseif ($serviceRequest->canEmployeeUpdateTrackingStatus() && $serviceRequest->nextTrackingStatus())
        <form action="{{ route('employee.requests.updateTrackingStatus', $serviceRequest) }}" method="POST">
            @csrf
            @method('PATCH')

            <input type="hidden" name="tracking_status" value="{{ $serviceRequest->nextTrackingStatus() }}">

            <textarea name="note" placeholder="Optional tracking note"></textarea>

            <button type="submit">
                Move to {{ ucfirst($serviceRequest->nextTrackingStatus()) }}
            </button>
        </form>
    @else
        <p>No action available</p>
    @endif

    <hr>

    <h2>Tracking History</h2>

    @if($serviceRequest->trackingEvents->isEmpty())
        <p>No tracking events yet.</p>
    @else
        <ul>
            @foreach($serviceRequest->trackingEvents as $event)
                <li>
                    <strong>{{ ucfirst($event->tracking_status) }}</strong>
                    by {{ $event->updater->name ?? 'Unknown' }}
                    at {{ $event->created_at->format('Y-m-d h:i A') }}
                    @if($event->note)
                        - {{ $event->note }}
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    <hr>

    <h2>Activity Log History</h2>

    @if($serviceRequest->activityLogs->isEmpty())
        <p>No activity logs yet.</p>
    @else
        <ul>
            @foreach($serviceRequest->activityLogs as $log)
                <li>
                    <strong>{{ $log->action }}</strong>
                    by {{ $log->user->name ?? 'Unknown' }}
                    at {{ $log->created_at->format('Y-m-d h:i A') }}
                    <br>
                    {{ $log->old_value ?? 'null' }} → {{ $log->new_value ?? 'null' }}
                    @if($log->description)
                        - {{ $log->description }}
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    <hr>

    <a href="{{ route('employee.requests.index') }}">Back to Employee Requests</a>
</x-app-layout>