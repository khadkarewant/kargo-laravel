<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-orange-600">Manager Workspace</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Create Employee
                </h2>
            </div>

            <a href="{{ route('manager.staff.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Back to Staff List
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-4">
                    <p class="text-sm font-semibold text-red-700">Please fix the following errors:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-slate-900">New Employee Account</h3>
                    <p class="mt-1 text-sm text-slate-600">
                        Create a staff account so an employee can access the operational workflow.
                    </p>
                </div>

                <form method="POST" action="{{ route('manager.staff.store') }}" class="space-y-6 px-6 py-6" autocomplete="off">
                    @csrf

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="off"
                                class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="off"
                                class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                            >
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                            >
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                            >
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-slate-500">
                            Double-check the email and password before creating the employee account.
                        </p>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-orange-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-orange-600"
                        >
                            Create Employee
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>