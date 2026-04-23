<x-guest-layout>
    <p class="text-sm text-bunny-muted dark:text-bunny-dark-muted">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-5">
        @csrf
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <input id="password" class="field" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <button class="pill-btn-primary w-full">{{ __('Confirm') }}</button>
    </form>
</x-guest-layout>
