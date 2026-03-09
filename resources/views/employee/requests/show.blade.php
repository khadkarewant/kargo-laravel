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

    <p><strong>Sender Country:</strong> {{ $serviceRequest->sender_country ?? 'N/A' }}</p>
    <p><strong>Sender Contact:</strong> {{ $serviceRequest->sender_contact ?? 'N/A' }}</p>
    <p><strong>Receiver Country:</strong> {{ $serviceRequest->receiver_country ?? 'N/A' }}</p>
    <p><strong>Receiver Contact:</strong> {{ $serviceRequest->receiver_contact ?? 'N/A' }}</p>
    <p><strong>Customer Notes:</strong> {{ $serviceRequest->notes ?? 'N/A' }}</p>
    <p><strong>Quantity:</strong> {{ $serviceRequest->quantity ?? 'N/A' }}</p>
    <p><strong>Product Detail:</strong> {{ $serviceRequest->product_detail ?? 'N/A' }}</p>
    <p><strong>Weight:</strong> {{ $serviceRequest->weight ?? 'N/A' }}</p>
    <p><strong>Dimension:</strong> {{ $serviceRequest->dimension ?? 'N/A' }}</p>
    <p><strong>Employee Note:</strong> {{ $serviceRequest->employee_note ?? 'N/A' }}</p>
    <hr>

    <h2>Update Request Details</h2>

    @if ($serviceRequest->canEmployeeUpdateDetails())
        <form action="{{ route('employee.requests.update', $serviceRequest) }}" method="POST">
            @csrf
            @method('PATCH')

            <div>
                <label>Quantity</label>
                <input type="text" name="quantity" value="{{ old('quantity', $serviceRequest->quantity) }}">
                @error('quantity') <div>{{ $message }}</div> @enderror
            </div>

            <div>
                <label>Product Detail</label>
                <textarea name="product_detail">{{ old('product_detail', $serviceRequest->product_detail) }}</textarea>
                @error('product_detail') <div>{{ $message }}</div> @enderror
            </div>

            <div>
                <label>Weight</label>
                <input type="text" name="weight" value="{{ old('weight', $serviceRequest->weight) }}">
                @error('weight') <div>{{ $message }}</div> @enderror
            </div>

            <div>
                <label>Dimension</label>
                <input type="text" name="dimension" value="{{ old('dimension', $serviceRequest->dimension) }}">
                @error('dimension') <div>{{ $message }}</div> @enderror
            </div>

            <div>
                <label>Employee Note</label>
                <textarea name="employee_note">{{ old('employee_note', $serviceRequest->employee_note) }}</textarea>
                @error('employee_note') <div>{{ $message }}</div> @enderror
            </div>

            <button type="submit">Save Details</button>
        </form>
    @else
        <p>Request details can no longer be updated.</p>
    @endif

    <h2>Status Action</h2>

    @if ($serviceRequest->canEmployeeUpdateStatus() && $serviceRequest->nextEmployeeStatus())
        <form action="{{ route('employee.requests.updateStatus', $serviceRequest) }}" method="POST">
            @csrf
            @method('PATCH')

            <input type="hidden" name="status" value="{{ $serviceRequest->nextEmployeeStatus() }}">

            <button type="submit">
                Move to {{ ucfirst(str_replace('_', ' ', $serviceRequest->nextEmployeeStatus())) }}
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

    <hr><hr>

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