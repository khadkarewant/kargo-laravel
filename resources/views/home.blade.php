@extends('layouts.guest-public')

@section('content')

    <section class="bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <span class="inline-flex items-center rounded-full bg-orange-500/10 px-3 py-1 text-sm font-medium text-orange-300 ring-1 ring-orange-400/20">
                        Trusted cargo and customs support
                    </span>

                    <h1 class="mt-6 text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Reliable import, export, courier, and customs clearance services
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                        Kargo helps individuals and businesses manage cargo requests with clear service intake,
                        professional handling, and shipment tracking visibility.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center rounded-lg bg-orange-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-orange-600">
                            Request a Service
                        </a>

                        <a href="#tracking"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                            Track Shipment
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="rounded-xl border border-slate-700 bg-slate-800 p-4">
                            <p class="text-sm font-semibold text-white">Import Support</p>
                            <p class="mt-1 text-sm text-slate-300">End-to-end request handling for incoming cargo.</p>
                        </div>

                        <div class="rounded-xl border border-slate-700 bg-slate-800 p-4">
                            <p class="text-sm font-semibold text-white">Export Assistance</p>
                            <p class="mt-1 text-sm text-slate-300">Structured processing for outgoing shipments.</p>
                        </div>

                        <div class="rounded-xl border border-slate-700 bg-slate-800 p-4">
                            <p class="text-sm font-semibold text-white">Customs Clearance</p>
                            <p class="mt-1 text-sm text-slate-300">Practical support for documentation and clearance flow.</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-700 bg-white p-6 shadow-sm lg:p-8">
                    <div class="rounded-2xl bg-slate-100 p-6">
                        <p class="text-sm font-medium text-orange-600">Why businesses choose Kargo</p>
                        <h2 class="mt-3 text-2xl font-semibold text-slate-900">
                            A professional cargo service platform built for real operations
                        </h2>
                        <p class="mt-4 text-sm leading-7 text-slate-600">
                            Submit service requests, receive operational support, and monitor request progress through a clear and structured workflow.
                        </p>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <h3 class="text-sm font-semibold text-slate-900">Clear request intake</h3>
                            <p class="mt-1 text-sm text-slate-600">
                                Customers can submit import, export, courier, or clearance requests through one structured system.
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <h3 class="text-sm font-semibold text-slate-900">Operational visibility</h3>
                            <p class="mt-1 text-sm text-slate-600">
                                Employees process requests while managers review and approve them through an organized workflow.
                            </p>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <h3 class="text-sm font-semibold text-slate-900">Tracking transparency</h3>
                            <p class="mt-1 text-sm text-slate-600">
                                Customers can use a tracking ID to check shipment or request progress without calling every time.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wide text-orange-600">Our Services</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                    Business-focused logistics services for everyday cargo needs
                </h2>
                <p class="mt-4 text-base leading-7 text-slate-600">
                    We focus on practical service delivery: collecting requests, coordinating operations, and keeping customers informed.
                </p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Import Services</h3>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Support for clients bringing goods into the country with structured request handling and coordination.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Export Services</h3>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Organized export request processing for businesses and individuals shipping goods outward.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Courier Support</h3>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Courier-related handling for clients who need dependable service intake and follow-up.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Customs Clearance</h3>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Practical assistance for customs-related workflows where clarity and progress visibility matter.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="get-started" class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wide text-orange-600">How It Works</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                    A simple process built around real cargo operations
                </h2>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white">1</div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Submit a request</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Customers submit a service request with the necessary details through the platform.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white">2</div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Operations team handles it</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Employees process the request and managers review key decisions in the workflow.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white">3</div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Track progress clearly</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Customers can follow progress through tracking visibility while communication can continue offline when needed.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="tracking" class="bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-orange-400">Shipment Tracking</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-white">
                        Track your shipment or request using your tracking ID
                    </h2>
                    <p class="mt-4 text-base leading-7 text-slate-300">
                        Enter your tracking ID below to check the current status and progress updates.
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <form action="{{ route('tracking.show') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="tracking_id" class="mb-2 block text-sm font-medium text-slate-700">
                                Tracking ID
                            </label>
                            <input
                                type="text"
                                name="tracking_id"
                                id="tracking_id"
                                value="{{ old('tracking_id') }}"
                                placeholder="Enter your tracking ID"
                                required
                                class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                            >
                            @error('tracking_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-orange-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-orange-600">
                            Track Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 lg:p-12">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-wide text-orange-600">Get Started</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                        Need help with cargo, courier, export, or customs clearance?
                    </h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        Create an account to submit a service request and manage your requests through Kargo’s workflow.
                    </p>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-orange-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-orange-600">
                        Create Account
                    </a>

                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Log In
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection