<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-semibold mb-6">Notifications</h1>

        @forelse ($notifications as $notification)
            <div class="mb-4 rounded border bg-white p-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="font-semibold">{{ $notification->title }}</h2>
                        <p class="mt-1 text-sm text-gray-700">{{ $notification->message }}</p>
                        <p class="mt-2 text-xs text-gray-500">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="text-right">
                        @if (is_null($notification->read_at))
                            <form method="POST" action="{{ route('notifications.read', $notification) }}" class="mt-2">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="text-sm text-blue-600 hover:underline">
                                    Mark as read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded border bg-white p-4 text-gray-600">
                No notifications yet.
            </div>
        @endforelse

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>