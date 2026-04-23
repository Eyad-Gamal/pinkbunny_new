@extends('layouts.app')

@section('title', 'Addresses - Pink Bunny')

@section('content')
    <section class="page-shell">
        <h1 class="section-title">Addresses</h1>
        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_360px]">
            <div class="space-y-4">
                @forelse($addresses as $address)
                    <div class="card-surface p-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-[0.25em] text-bunny-primary">{{ $address->label }}</p>
                                <h2 class="mt-2 text-xl font-extrabold">{{ $address->full_name }}</h2>
                                <p class="mt-2 text-sm text-bunny-muted dark:text-slate-300">{{ $address->phone }}</p>
                                <p class="text-sm text-bunny-muted dark:text-slate-300">{{ $address->street }}, {{ $address->city }}, {{ $address->governorate }}</p>
                                @if($address->is_default)
                                    <span class="mt-3 inline-flex rounded-full bg-bunny-accent px-3 py-1 text-xs font-bold">Default</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('profile.addresses.default', $address) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="pill-btn border border-bunny-border px-4 py-2">Make default</button>
                                </form>
                                <form action="{{ route('profile.addresses.delete', $address) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pill-btn border border-red-200 px-4 py-2 text-red-600">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card-surface p-8 text-center text-bunny-muted">No addresses added yet.</div>
                @endforelse
            </div>

            <div class="card-surface p-6">
                <h2 class="text-2xl font-extrabold">Add new address</h2>
                <form action="{{ route('profile.addresses.store') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <input type="text" name="label" class="field mt-0" placeholder="Home / Work">
                    <input type="text" name="full_name" class="field mt-0" placeholder="Full name">
                    <input type="text" name="phone" class="field mt-0" placeholder="Phone">
                    <textarea name="street" rows="3" class="field mt-0" placeholder="Street"></textarea>
                    <input type="text" name="city" class="field mt-0" placeholder="City">
                    <input type="text" name="governorate" class="field mt-0" placeholder="Governorate">
                    <input type="text" name="country" value="Egypt" class="field mt-0" placeholder="Country">
                    <label class="flex items-center gap-3 text-sm"><input type="checkbox" name="is_default" value="1"> Set as default</label>
                    <button type="submit" class="pill-btn w-full bg-bunny-primary px-6 py-3 text-white">Save address</button>
                </form>
            </div>
        </div>
    </section>
@endsection
