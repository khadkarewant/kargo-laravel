<x-app-layout>

    <h1>Edit Service Request #{{ $request->id }} </h1>

    <form method="POST" action="{{ route('requests.update', $request->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Service Type</label>
            <input type="text" name="service_type" value="{{ old('service_type', $request->service_type) }}">
            @error('service_type') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Sender Name</label>
            <input type="text" name="sender_name" value="{{ old('sender_name', $request->sender_name) }}">
            @error('sender_name') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Receiver Name</label>
            <input type="text" name="receiver_name" value="{{ old('receiver_name', $request->receiver_name) }}">
            @error('receiver_name') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Tracking ID</label>
            <input type="text" name="tracking_id" value="{{ old('tracking_id', $request->tracking_id) }}">
            @error('tracking_id') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Status</label>
            <input type="text" name="status" value="{{ old('status', $request->status) }}">
            @error('status') <div>{{ $message }}</div> @enderror
        </div>

        <button type="submit">Update</button>
        <a href="{{ route('requests.index') }}">Cancel</a>
    </form>
</x-app-layout>
