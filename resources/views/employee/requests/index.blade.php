<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-orange-600">Employee Workspace</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Employee Request Dashboard
                </h2>
            </div>
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
                            <h3 class="text-lg font-semibold text-slate-900">Assigned Requests</h3>
                            <p class="mt-1 text-sm text-slate-600">
                                Review active requests and open a request to continue processing.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('employee.requests.index') }}" class="border-b border-slate-200 px-6 py-4">
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <option value="">All</option>
                                @foreach (\App\Models\ServiceRequest::STATUSES as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>
                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tracking_status" class="block text-sm font-medium text-slate-700">Tracking Status</label>
                            <select name="tracking_status" id="tracking_status" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <option value="">All</option>
                                @foreach (\App\Models\ServiceRequest::TRACKING_STATUSES as $trackingStatus)
                                    <option value="{{ $trackingStatus }}" @selected(request('tracking_status') === $trackingStatus)>
                                        {{ ucwords(str_replace('_', ' ', $trackingStatus)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="service_type" class="block text-sm font-medium text-slate-700">Service Type</label>
                            <select name="service_type" id="service_type" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <option value="">All</option>
                                @foreach (\App\Models\ServiceRequest::SERVICE_TYPES as $serviceType)
                                    <option value="{{ $serviceType }}" @selected(request('service_type') === $serviceType)>
                                        {{ ucwords(str_replace('_', ' ', $serviceType)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tracking_id" class="block text-sm font-medium text-slate-700">Tracking ID</label>
                            <input
                                type="text"
                                name="tracking_id"
                                id="tracking_id"
                                value="{{ request('tracking_id') }}"
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                placeholder="Search tracking ID"
                            >
                        </div>

                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-slate-700">Customer Name</label>
                            <input
                                type="text"
                                name="customer_name"
                                id="customer_name"
                                value="{{ request('customer_name') }}"
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                placeholder="Search customer"
                            >
                        </div>

                        <div>
                            <label for="from" class="block text-sm font-medium text-slate-700">From Date</label>
                            <input
                                type="date"
                                name="from"
                                id="from"
                                value="{{ request('from') }}"
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            >
                        </div>

                        <div>
                            <label for="to" class="block text-sm font-medium text-slate-700">To Date</label>
                            <input
                                type="date"
                                name="to"
                                id="to"
                                value="{{ request('to') }}"
                                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            >
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-3">
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600"
                        >
                            Apply Filters
                        </button>

                        <a
                            href="{{ route('employee.requests.index') }}"
                            class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        >
                            Reset
                        </a>
                    </div>
                </form>

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
                                @foreach($serviceRequests as $serviceRequest)
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
                                            <a href="{{ route('employee.requests.show', $serviceRequest) }}"
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
                            <h3 class="text-lg font-semibold text-slate-900">No assigned requests</h3>
                            <p class="mt-2 text-sm text-slate-600">
                                There are currently no requests assigned to you.
                            </p>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>