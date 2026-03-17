<x-guest-layout>
    <div class="text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Reset password</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            Forgot your password? Enter your email address and we’ll send you a password reset link.
        </p>
    </div>

    <x-auth-session-status
        class="mt-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-2"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="space-y-4 pt-2">
            <x-primary-button class="w-full">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>

            <div class="text-center">
                <a
                    class="text-sm font-medium text-orange-600 transition hover:text-orange-700"
                    href="{{ route('login') }}"
                >
                    Back to login
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>