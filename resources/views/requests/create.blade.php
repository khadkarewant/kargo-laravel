<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-semibold mb-6">Create Service Request</h1>

        <form method="POST" action="{{ route('requests.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="service_type" class="block mb-1">Service Type</label>
                <select name="service_type" id="service_type" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select service type</option>
                    <option value="{{ \App\Models\ServiceRequest::SERVICE_CLEARANCE }}" @selected(old('service_type') === \App\Models\ServiceRequest::SERVICE_CLEARANCE)>
                        Clearance
                    </option>
                    <option value="{{ \App\Models\ServiceRequest::SERVICE_IMPORT }}" @selected(old('service_type') === \App\Models\ServiceRequest::SERVICE_IMPORT)>
                        Import
                    </option>
                    <option value="{{ \App\Models\ServiceRequest::SERVICE_COURIER }}" @selected(old('service_type') === \App\Models\ServiceRequest::SERVICE_COURIER)>
                        Courier
                    </option>
                    <option value="{{ \App\Models\ServiceRequest::SERVICE_EXPORT }}" @selected(old('service_type') === \App\Models\ServiceRequest::SERVICE_EXPORT)>
                        Export
                    </option>
                </select>
                @error('service_type')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <h2 class="text-lg font-medium mb-3">Sender Details</h2>

                <div class="space-y-4">
                    <div>
                        <label for="sender_name" class="block mb-1">Sender Name</label>
                        <input
                            id="sender_name"
                            name="sender_name"
                            type="text"
                            value="{{ old('sender_name') }}"
                            class="w-full border rounded px-3 py-2"
                            required
                        >
                        @error('sender_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="sender_country" class="block mb-1">Sender Country</label>
                        <input
                            id="sender_country"
                            name="sender_country"
                            type="text"
                            value="{{ old('sender_country') }}"
                            class="w-full border rounded px-3 py-2"
                            required
                        >
                        @error('sender_country')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="sender_contact" class="block mb-1">Sender Contact</label>
                        <input
                            id="sender_contact"
                            name="sender_contact"
                            type="text"
                            value="{{ old('sender_contact') }}"
                            class="w-full border rounded px-3 py-2"
                            required
                        >
                        @error('sender_contact')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-medium mb-3">Receiver Details</h2>

                <div class="space-y-4">
                    <div>
                        <label for="receiver_name" class="block mb-1">Receiver Name</label>
                        <input
                            id="receiver_name"
                            name="receiver_name"
                            type="text"
                            value="{{ old('receiver_name') }}"
                            class="w-full border rounded px-3 py-2"
                            required
                        >
                        @error('receiver_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="receiver_country" class="block mb-1">Receiver Country</label>
                        <input
                            id="receiver_country"
                            name="receiver_country"
                            type="text"
                            value="{{ old('receiver_country') }}"
                            class="w-full border rounded px-3 py-2"
                            required
                        >
                        @error('receiver_country')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="receiver_contact" class="block mb-1">Receiver Contact</label>
                        <input
                            id="receiver_contact"
                            name="receiver_contact"
                            type="text"
                            value="{{ old('receiver_contact') }}"
                            class="w-full border rounded px-3 py-2"
                            required
                        >
                        @error('receiver_contact')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="notes" class="block mb-1">Notes</label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="5"
                    class="w-full border rounded px-3 py-2"
                    placeholder="Describe the products, quantity or units, and any important request details."
                    required
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</x-app-layout>