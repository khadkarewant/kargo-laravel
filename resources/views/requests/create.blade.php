<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-orange-600">Customer Request</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Create Service Request
                </h2>
            </div>

            <a href="{{ route('requests.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Back to Requests
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-slate-900">Request Information</h3>
                    <p class="mt-1 text-sm text-slate-600">
                        Fill in the details below to submit a cargo, courier, import, export, or customs request.
                    </p>
                </div>

                <form method="POST" action="{{ route('requests.store') }}" class="space-y-8 px-6 py-6">
                    @csrf

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <label for="service_type" class="block text-sm font-semibold text-slate-900">
                            Service Type
                        </label>
                        <p class="mt-1 text-sm text-slate-500">
                            Select the type of logistics support you need.
                        </p>

                        <select
                            name="service_type"
                            id="service_type"
                            class="mt-4 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                            required
                        >
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
                            <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <section class="rounded-2xl border border-slate-200 bg-white p-6">
                            <div class="mb-5">
                                <h3 class="text-lg font-semibold text-slate-900">Sender Details</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Enter the sender’s basic contact information.
                                </p>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label for="sender_name" class="block text-sm font-medium text-slate-700">Sender Name</label>
                                    <input
                                        id="sender_name"
                                        name="sender_name"
                                        type="text"
                                        value="{{ old('sender_name') }}"
                                        class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                        required
                                    >
                                    @error('sender_name')
                                        <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="sender_country" class="block text-sm font-medium text-slate-700">Sender Country</label>
                                    <input
                                        id="sender_country"
                                        name="sender_country"
                                        type="text"
                                        value="{{ old('sender_country') }}"
                                        class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                        required
                                    >
                                    @error('sender_country')
                                        <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="sender_contact" class="block text-sm font-medium text-slate-700">Sender Contact</label>
                                    <input
                                        id="sender_contact"
                                        name="sender_contact"
                                        type="text"
                                        value="{{ old('sender_contact') }}"
                                        class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                        required
                                    >
                                    @error('sender_contact')
                                        <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-slate-200 bg-white p-6">
                            <div class="mb-5">
                                <h3 class="text-lg font-semibold text-slate-900">Receiver Details</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Enter the receiver’s destination and contact information.
                                </p>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label for="receiver_name" class="block text-sm font-medium text-slate-700">Receiver Name</label>
                                    <input
                                        id="receiver_name"
                                        name="receiver_name"
                                        type="text"
                                        value="{{ old('receiver_name') }}"
                                        class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                        required
                                    >
                                    @error('receiver_name')
                                        <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="receiver_country" class="block text-sm font-medium text-slate-700">Receiver Country</label>
                                    <input
                                        id="receiver_country"
                                        name="receiver_country"
                                        type="text"
                                        value="{{ old('receiver_country') }}"
                                        class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                        required
                                    >
                                    @error('receiver_country')
                                        <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="receiver_contact" class="block text-sm font-medium text-slate-700">Receiver Contact</label>
                                    <input
                                        id="receiver_contact"
                                        name="receiver_contact"
                                        type="text"
                                        value="{{ old('receiver_contact') }}"
                                        class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                        required
                                    >
                                    @error('receiver_contact')
                                        <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </section>
                    </div>

                    <section class="rounded-2xl border border-slate-200 bg-white p-6">
                        <div class="mb-5">
                            <h3 class="text-lg font-semibold text-slate-900">Request Notes</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Describe the products, quantity or units, and any important request details.
                            </p>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700">Notes</label>
                            <textarea
                                id="notes"
                                name="notes"
                                rows="6"
                                class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                placeholder="Describe the products, quantity or units, and any important request details."
                                required
                            >{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="mt-2 text-sm font-medium text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </section>

                    <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-slate-500">
                            Please review the information carefully before submitting.
                        </p>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-orange-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-orange-600"
                        >
                            Submit Request
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>