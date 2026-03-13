<x-guest-layout>
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Tracking Result</h1>

        <div class="bg-white shadow rounded-lg p-6 mb-6 space-y-2">
            <p><strong>Tracking ID:</strong> {{ $serviceRequest->tracking_id }}</p>
            <p><strong>Service Type:</strong> {{ ucfirst($serviceRequest->service_type) }}</p>
            <p><strong>Current Status:</strong> {{ ucwords(str_replace('_', ' ', $serviceRequest->tracking_status ?? $serviceRequest->status)) }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Tracking Timeline</h2>

            @forelse($serviceRequest->trackingEvents as $event)
                <div class="border-l-4 border-gray-300 pl-4 ml-2 mb-6 relative">
                    <div class="absolute -left-2 top-1 w-3 h-3 bg-gray-600 rounded-full"></div>

                    <p class="text-sm text-gray-500">
                        {{ $event->created_at->format('M d, Y - h:i A') }}
                    </p>

                    <p class="font-semibold">
                        {{ ucwords(str_replace('_', ' ', $event->tracking_status)) }}
                    </p>

                    @if($event->note)
                        <p class="text-gray-700 mt-1">{{ $event->note }}</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">No tracking updates available yet.</p>
            @endforelse

            <a href="{{ url('/') }}" class="inline-block mt-4 px-4 py-2 bg-gray-800 text-white rounded">
                Search Again
            </a>
        </div>
    </div>
</x-guest-layout>