<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Kargo') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
        <div x-data="{ open: false }" class="min-h-screen flex flex-col">
            <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <a href="{{ url('/') }}" class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-slate-900 text-sm font-bold text-white shadow-sm">
                                K
                            </span>
                            <div class="leading-tight">
                                <div class="text-base font-semibold tracking-tight text-slate-900">Kargo</div>
                                <div class="text-xs text-slate-500">Cargo Operations Platform</div>
                            </div>
                        </a>

                        <nav class="hidden md:flex md:items-center md:gap-2">
                            <a href="{{ url('/') }}"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-orange-500">
                                Home
                            </a>
                            <a href="#services"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-orange-500">
                                Services
                            </a>
                            <a href="#tracking"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-orange-500">
                                Tracking
                            </a>
                            <a href="#get-started"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-orange-500">
                                Get Started
                            </a>
                        </nav>

                        <div class="hidden items-center gap-3 md:flex">
                            @auth
                                <a href="{{ route('dashboard') }}"
                                   class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="text-sm font-medium text-slate-700 transition hover:text-orange-500">
                                    Log in
                                </a>

                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600">
                                    Get Started
                                </a>
                            @endauth
                        </div>

                        <button
                            @click="open = !open"
                            class="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 md:hidden"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div :class="{ 'block': open, 'hidden': !open }" class="hidden border-t border-slate-200 bg-white md:hidden">
                    <div class="space-y-1 px-4 py-4">
                        <a href="{{ url('/') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-slate-900">
                            Home
                        </a>
                        <a href="#services" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-slate-900">
                            Services
                        </a>
                        <a href="#tracking" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-slate-900">
                            Tracking
                        </a>
                        <a href="#get-started" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-slate-900">
                            Get Started
                        </a>
                    </div>

                    <div class="border-t border-slate-200 px-4 py-4">
                        <div class="flex flex-col gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}"
                                   class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                    Log in
                                </a>

                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center justify-center rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600">
                                    Get Started
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1">
                @yield('content')
            </main>

            <footer class="border-t border-slate-200 bg-white">
                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <div class="flex flex-col gap-8 md:flex-row md:items-start md:justify-between">
                        <div class="max-w-md">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-slate-900 text-sm font-bold text-white">
                                    K
                                </span>
                                <div>
                                    <p class="text-base font-semibold text-slate-900">Kargo</p>
                                    <p class="text-xs text-slate-500">Cargo Operations Platform</p>
                                </div>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-slate-600">
                                Reliable support for import, export, courier, and customs clearance requests with structured workflows and tracking visibility.
                            </p>
                        </div>

                        <div class="grid gap-8 sm:grid-cols-2">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Quick Links</h3>
                                <div class="mt-3 space-y-2">
                                    <a href="{{ url('/') }}" class="block text-sm text-slate-600 transition hover:text-orange-500">Home</a>
                                    <a href="#services" class="block text-sm text-slate-600 transition hover:text-orange-500">Services</a>
                                    <a href="#tracking" class="block text-sm text-slate-600 transition hover:text-orange-500">Tracking</a>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Access</h3>
                                <div class="mt-3 space-y-2">
                                    <a href="{{ route('login') }}" class="block text-sm text-slate-600 transition hover:text-orange-500">Log in</a>
                                    <a href="{{ route('register') }}" class="block text-sm text-slate-600 transition hover:text-orange-500">Create account</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-slate-200 pt-6 text-sm text-slate-500">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <p>&copy; {{ date('Y') }} Kargo. All rights reserved.</p>
                            <p>Import, export, courier, and customs clearance support.</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>