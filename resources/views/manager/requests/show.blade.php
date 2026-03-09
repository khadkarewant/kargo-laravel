<x-app-layout>
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <h1>Manager Request Details</h1>

    <p><strong>Tracking ID:</strong> {{ $serviceRequest->tracking_id }}</p>
    <p><strong>Sender:</strong> {{ $serviceRequest->sender_name }}</p>
    <p><strong>Receiver:</strong> {{ $serviceRequest->receiver_name }}</p>
    <p><strong>Service Type:</strong> {{ $serviceRequest->service_type }}</p>
    <p><strong>Status:</strong> {{ $serviceRequest->status }}</p>
    <p><strong>Tracking Status:</strong> {{ $serviceRequest->tracking_status ? ucfirst($serviceRequest->tracking_status) : 'Not started' }}</p>

    <hr>

    <h2>Action</h2>

    @if ($serviceRequest->canManagerApprove())
        <form action="{{ route('manager.requests.approve', $serviceRequest) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit">Approve</button>
        </form>
    @endif

    @if ($serviceRequest->canManagerMarkRevisionRequired())
        <form action="{{ route('manager.requests.markRevisionRequired', $serviceRequest) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit">Mark as Revision Required</button>
        </form>
    @endif

    @if (! $serviceRequest->canManagerApprove() && ! $serviceRequest->canManagerMarkRevisionRequired())
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

    <a href="{{ route('manager.requests.index') }}">Back to Manager Requests</a>
</x-app-layout>