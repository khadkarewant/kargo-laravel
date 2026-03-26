<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-orange-600">Manager Workspace</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Staff List
                </h2>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('manager.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Back to Dashboard
                </a>

                <a href="{{ route('manager.staff.create') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600">
                    Create Employee
                </a>
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

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Employee Accounts</h3>
                        <p class="mt-1 text-sm text-slate-600">
                            Review current staff members and manage employee access from the manager workspace.
                        </p>
                    </div>
                </div>

                @if($staff->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Created At
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse ($staff as $user)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                                            <a href="{{ route('manager.staff.show', $user) }}" class="hover:text-orange-600">
                                                {{ $user->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-700">
                                            {{ $user->email }}
                                        </td>

                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($user->is_active)
                                                <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-green-200">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-200">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                            {{ $user->created_at }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="" class="px-6 py-12 text-center">
                                            <div class="mx-auto max-w-md">
                                                <h3 class="text-lg font-semibold text-slate-900">No staff found</h3>
                                                <p class="mt-2 text-sm text-slate-600">
                                                    No employee accounts are available yet.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>