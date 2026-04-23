@extends('layouts.app')

@section('title', 'About — Pink Bunny')

@section('content')
    {{-- Hero --}}
    <section class="page-shell">
        <div class="overflow-hidden rounded-[2.5rem] shadow-elevated lg:grid lg:grid-cols-[1fr_0.85fr]">
            <div class="bg-gradient-to-r from-bunny-secondary to-bunny-primary p-8 lg:p-14">
                <p class="text-xs font-bold uppercase tracking-[0.3em] text-bunny-text/60">The Pink Bunny Story</p>
                <h1 class="mt-5 font-sans text-4xl font-extrabold leading-tight text-bunny-text sm:text-5xl">Beauty that feels soft, safe, and joyful.</h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-bunny-text/80">Pink Bunny Beauty was imagined as a pastel-first destination for skincare, haircare, and makeup lovers who want authentic products and a playful shopping experience.</p>
            </div>
            <img src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=1200&q=80" alt="About Pink Bunny" class="h-full w-full object-cover">
        </div>
    </section>

    {{-- Values --}}
    <section class="page-shell mt-16 grid gap-6 lg:grid-cols-3 animate-stagger">
        @foreach([
            ['icon' => '🐰', 'title' => 'Cruelty free', 'text' => 'We celebrate brands that respect animals and conscious beauty choices.'],
            ['icon' => '✨', 'title' => 'Ingredient-first', 'text' => 'We curate by formulas, not hype alone, so your routine feels intentional.'],
            ['icon' => '💫', 'title' => 'Confidence-led', 'text' => 'Every page is built to make beauty shopping easier, warmer, and more empowering.'],
        ] as $value)
            <div class="card-surface-hover p-8">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-bunny-accent text-2xl dark:bg-bunny-dark-card">{{ $value['icon'] }}</div>
                <h2 class="mt-5 font-sans text-2xl font-extrabold text-bunny-text dark:text-bunny-dark-text">{{ $value['title'] }}</h2>
                <p class="mt-3 text-sm leading-relaxed text-bunny-muted dark:text-bunny-dark-muted">{{ $value['text'] }}</p>
            </div>
        @endforeach
    </section>
@endsection
