@extends('layouts.app')

@section('title', __('messages.profile.title') . ' - Pink Bunny')

@section('content')
    <section class="page-shell grid gap-8 lg:grid-cols-[260px_minmax(0,1fr)]">
        <aside class="card-surface h-fit p-6">
            <div class="text-center">
                @if($user->avatar)
                    <div class="mx-auto flex h-24 w-24 items-center justify-center overflow-hidden rounded-full shadow-soft">
                        <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    </div>
                @else
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-bunny-accent text-3xl font-extrabold text-bunny-text">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <h2 class="mt-4 text-xl font-extrabold">{{ $user->name }}</h2>
                <p class="text-sm text-bunny-muted dark:text-slate-300">{{ $user->email ?: $user->phone }}</p>
                <span class="mt-4 inline-flex rounded-full bg-bunny-primary px-4 py-2 text-sm font-bold text-white">{{ number_format((float) $user->points_balance, 2) }} pts</span>
            </div>
            <div class="mt-6 space-y-2 text-sm font-bold">
                <a href="{{ route('profile.index') }}" class="block rounded-2xl bg-bunny-bg px-4 py-3 dark:bg-bunny-dark-card">{{ __('messages.profile.title') }}</a>
                <a href="{{ route('profile.addresses') }}" class="block rounded-2xl px-4 py-3 hover:bg-bunny-bg dark:hover:bg-bunny-dark-card">Addresses</a>
                <a href="{{ route('settings.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-bunny-bg dark:hover:bg-bunny-dark-card">{{ __('messages.nav.settings') }}</a>
            </div>
        </aside>

        <div class="card-surface p-8">
            <h1 class="section-title">{{ __('messages.profile.title') }}</h1>
            <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                @csrf
                <label class="block text-sm font-bold">Avatar</label>
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <input type="file" name="avatar" class="field mt-0 max-w-sm">
                    <button type="submit" class="pill-btn border border-bunny-border px-5 py-3">Upload</button>
                </div>
            </form>

            <form action="{{ route('profile.update') }}" method="POST" class="mt-8 grid gap-4 md:grid-cols-2">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-sm font-bold">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="field">
                </div>
                <div>
                    <label class="text-sm font-bold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="field">
                </div>
                <div>
                    <label class="text-sm font-bold">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="field">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="pill-btn bg-bunny-primary px-6 py-3 text-white">Save changes</button>
                </div>
            </form>
        </div>
    </section>
@endsection
