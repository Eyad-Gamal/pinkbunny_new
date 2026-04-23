<x-guest-layout>
    <h1 class="font-serif text-3xl font-bold text-bunny-text dark:text-bunny-dark-text">{{ __('messages.auth.register_title') }}</h1>
    <p class="mt-2 text-sm text-bunny-muted dark:text-bunny-dark-muted">Create your account and start collecting your beauty favorites.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
        @csrf
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <input id="name" class="field" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <input id="email" class="field" type="email" name="email" value="{{ old('email') }}" autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <input id="phone" class="field" type="text" name="phone" value="{{ old('phone') }}" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            <x-input-error :messages="$errors->get('contact')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <input id="password" class="field" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <input id="password_confirmation" class="field" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>
        <label class="flex cursor-pointer items-start gap-3 text-sm text-bunny-muted dark:text-bunny-dark-muted">
            <input type="checkbox" name="terms" value="1" class="mt-0.5 rounded border-bunny-border text-bunny-primary focus:ring-bunny-primary/20 dark:border-bunny-dark-border">
            <span>I agree to the terms and privacy policy.</span>
        </label>
        <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        <button class="pill-btn-primary w-full">
            {{ __('Register') }}
        </button>
        <p class="text-center text-sm text-bunny-muted dark:text-bunny-dark-muted">
            Already have an account? <a class="font-semibold text-bunny-primary transition-colors hover:text-bunny-primary-dark" href="{{ route('login') }}">{{ __('Already registered?') }}</a>
        </p>
    </form>
</x-guest-layout>
