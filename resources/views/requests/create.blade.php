<x-app-layout>

    <h1>Create Service Request</h1>  

  <form method="POST" action="{{ route('requests.store') }}">
        @csrf

        <div>
            <label>Service Type</label>
            <input name="service_type" type="text" value="{{ old('service_type') }}" placeholder="import/export/clearance">
            @error('service_type') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Sender Name</label>
            <input name="sender_name" type="text" value="{{ old('sender_name') }}" >
            @error('sender_name') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label>Receiver Name</label>
            <input name="receiver_name" type="text" value="{{ old('receiver_name') }}">
            @error('receiver_name') <div>{{ $message }}</div> @enderror
        </div>

        <button type="submit">Create Request</button>
    </form>
</x-app-layout>
