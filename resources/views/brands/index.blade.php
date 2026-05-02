@extends('layouts.app')

@section('title', __('messages.brands.title') . ' — Pink Bunny')

@section('content')
    <section class="page-shell">
        <div class="overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-bunny-accent via-bunny-primary-light/60 to-bunny-secondary-light px-8 py-12 shadow-soft dark:from-bunny-dark-card dark:via-bunny-dark-surface dark:to-bunny-dark-card">
            <h1 class="section-title">{{ __('messages.brands.title') }}</h1>
            <p class="mt-3 text-sm text-bunny-muted dark:text-bunny-dark-muted">{{ __('messages.brands.subtitle') }}</p>
        </div>
    </section>

    <section class="page-shell mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-4 animate-stagger">
        @foreach($brands as $brand)
            <a href="{{ route('brands.show', $brand) }}" class="card-surface-hover group p-6">
                <div class="flex h-20 items-center justify-center rounded-2xl bg-bunny-bg dark:bg-bunny-dark-card">
                    @php $brandLogo = $brand->logo ?: 'https://dummyimage.com/180x80/ffffff/D4577B&text=' . urlencode($brand->name); @endphp
                    <img src="{{ str_starts_with($brandLogo, 'http') ? $brandLogo : asset('storage/' . $brandLogo) }}" alt="{{ $brand->name }}" class="max-h-12 max-w-full object-contain transition-transform duration-300 group-hover:scale-105">
                </div>
                <h2 class="mt-5 font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text">{{ $brand->name }}</h2>
                <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-bunny-muted dark:text-bunny-dark-muted">{{ $brand->display_description ?: 'A curated beauty brand inside the Pink Bunny collection.' }}</p>
                <p class="mt-4 text-xs font-semibold text-bunny-primary">{{ __('messages.brands.products_count', ['count' => $brand->products_count]) }} →</p>
            </a>
        @endforeach
    </section>
@endsection
