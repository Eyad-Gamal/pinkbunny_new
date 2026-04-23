@props(['slides'])

@php $total = count($slides); @endphp

@if($total > 0)
<div class="hero-slider" x-data="heroSlider({{ $total }})" x-init="start()">
    <div class="hero-slider-inner">
        @foreach($slides as $index => $slide)
            @php
                $img1 = $slide->image_1 ? (str_starts_with($slide->image_1, 'http') ? $slide->image_1 : asset('storage/'.$slide->image_1)) : null;
                $img2 = $slide->image_2 ? (str_starts_with($slide->image_2, 'http') ? $slide->image_2 : asset('storage/'.$slide->image_2)) : null;
                $img3 = $slide->image_3 ? (str_starts_with($slide->image_3, 'http') ? $slide->image_3 : asset('storage/'.$slide->image_3)) : null;
            @endphp
            <div class="hero-slide"
                 x-show="current === {{ $index }}"
                 x-transition:enter="slide-enter"
                 x-transition:enter-start="slide-enter-start"
                 x-transition:enter-end="slide-enter-end"
                 x-transition:leave="slide-leave"
                 x-transition:leave-start="slide-leave-start"
                 x-transition:leave-end="slide-leave-end"
                 style="background: linear-gradient(135deg, {{ $slide->bg_color_from }}, {{ $slide->bg_color_to }}); color: {{ $slide->text_color }};">

                {{-- Decorative circles --}}
                <div class="hero-decor-1" style="background: {{ $slide->bg_color_from }};"></div>
                <div class="hero-decor-2" style="background: {{ $slide->bg_color_to }};"></div>

                <div class="hero-slide-grid">
                    {{-- Text Content --}}
                    <div class="hero-text-col">
                        @if($slide->badge_text)
                            <span class="hero-badge" style="color: {{ $slide->text_color }};">{{ $slide->badge_text }}</span>
                        @endif
                        <h1 class="hero-title">{{ $slide->display_title }}</h1>
                        @if($slide->display_subtitle)
                            <p class="hero-subtitle">{{ $slide->display_subtitle }}</p>
                        @endif
                        <div class="hero-cta">
                            <a href="{{ $slide->button_link }}" class="hero-btn" style="background: {{ $slide->text_color }}; color: {{ $slide->bg_color_from }};">
                                {{ $slide->button_text }}
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Images --}}
                    <div class="hero-images-col">
                        @if($img1)
                            <img src="{{ $img1 }}" alt="" class="hero-img hero-img-1" loading="eager">
                        @endif
                        @if($img2)
                            <img src="{{ $img2 }}" alt="" class="hero-img hero-img-2" loading="eager">
                        @endif
                        @if($img3)
                            <img src="{{ $img3 }}" alt="" class="hero-img hero-img-3" loading="eager">
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Dots --}}
    @if($total > 1)
        <div class="hero-dots">
            @for($i = 0; $i < $total; $i++)
                <button @click="goTo({{ $i }})" class="hero-dot" :class="{ 'active': current === {{ $i }} }" aria-label="Slide {{ $i + 1 }}">
                    <span class="hero-dot-fill" x-show="current === {{ $i }}" x-transition></span>
                </button>
            @endfor
        </div>

        {{-- Navigation Arrows --}}
        <button @click="prev()" class="hero-arrow hero-arrow-prev" aria-label="Previous slide">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click="next()" class="hero-arrow hero-arrow-next" aria-label="Next slide">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>
    @endif
</div>

<style>
    .hero-slider {
        position: relative;
        width: 100%;
        border-radius: 2.5rem;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
    .hero-slider-inner { position: relative; min-height: 520px; }
    @media (max-width: 768px) { .hero-slider-inner { min-height: 640px; } }

    .hero-slide {
        position: absolute;
        inset: 0;
        overflow: hidden;
    }

    /* Decorative blobs */
    .hero-decor-1 {
        position: absolute;
        width: 300px; height: 300px;
        border-radius: 50%;
        top: -80px; right: -60px;
        opacity: 0.15;
        filter: blur(60px);
    }
    .hero-decor-2 {
        position: absolute;
        width: 250px; height: 250px;
        border-radius: 50%;
        bottom: -60px; left: -40px;
        opacity: 0.2;
        filter: blur(50px);
    }

    .hero-slide-grid {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 2rem;
        height: 100%;
        padding: 3.5rem 4rem;
        align-items: center;
    }
    @media (max-width: 1024px) {
        .hero-slide-grid { grid-template-columns: 1fr; padding: 2.5rem 2rem; }
    }

    .hero-text-col { display: flex; flex-direction: column; justify-content: center; }

    .hero-badge {
        display: inline-block;
        width: fit-content;
        padding: 5px 16px;
        border-radius: 20px;
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(8px);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.5px;
        animation: fadeSlideDown 0.6s ease-out both;
        animation-delay: 0.1s;
    }

    .hero-title {
        margin-top: 1.2rem;
        font-family: 'Nunito', sans-serif;
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -0.5px;
        animation: fadeSlideUp 0.7s ease-out both;
        animation-delay: 0.2s;
    }

    .hero-subtitle {
        margin-top: 1rem;
        font-size: clamp(0.95rem, 2vw, 1.15rem);
        font-weight: 600;
        opacity: 0.75;
        max-width: 480px;
        line-height: 1.6;
        animation: fadeSlideUp 0.7s ease-out both;
        animation-delay: 0.35s;
    }

    .hero-cta {
        margin-top: 1.8rem;
        animation: fadeSlideUp 0.7s ease-out both;
        animation-delay: 0.5s;
    }

    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 32px;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 800;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }
    .hero-btn:hover { transform: translateY(-2px) scale(1.03); box-shadow: 0 8px 30px rgba(0,0,0,0.18); }
    .hero-btn:active { transform: scale(0.97); }

    /* Floating Images */
    .hero-images-col {
        position: relative;
        min-height: 420px;
    }
    @media (max-width: 1024px) {
        .hero-images-col { display: none; }
    }

    .hero-img {
        position: absolute;
        object-fit: cover;
        border-radius: 1.5rem;
        border: 5px solid rgba(255,255,255,0.6);
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .hero-img:hover { transform: scale(1.06) !important; z-index: 40 !important; }

    .hero-img-1 {
        width: 180px; height: 240px;
        bottom: 40px; left: 0;
        z-index: 10;
        transform: rotate(-6deg);
        animation: floatImg1 0.8s ease-out both, floatBounce1 3s ease-in-out 1s infinite;
    }
    .hero-img-2 {
        width: 240px; height: 310px;
        top: 20px; right: 40px;
        z-index: 20;
        border-width: 6px;
        animation: floatImg2 0.8s ease-out both, floatBounce2 3.5s ease-in-out 1.2s infinite;
    }
    .hero-img-3 {
        width: 160px; height: 220px;
        bottom: 60px; right: 0;
        z-index: 30;
        transform: rotate(8deg);
        animation: floatImg3 0.8s ease-out both, floatBounce3 4s ease-in-out 1.4s infinite;
    }

    /* Dots */
    .hero-dots {
        position: absolute;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 50;
    }
    .hero-dot {
        width: 12px; height: 12px;
        border-radius: 50%;
        background: rgba(255,255,255,0.4);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: all 0.3s;
    }
    .hero-dot.active { width: 36px; border-radius: 6px; background: rgba(255,255,255,0.6); }
    .hero-dot-fill {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.95);
        border-radius: inherit;
        animation: dotProgress 6s linear;
    }

    /* Arrows */
    .hero-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 50;
        width: 42px; height: 42px;
        border-radius: 50%;
        border: none;
        background: rgba(255,255,255,0.5);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: rgba(0,0,0,0.6);
        opacity: 0;
    }
    .hero-slider:hover .hero-arrow { opacity: 1; }
    .hero-arrow:hover { background: rgba(255,255,255,0.85); transform: translateY(-50%) scale(1.1); }
    .hero-arrow-prev { left: 16px; }
    .hero-arrow-next { right: 16px; }

    /* Transition classes */
    .slide-enter { transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
    .slide-enter-start { opacity: 0; transform: translateX(80px) scale(0.97); }
    .slide-enter-end { opacity: 1; transform: translateX(0) scale(1); }
    .slide-leave { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    .slide-leave-start { opacity: 1; transform: translateX(0) scale(1); }
    .slide-leave-end { opacity: 0; transform: translateX(-80px) scale(0.97); }

    /* Keyframes */
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeSlideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes floatImg1 {
        from { opacity: 0; transform: translateY(60px) rotate(-20deg) scale(0.6); }
        to   { opacity: 1; transform: translateY(0) rotate(-6deg) scale(1); }
    }
    @keyframes floatImg2 {
        from { opacity: 0; transform: translateY(-80px) scale(0.6); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes floatImg3 {
        from { opacity: 0; transform: translateY(60px) rotate(20deg) scale(0.6); }
        to   { opacity: 1; transform: translateY(0) rotate(8deg) scale(1); }
    }
    @keyframes floatBounce1 {
        0%, 100% { transform: translateY(0) rotate(-6deg); }
        50%      { transform: translateY(-18px) rotate(-3deg); }
    }
    @keyframes floatBounce2 {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-22px); }
    }
    @keyframes floatBounce3 {
        0%, 100% { transform: translateY(0) rotate(8deg); }
        50%      { transform: translateY(-15px) rotate(12deg); }
    }
    @keyframes dotProgress {
        from { width: 0; }
        to   { width: 100%; }
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('heroSlider', (total) => ({
            current: 0,
            total: total,
            timer: null,
            duration: 6000,

            start() {
                if (this.total <= 1) return;
                this.autoplay();
            },

            autoplay() {
                clearInterval(this.timer);
                this.timer = setInterval(() => this.next(), this.duration);
            },

            next() {
                this.current = (this.current + 1) % this.total;
                this.autoplay();
            },

            prev() {
                this.current = (this.current - 1 + this.total) % this.total;
                this.autoplay();
            },

            goTo(index) {
                this.current = index;
                this.autoplay();
            }
        }));
    });
</script>
@endif
