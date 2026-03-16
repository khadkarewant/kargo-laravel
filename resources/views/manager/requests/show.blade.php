<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-orange-600">Manager Workspace</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Manager Request Details
                </h2>
            </div>

            <a href="{{ route('manager.requests.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Back to Manager Requests
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

            <section class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-slate-900">Request Overview</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            Review customer submission, employee processing details, and approval-stage information.
                        </p>
                    </div>

                    <div class="grid gap-6 px-6 py-6 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Tracking ID</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">
                                {{ $serviceRequest->tracking_id }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Service Type</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ ucfirst($serviceRequest->service_type) }}
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

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Quantity</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->quantity ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Weight</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->weight ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Dimension</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->dimension ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Customer Notes</p>
                            <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                {{ $serviceRequest->notes ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Product Detail</p>
                            <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                {{ $serviceRequest->product_detail ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Employee Note</p>
                            <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                {{ $serviceRequest->employee_note ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Manager Note</p>
                            <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                {{ $serviceRequest->manager_note ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Processed By</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->processor->name ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Processed At</p>
                            <p class="mt-2 text-sm text-slate-700">
                                {{ $serviceRequest->processed_at ? $serviceRequest->processed_at->format('Y-m-d h:i A') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-semibold uppercase tracking-wide text-orange-600">Current Status</p>

                        <div class="mt-5 space-y-5">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Request Status</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700 ring-1 ring-orange-200">
                                        {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Tracking Status</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ $serviceRequest->tracking_status ? ucfirst($serviceRequest->tracking_status) : 'Not started' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-semibold uppercase tracking-wide text-orange-600">Manager Actions</p>

                        <div class="mt-4 space-y-4">
                            @if ($serviceRequest->canManagerApprove())
                                <form action="{{ route('manager.requests.approve', $serviceRequest) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="inline-flex w-full items-center justify-center rounded-lg bg-orange-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-orange-600">
                                        Approve Request
                                    </button>
                                </form>
                            @endif

                            @if ($serviceRequest->canManagerMarkRevisionRequired())
                                <form action="{{ route('manager.requests.markRevisionRequired', $serviceRequest) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label for="manager_note" class="block text-sm font-medium text-slate-700">Manager Note</label>
                                        <textarea
                                            id="manager_note"
                                            name="manager_note"
                                            rows="4"
                                            required
                                            class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                        >{{ old('manager_note', $serviceRequest->manager_note) }}</textarea>
                                        @error('manager_note')
                                            <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                            class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                        Mark as Revision Required
                                    </button>
                                </form>
                            @endif

                            @if (! $serviceRequest->isTrashed())
                                <div class="border-t border-slate-200 pt-4">
                                    <p class="mb-3 text-sm font-semibold text-red-700">Move Request to Trash</p>

                                    <form action="{{ route('manager.requests.trash', $serviceRequest) }}" method="POST" class="space-y-4">
                                        @csrf

                                        <div>
                                            <label for="trash_reason" class="block text-sm font-medium text-slate-700">Reason</label>
                                            <textarea
                                                name="trash_reason"
                                                id="trash_reason"
                                                rows="4"
                                                required
                                                class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200"
                                            >{{ old('trash_reason') }}</textarea>
                                            @error('trash_reason')
                                                <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit"
                                                class="inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                                            Move to Trash
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if (! $serviceRequest->canManagerApprove() && ! $serviceRequest->canManagerMarkRevisionRequired() && $serviceRequest->isTrashed())
                                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-600">
                                    No manager action available.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-slate-900">Tracking History</h3>
                    </div>

                    <div class="px-6 py-6">
                        @if($serviceRequest->trackingEvents->isEmpty())
                            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-600">
                                No tracking events yet.
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach($serviceRequest->trackingEvents as $event)
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
                                                {{ ucfirst($event->tracking_status) }}
                                            </p>

                                            <p class="mt-1 text-sm text-slate-500">
                                                by {{ $event->updater->name ?? 'Unknown' }}
                                            </p>

                                            @if($event->note)
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
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-slate-900">Activity Log History</h3>
                    </div>

                    <div class="px-6 py-6">
                        @if($serviceRequest->activityLogs->isEmpty())
                            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-600">
                                No activity logs yet.
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach($serviceRequest->activityLogs as $log)
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                            {{ $log->created_at->format('Y-m-d h:i A') }}
                                        </p>

                                        <p class="mt-2 text-base font-semibold text-slate-900">
                                            {{ $log->action }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            by {{ $log->user->name ?? 'Unknown' }}
                                        </p>

                                        <p class="mt-3 text-sm text-slate-700">
                                            {{ $log->old_value ?? 'null' }} → {{ $log->new_value ?? 'null' }}
                                        </p>

                                        @if($log->description)
                                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                                {{ $log->description }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>