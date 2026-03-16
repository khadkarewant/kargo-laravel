<nav x-data="{ open: false }" class="border-b border-slate-200 bg-white">
    @php
        $user = Auth::user();
        $unreadCount = $user->notifications()->whereNull('read_at')->count();
    @endphp

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <div class="flex shrink-0 items-center">
                    @if ($user->isManager())
                        <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-3">
                    @elseif ($user->isEmployee())
                        <a href="{{ route('employee.dashboard') }}" class="flex items-center gap-3">
                    @else
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    @endif
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-slate-900 text-sm font-bold text-white">
                                K
                            </span>
                            <div class="leading-tight">
                                <div class="text-base font-semibold text-slate-900">Kargo</div>
                                <div class="text-xs text-slate-500">Cargo Operations Platform</div>
                            </div>
                        </a>
                </div>

                <div class="hidden items-center gap-2 md:flex">
                    @if ($user->isManager())
                        <a href="{{ route('manager.dashboard') }}"
                           class="{{ request()->routeIs('manager.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-lg px-3 py-2 text-sm font-medium transition">
                            Dashboard
                        </a>
                    @elseif ($user->isEmployee())
                        <a href="{{ route('employee.dashboard') }}"
                           class="{{ request()->routeIs('employee.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-lg px-3 py-2 text-sm font-medium transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="{{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-lg px-3 py-2 text-sm font-medium transition">
                            Dashboard
                        </a>
                    @endif

                    <a href="{{ route('notifications.index') }}"
                       class="{{ request()->routeIs('notifications.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium transition">
                        Notifications

                        @if ($unreadCount > 0)
                            <span class="ml-2 inline-flex min-w-[1.5rem] items-center justify-center rounded-full bg-red-600 px-2 py-0.5 text-xs font-semibold text-white">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 focus:outline-none">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-orange-500 text-sm font-bold text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div class="text-left leading-tight">
                                <div class="max-w-[140px] truncate font-semibold text-slate-800">
                                    {{ $user->name }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    @if ($user->isManager())
                                        Manager
                                    @elseif ($user->isEmployee())
                                        Employee
                                    @else
                                        Customer
                                    @endif
                                </div>
                            </div>

                            <svg class="h-4 w-4 fill-current text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-b border-slate-100 px-4 py-3">
                            <div class="text-sm font-semibold text-slate-800">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $user->email }}</div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-slate-700 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none">
                                Log Out
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center md:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden border-t border-slate-200 bg-white md:hidden">
        <div class="space-y-1 px-4 py-4">
            @if ($user->isManager())
                <a href="{{ route('manager.dashboard') }}"
                   class="{{ request()->routeIs('manager.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} block rounded-lg px-3 py-2 text-sm font-medium transition">
                    Dashboard
                </a>
            @elseif ($user->isEmployee())
                <a href="{{ route('employee.dashboard') }}"
                   class="{{ request()->routeIs('employee.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} block rounded-lg px-3 py-2 text-sm font-medium transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} block rounded-lg px-3 py-2 text-sm font-medium transition">
                    Dashboard
                </a>
            @endif

            <a href="{{ route('notifications.index') }}"
               class="{{ request()->routeIs('notifications.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition">
                <span>Notifications</span>

                @if ($unreadCount > 0)
                    <span class="inline-flex min-w-[1.5rem] items-center justify-center rounded-full bg-red-600 px-2 py-0.5 text-xs font-semibold text-white">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </div>

        <div class="border-t border-slate-200 px-4 py-4">
            <div class="mb-3">
                <div class="text-sm font-semibold text-slate-800">{{ $user->name }}</div>
                <div class="text-xs text-slate-500">{{ $user->email }}</div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>