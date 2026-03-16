@extends('layouts.guest-public')

@section('content')
    <section class="bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wide text-orange-400">Public Tracking</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    Tracking Result
                </h1>
                <p class="mt-4 text-base leading-7 text-slate-300">
                    View the current shipment or request status and follow progress updates using your tracking ID.
                </p>
            </div>
        </div>
    </section>

    <section class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-1">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-semibold uppercase tracking-wide text-orange-600">Shipment Summary</p>

                        <div class="mt-6 space-y-5">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Tracking ID</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $serviceRequest->tracking_id }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Service Type</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ ucfirst($serviceRequest->service_type) }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Current Status</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 text-sm font-semibold text-orange-700 ring-1 ring-orange-200">
                                        {{ ucwords(str_replace('_', ' ', $serviceRequest->tracking_status ?? $serviceRequest->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ url('/') }}#tracking"
                           class="mt-8 inline-flex w-full items-center justify-center rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Search Again
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-wide text-orange-600">Tracking Timeline</p>
                                <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                                    Shipment Progress Updates
                                </h2>
                            </div>
                        </div>

                        <div class="mt-8">
                            @forelse($serviceRequest->trackingEvents as $event)
                                <div class="relative pl-8 {{ !$loop->last ? 'pb-8' : '' }}">
                                    @if (!$loop->last)
                                        <div class="absolute left-[0.45rem] top-6 h-full w-0.5 bg-slate-200"></div>
                                    @endif

                                    <div class="absolute left-0 top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-orange-500 ring-4 ring-orange-100"></div>

                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                            {{ $event->created_at->format('M d, Y - h:i A') }}
                                        </p>

                                        <p class="mt-2 text-base font-semibold text-slate-900">
                                            {{ ucwords(str_replace('_', ' ', $event->tracking_status)) }}
                                        </p>

                                        @if($event->note)
                                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                                {{ $event->note }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                                    <p class="text-sm font-medium text-slate-700">No tracking updates available yet.</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Please check again later for progress updates.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection