<x-guest-layout>
    <h1 class="font-serif text-3xl font-bold text-bunny-text dark:text-bunny-dark-text">{{ __('messages.auth.login_title') }}</h1>
    <p class="mt-2 text-sm text-bunny-muted dark:text-bunny-dark-muted">Sign in with your email or phone number.</p>

    <x-auth-session-status class="mb-4 mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf
        <div>
            <x-input-label for="login" :value="__('Email or phone')" />
            <input id="login" class="field" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <input id="password" class="field" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="inline-flex cursor-pointer items-center gap-2 text-bunny-muted dark:text-bunny-dark-muted">
                <input id="remember_me" type="checkbox" class="rounded border-bunny-border text-bunny-primary focus:ring-bunny-primary/20 dark:border-bunny-dark-border" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
            <a class="font-semibold text-bunny-primary transition-colors hover:text-bunny-primary-dark" href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
        </div>
        <button class="pill-btn-primary w-full">
            {{ __('Log in') }}
        </button>
        <p class="text-center text-sm text-bunny-muted dark:text-bunny-dark-muted">
            Don't have an account? <a class="font-semibold text-bunny-primary transition-colors hover:text-bunny-primary-dark" href="{{ route('register') }}">{{ __('Create account') }}</a>
        </p>
    </form>
</x-guest-layout>
