@extends('layouts.app')

@section('title', __('messages.settings.title') . ' — Pink Bunny')

@section('content')
    <section class="page-shell">
        <div class="card-surface max-w-3xl p-8 lg:p-10">
            <h1 class="section-title">{{ __('messages.settings.title') }}</h1>
            <p class="section-subtitle">Customize your experience</p>

            <form action="{{ route('settings.update') }}" method="POST" class="mt-8 space-y-8">
                @csrf
                @method('PUT')

                {{-- Language --}}
                <div>
                    <h2 class="font-serif text-lg font-semibold text-bunny-text dark:text-bunny-dark-text">Language</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-bunny-border p-4 transition-all has-[:checked]:border-bunny-primary has-[:checked]:shadow-soft dark:border-bunny-dark-border dark:has-[:checked]:border-bunny-primary">
                            <input type="radio" name="preferred_language" value="en" class="text-bunny-primary focus:ring-bunny-primary/20" @checked($user->preferred_language === 'en')>
                            <span class="font-medium">English</span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-bunny-border p-4 transition-all has-[:checked]:border-bunny-primary has-[:checked]:shadow-soft dark:border-bunny-dark-border dark:has-[:checked]:border-bunny-primary">
                            <input type="radio" name="preferred_language" value="ar" class="text-bunny-primary focus:ring-bunny-primary/20" @checked($user->preferred_language === 'ar')>
                            <span class="font-medium">العربية</span>
                        </label>
                    </div>
                </div>

                <div class="divider"></div>

                {{-- Theme --}}
                <div>
                    <h2 class="font-serif text-lg font-semibold text-bunny-text dark:text-bunny-dark-text">Theme</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-bunny-border p-4 transition-all has-[:checked]:border-bunny-primary has-[:checked]:shadow-soft dark:border-bunny-dark-border dark:has-[:checked]:border-bunny-primary">
                            <input type="radio" name="preferred_theme" value="light" class="text-bunny-primary focus:ring-bunny-primary/20" @checked($user->preferred_theme === 'light')>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-bunny-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /></svg>
                                <span class="font-medium">Light</span>
                            </div>
                        </label>
                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-bunny-border p-4 transition-all has-[:checked]:border-bunny-primary has-[:checked]:shadow-soft dark:border-bunny-dark-border dark:has-[:checked]:border-bunny-primary">
                            <input type="radio" name="preferred_theme" value="dark" class="text-bunny-primary focus:ring-bunny-primary/20" @checked($user->preferred_theme === 'dark')>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-bunny-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
                                <span class="font-medium">Dark</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="pill-btn-primary">Save preferences</button>
            </form>
        </div>
    </section>
@endsection
