<x-guest-layout>
    <div class="text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Create account</h1>
        <p class="mt-2 text-sm text-slate-600">
            Register to submit and track your service requests in Kargo.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                class="mt-2"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-2"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input
                id="password"
                class="mt-2"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <p class="mt-2 text-sm text-slate-500">
                Password must be at least 8 characters long.
            </p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input
                id="password_confirmation"
                class="mt-2"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="space-y-4 pt-2">
            <x-primary-button class="w-full">
                {{ __('Register') }}
            </x-primary-button>

            <div class="text-center">
                <a
                    class="text-sm font-medium text-orange-600 transition hover:text-orange-700"
                    href="{{ route('login') }}"
                >
                    {{ __('Already registered? Log in') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>