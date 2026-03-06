<x-app-layout>
    
    <h1>Edit Service Request #{{ $serviceRequest->id }} </h1>
    
    <form method="POST" action="{{ route('requests.update', $serviceRequest->id) }}">
        @csrf
        @method('PUT')
        
        <p><strong>Tracking ID:</strong> {{ $serviceRequest->tracking_id }}</p>
        <p><strong>Status:</strong> {{ $serviceRequest->status }}</p>
        <div>
            <label>Service Type</label>
            <input type="text" name="service_type" value="{{ old('service_type', $serviceRequest->service_type) }}">
            @error('service_type') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Sender Name</label>
            <input type="text" name="sender_name" value="{{ old('sender_name', $serviceRequest->sender_name) }}">
            @error('sender_name') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Receiver Name</label>
            <input type="text" name="receiver_name" value="{{ old('receiver_name', $serviceRequest->receiver_name) }}">
            @error('receiver_name') <div>{{ $message }}</div> @enderror
        </div>

        <button type="submit">Update</button>
        <a href="{{ route('requests.index') }}">Cancel</a>
    </form>
</x-app-layout>
