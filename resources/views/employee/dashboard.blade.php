<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-orange-600">Employee Workspace</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Employee Dashboard
                </h2>
            </div>

            <a href="{{ route('employee.requests.index') }}"
               class="inline-flex items-center justify-center rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600">
                View Assigned Requests
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Assigned Requests</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">--</p>
                    <p class="mt-2 text-sm text-slate-600">
                        Requests currently assigned for your handling.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Pending Action</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">--</p>
                    <p class="mt-2 text-sm text-slate-600">
                        Requests waiting for update, processing, or revision work.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Processed Requests</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">--</p>
                    <p class="mt-2 text-sm text-slate-600">
                        Requests you have already moved forward in the workflow.
                    </p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Work Queue</h3>
                                <p class="mt-1 text-sm text-slate-600">
                                    Review requests assigned to you and continue operational processing.
                                </p>
                            </div>

                            <a href="{{ route('employee.requests.index') }}"
                               class="text-sm font-semibold text-orange-600 transition hover:text-orange-700">
                                Open Requests
                            </a>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                            <p class="text-sm font-medium text-slate-700">No request summary added yet.</p>
                            <p class="mt-2 text-sm text-slate-500">
                                We’ll connect this dashboard to real employee request data later.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-semibold uppercase tracking-wide text-orange-600">Quick Actions</p>

                    <div class="mt-5 space-y-3">
                        <a href="{{ route('employee.requests.index') }}"
                           class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-slate-900">
                            <span>Check assigned requests</span>
                            <span>&rarr;</span>
                        </a>

                        <a href="{{ route('notifications.index') }}"
                           class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-slate-900">
                            <span>View notifications</span>
                            <span>&rarr;</span>
                        </a>

                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-orange-200 hover:bg-orange-50 hover:text-slate-900">
                            <span>Update profile</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>