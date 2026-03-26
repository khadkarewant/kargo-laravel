<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-orange-600">Staff Management</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Employee Details
                </h2>
            </div>

            <a href="{{ route('manager.staff.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                &larr; Back to Staff
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Profile</h3>

                        @if ($user->is_active)
                            <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-green-200">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-200">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

                <div class="divide-y divide-slate-100 px-6">
                    <div class="py-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Name</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                    </div>

                    <div class="py-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Email</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $user->email }}</p>
                    </div>

                    <div class="py-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Role</p>
                        <p class="mt-1 text-sm text-slate-700">{{ ucfirst($user->role) }}</p>
                    </div>

                    <div class="py-4">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Member Since</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </section>

            @if ($user->id !== auth()->id() && $user->role !== 'manager')
                <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-slate-900">Account Status</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            {{ $user->is_active ? 'Deactivating will prevent this employee from logging in or taking any action.' : 'Reactivating will restore full access for this employee.' }}
                        </p>
                    </div>

                    <div class="px-6 py-5">
                        <form method="POST" action="{{ route('manager.staff.toggle', $user) }}">
                            @csrf
                            @method('PATCH')

                            @if ($user->is_active)
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold text-white transition bg-red-600 hover:bg-red-700">
                                    Deactivate Employee
                                </button>
                            @else
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold text-white transition bg-green-600 hover:bg-green-700">
                                    Reactivate Employee
                                </button>
                            @endif
                        </form>
                    </div>
                </section>
            @endif

        </div>
    </div>
</x-app-layout>