<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-orange-600">Customer Request</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Request Details
                </h2>
            </div>

            <a href="{{ route('requests.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Back to Requests
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-slate-900">Request Overview</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            Review the main service request details and current shipment progress.
                        </p>
                    </div>

                    <div class="grid gap-6 px-6 py-6 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Tracking ID</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">
                                {{ $serviceRequest->tracking_id ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Service Type</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">
                                {{ $serviceRequest->service_type_label }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Sender</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->sender_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Receiver</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->receiver_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Sender Country</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->sender_country ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Sender Contact</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->sender_contact ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Receiver Country</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->receiver_country ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Receiver Contact</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->receiver_contact ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Notes</p>
                            <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                {{ $serviceRequest->notes ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-semibold uppercase tracking-wide text-orange-600">Request Status</p>

                        <div class="mt-5 space-y-5">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Current Status</p>
                                <div class="mt-2">
                                    @if ($serviceRequest->is_trashed)
                                        <span class="inline-flex items-center rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                                            Inactive
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700 ring-1 ring-orange-200">
                                            {{ $serviceRequest->status_label }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Tracking Status</p>
                                <div class="mt-2">
                                    @if ($serviceRequest->is_trashed)
                                        <span class="inline-flex items-center rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                                            Inactive
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ $serviceRequest->tracking_status_label }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-semibold uppercase tracking-wide text-orange-600">Quick Action</p>

                        <a href="{{ route('requests.index') }}"
                           class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Back to Requests
                        </a>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Tracking History</h3>
                    <p class="mt-1 text-sm text-slate-600">
                        Follow the timeline of updates for this request.
                    </p>
                </div>

                <div class="px-6 py-6">
                    @if ($serviceRequest->trackingEvents->isEmpty())
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                            <p class="text-sm font-medium text-slate-700">No tracking events yet.</p>
                            <p class="mt-1 text-sm text-slate-500">
                                Tracking updates will appear here once progress is recorded.
                            </p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach ($serviceRequest->trackingEvents as $event)
                                <div class="relative pl-8 {{ !$loop->last ? 'pb-6' : '' }}">
                                    @if (!$loop->last)
                                        <div class="absolute left-[0.45rem] top-6 h-full w-0.5 bg-slate-200"></div>
                                    @endif

                                    <div class="absolute left-0 top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-orange-500 ring-4 ring-orange-100"></div>

                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                            {{ $event->created_at->format('Y-m-d h:i A') }}
                                        </p>

                                        <p class="mt-2 text-base font-semibold text-slate-900">
                                            {{ ucwords(str_replace('_', ' ', $event->tracking_status)) }}
                                        </p>

                                        @if ($event->note)
                                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                                {{ $event->note }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-app-layout>