<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-orange-600">Manager Workspace</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Manager Request Dashboard
                </h2>
            </div>

            <a href="{{ route('manager.requests.trashed') }}"
               class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                View Inactive Requests
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Request Review Queue</h3>
                            <p class="mt-1 text-sm text-slate-600">
                                Review service requests, tracking progress, and approval-stage workflow.
                            </p>
                        </div>
                    </div>
                </div>

                @if($serviceRequests->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Tracking ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Sender
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Receiver
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Service Type
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Tracking Status
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach ($serviceRequests as $serviceRequest)
                                    <tr class="hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-900">
                                            {{ $serviceRequest->tracking_id }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-slate-700">
                                            {{ $serviceRequest->sender_name }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-slate-700">
                                            {{ $serviceRequest->receiver_name }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-slate-700">
                                            {{ $serviceRequest->service_type_label }}
                                        </td>

                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700 ring-1 ring-orange-200">
                                                {{ $serviceRequest->status_label }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                {{ $serviceRequest->tracking_status_label }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('manager.requests.show', $serviceRequest) }}"
                                               class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-slate-900">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-slate-200 px-6 py-4">
                        {{ $serviceRequests->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto max-w-md">
                            <h3 class="text-lg font-semibold text-slate-900">No requests available</h3>
                            <p class="mt-2 text-sm text-slate-600">
                                There are currently no requests in the manager review queue.
                            </p>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>