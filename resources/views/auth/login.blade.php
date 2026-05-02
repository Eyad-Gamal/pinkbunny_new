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
        <div x-data="{ show: false }" class="relative">
            <x-input-label for="password" :value="__('Password')" />
            <input id="password" class="field pr-10" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" />
            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 top-6 flex items-center pr-3 text-bunny-muted hover:text-bunny-primary">
                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
            </button>
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
