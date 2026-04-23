<x-guest-layout>
    <p class="text-sm text-bunny-muted dark:text-bunny-dark-muted">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    <x-auth-session-status class="mb-4 mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
        @csrf
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <input id="email" class="field" type="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <button class="pill-btn-primary w-full">{{ __('Email Password Reset Link') }}</button>
    </form>
</x-guest-layout>
