<x-app-layout>
    <h1>Request Details</h1>

    <p><strong>Tracking ID:</strong> {{ $serviceRequest->tracking_id }}</p>
    <p><strong>Sender:</strong> {{ $serviceRequest->sender_name }}</p>
    <p><strong>Receiver:</strong> {{ $serviceRequest->receiver_name }}</p>
    <p><strong>Service Type:</strong> {{ $serviceRequest->service_type_label }}</p>
    <p><strong>Status:</strong>
        @if ($serviceRequest->is_trashed)
            Inactive
        @else 
            {{ $serviceRequest->status_label }}
        @endif
    </p>
    <p><strong>Tracking Status:</strong>
        @if ($serviceRequest->is_trashed)
            Inactive
        @else
            {{ $serviceRequest->tracking_status_label }}
        @endif
    </p>

    <p><strong>Sender Country:</strong> {{ $serviceRequest->sender_country ?? 'N/A' }}</p>
    <p><strong>Sender Contact:</strong> {{ $serviceRequest->sender_contact ?? 'N/A' }}</p>
    <p><strong>Receiver Country:</strong> {{ $serviceRequest->receiver_country ?? 'N/A' }}</p>
    <p><strong>Receiver Contact:</strong> {{ $serviceRequest->receiver_contact ?? 'N/A' }}</p>
    <p><strong>Notes:</strong> {{ $serviceRequest->notes ?? 'N/A' }}</p>

    <hr>

    <h2>Tracking History</h2>

    @if ($serviceRequest->trackingEvents->isEmpty())
        <p>No tracking events yet.</p>
    @else
        <ul>
            @foreach ($serviceRequest->trackingEvents as $event)
                <li>
                    <strong>{{ ucwords(str_replace('_', ' ', $event->tracking_status)) }}</strong>
                    at {{ $event->created_at->format('Y-m-d h:i A') }}
                    @if ($event->note)
                        - {{ $event->note }}
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    <hr>

    <a href="{{ route('requests.index') }}">Back to Requests</a>
</x-app-layout>