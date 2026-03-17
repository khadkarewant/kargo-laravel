<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kargo') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
        <div class="flex min-h-screen flex-col">
            <main class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
                <div class="w-full max-w-md">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="mb-8 text-center">
                            <a href="/" class="inline-flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-slate-900 text-sm font-bold text-white">
                                    K
                                </span>
                                <div class="text-left">
                                    <p class="text-base font-semibold text-slate-900">Kargo</p>
                                    <p class="text-xs text-slate-500">Cargo Operations Platform</p>
                                </div>
                            </a>
                        </div>

                        {{ $slot }}
                    </div>
                </div>
            </main>

            <footer class="border-t border-slate-200 bg-white">
                <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col gap-2 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                        <p>&copy; {{ date('Y') }} Kargo. All rights reserved.</p>
                        <p>Import, export, courier, and customs clearance support.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>