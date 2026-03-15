<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manager Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-3">
                    <p>{{ __("You're logged in!") }}</p>

                    <div class="flex gap-4">
                        <a href="{{ route('manager.requests.index') }}"
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded">
                            Check Requests
                        </a>

                        <a href="{{ route('manager.staff.index') }}"
                           class="inline-block px-4 py-2 bg-gray-800 text-white rounded">
                            View Staff
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>