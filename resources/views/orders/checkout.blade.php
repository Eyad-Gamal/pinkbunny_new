@extends('layouts.app')

@section('title', __('messages.orders.checkout') . ' — Pink Bunny')

@section('content')
    <section class="page-shell">
        <h1 class="section-title">{{ __('messages.orders.checkout') }}</h1>
        <p class="section-subtitle">أكملي طلبك</p>

        <form action="{{ route('order.place') }}" method="POST" data-no-swup class="mt-8 grid gap-8 lg:grid-cols-[1.2fr_0.8fr]"
              x-data="checkoutForm()" x-cloak>
            @csrf
            @if(session('coupon_code'))
                <input type="hidden" name="coupon_code" value="{{ session('coupon_code') }}">
            @endif

            @if ($errors->any())
                <div class="lg:col-span-2 rounded-2xl border border-red-200 bg-red-50 p-4 dark:border-red-500/20 dark:bg-red-500/10">
                    <ul class="list-inside list-disc space-y-1 text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="lg:col-span-2 rounded-2xl border border-red-200 bg-red-50 p-4 dark:border-red-500/20 dark:bg-red-500/10">
                    <p class="text-sm text-red-600 dark:text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            <div class="space-y-6">
                {{-- ═══ Shipping Address ═══ --}}
                <div class="card-surface p-6 lg:p-8">
                    <h2 class="font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text flex items-center gap-2">
                        <span class="text-2xl">📍</span> عنوان الشحن
                    </h2>

                    @if($addresses->isNotEmpty())
                        <div class="mt-5 space-y-3">
                            @foreach($addresses as $address)
                                <label class="flex cursor-pointer gap-4 rounded-2xl border border-bunny-border p-4 transition-all has-[:checked]:border-bunny-primary has-[:checked]:shadow-soft dark:border-bunny-dark-border dark:has-[:checked]:border-bunny-primary">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" class="mt-1 text-bunny-primary focus:ring-bunny-primary/20" @checked(optional($defaultAddress)->id === $address->id || old('address_id') == $address->id)>
                                    <span class="text-sm">
                                        <span class="block font-semibold text-bunny-text dark:text-bunny-dark-text">{{ $address->label }}</span>
                                        <span class="block text-bunny-muted dark:text-bunny-dark-muted">{{ $address->full_name }} · {{ $address->phone }}</span>
                                        <span class="block text-bunny-muted dark:text-bunny-dark-muted">{{ $address->street }}, {{ $address->city }}, {{ $address->governorate }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-4 flex items-center gap-3 text-sm text-bunny-muted dark:text-bunny-dark-muted">
                            <div class="h-px flex-1 bg-bunny-border dark:bg-bunny-dark-border"></div>
                            <span>أو أضيفي عنوان جديد</span>
                            <div class="h-px flex-1 bg-bunny-border dark:bg-bunny-dark-border"></div>
                        </div>
                    @endif

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <input type="text" name="label" value="{{ old('label') }}" class="field mt-0" placeholder="اسم العنوان (مثال: البيت)">
                        <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" class="field mt-0" placeholder="الاسم بالكامل">
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="field mt-0" placeholder="رقم الموبايل">

                        {{-- Governorate Dropdown --}}
                        <select name="governorate" class="field mt-0" x-model="governorate" @change="fetchAreas()">
                            <option value="">— اختاري المحافظة —</option>
                            @foreach(array_keys(config('egypt', [])) as $gov)
                                <option value="{{ $gov }}" @selected(old('governorate') === $gov)>{{ $gov }}</option>
                            @endforeach
                        </select>

                        {{-- Area/City Dropdown --}}
                        <select name="city" class="field mt-0" x-model="city" :disabled="areas.length === 0">
                            <option value="">— اختاري المنطقة —</option>
                            <template x-for="area in areas" :key="area">
                                <option :value="area" x-text="area" :selected="area === '{{ old('city') }}'"></option>
                            </template>
                        </select>

                        {{-- Country fixed --}}
                        <input type="hidden" name="country" value="Egypt">
                        <div class="field mt-0 flex items-center gap-2 bg-bunny-bg/50 dark:bg-bunny-dark-bg/50 cursor-not-allowed opacity-75">
                            <span>🇪🇬</span> <span>مصر</span>
                        </div>

                        <textarea name="street" rows="3" class="field mt-0 md:col-span-2" placeholder="العنوان بالتفصيل (الشارع، رقم العمارة، الدور)">{{ old('street') }}</textarea>
                    </div>
                </div>

                {{-- ═══ Payment Method ═══ --}}
                <div class="card-surface p-6 lg:p-8">
                    <h2 class="font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text flex items-center gap-2">
                        <span class="text-2xl">💳</span> طريقة الدفع
                    </h2>
                    <div class="mt-5 grid gap-3">
                        {{-- Cash on Delivery --}}
                        <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-bunny-border p-4 transition-all has-[:checked]:border-bunny-primary has-[:checked]:shadow-soft dark:border-bunny-dark-border dark:has-[:checked]:border-bunny-primary">
                            <input type="radio" name="payment_method" value="cash_on_delivery" class="text-bunny-primary focus:ring-bunny-primary/20" x-model="paymentMethod" @checked(old('payment_method', 'cash_on_delivery') === 'cash_on_delivery')>
                            <div>
                                <span class="text-sm font-semibold flex items-center gap-2">💵 كاش عند الاستلام</span>
                                <span class="text-xs text-bunny-muted dark:text-bunny-dark-muted">ادفعي عند استلام الأوردر</span>
                            </div>
                        </label>

                        {{-- InstaPay --}}
                        <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-bunny-border p-4 transition-all has-[:checked]:border-bunny-primary has-[:checked]:shadow-soft dark:border-bunny-dark-border dark:has-[:checked]:border-bunny-primary">
                            <input type="radio" name="payment_method" value="instapay" class="text-bunny-primary focus:ring-bunny-primary/20" x-model="paymentMethod" @checked(old('payment_method') === 'instapay')>
                            <div>
                                <span class="text-sm font-semibold flex items-center gap-2">📱 إنستاباي</span>
                                <span class="text-xs text-bunny-muted dark:text-bunny-dark-muted">تحويل فوري عبر إنستاباي</span>
                            </div>
                        </label>

                        {{-- Vodafone Cash --}}
                        <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-bunny-border p-4 transition-all has-[:checked]:border-bunny-primary has-[:checked]:shadow-soft dark:border-bunny-dark-border dark:has-[:checked]:border-bunny-primary">
                            <input type="radio" name="payment_method" value="vodafone_cash" class="text-bunny-primary focus:ring-bunny-primary/20" x-model="paymentMethod" @checked(old('payment_method') === 'vodafone_cash')>
                            <div>
                                <span class="text-sm font-semibold flex items-center gap-2">📲 فودافون كاش</span>
                                <span class="text-xs text-bunny-muted dark:text-bunny-dark-muted">تحويل عبر فودافون كاش</span>
                            </div>
                        </label>
                    </div>

                    {{-- Payment Instructions (shown for instapay/vodafone) --}}
                    <div x-show="paymentMethod === 'instapay' || paymentMethod === 'vodafone_cash'"
                         x-transition
                         class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-500/20 dark:bg-amber-500/10">

                        {{-- InstaPay Instructions --}}
                        <div x-show="paymentMethod === 'instapay'" class="space-y-3">
                            <p class="text-sm font-bold text-amber-800 dark:text-amber-300">📱 تعليمات الدفع بـ إنستاباي:</p>
                            <div class="rounded-xl bg-white/80 p-4 dark:bg-black/20 space-y-2 text-sm">
                                <p><span class="font-semibold">الحساب:</span> <code class="bg-bunny-accent px-2 py-0.5 rounded font-mono text-bunny-primary">eyadsaleh1@instapay</code></p>
                                <p><span class="font-semibold">رقم التحويل:</span> <code class="bg-bunny-accent px-2 py-0.5 rounded font-mono text-bunny-primary">01140748435</code></p>
                                <a href="https://ipn.eg/S/eyadsaleh1/instapay/4UHCjY" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-2 rounded-xl bg-bunny-primary px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90">
                                    🔗 اضغطي هنا للتحويل مباشرة
                                </a>
                            </div>
                            <p class="text-xs text-amber-700 dark:text-amber-400">⚠️ بعد التحويل، ابعتي اسكرين شوت على الواتساب لتأكيد الدفع</p>
                        </div>

                        {{-- Vodafone Cash Instructions --}}
                        <div x-show="paymentMethod === 'vodafone_cash'" class="space-y-3">
                            <p class="text-sm font-bold text-amber-800 dark:text-amber-300">📲 تعليمات الدفع بـ فودافون كاش:</p>
                            <div class="rounded-xl bg-white/80 p-4 dark:bg-black/20 space-y-2 text-sm">
                                <p><span class="font-semibold">حوّلي على الرقم:</span> <code class="bg-bunny-accent px-2 py-0.5 rounded font-mono text-bunny-primary text-lg">01140748435</code></p>
                            </div>
                            <p class="text-xs text-amber-700 dark:text-amber-400">⚠️ بعد التحويل، ابعتي اسكرين شوت على الواتساب لتأكيد الدفع</p>
                        </div>
                    </div>

                    <textarea name="notes" rows="3" class="field" placeholder="ملاحظات على الأوردر (اختياري)">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- ═══ Summary ═══ --}}
            <aside class="card-surface h-fit p-6 lg:p-8">
                <h2 class="font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text flex items-center gap-2">
                    <span class="text-2xl">🛍️</span> ملخص الأوردر
                </h2>
                <div class="mt-5 space-y-4">
                    @foreach($items as $item)
                        <div class="flex items-center justify-between gap-3 text-sm">
                            <div class="min-w-0">
                                <p class="truncate font-medium text-bunny-text dark:text-bunny-dark-text">{{ $item->product->display_name }}</p>
                                <p class="text-xs text-bunny-muted dark:text-bunny-dark-muted">x{{ $item->quantity }}</p>
                            </div>
                            <span class="flex-shrink-0 text-bunny-text dark:text-bunny-dark-text">{{ number_format((float) ($item->product->current_price * $item->quantity), 2) }} {{ __('messages.currency') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 space-y-3 border-t border-bunny-border pt-5 text-sm dark:border-bunny-dark-border">
                    <div class="flex justify-between text-bunny-muted dark:text-bunny-dark-muted"><span>{{ __('messages.cart.subtotal') }}</span><span>{{ number_format((float) $subtotal, 2) }} {{ __('messages.currency') }}</span></div>
                    <div class="flex justify-between text-bunny-muted dark:text-bunny-dark-muted"><span>{{ __('messages.cart.shipping') }}</span><span>{{ number_format((float) $shipping, 2) }} {{ __('messages.currency') }}</span></div>
                    <div class="flex justify-between text-bunny-muted dark:text-bunny-dark-muted"><span>{{ __('messages.cart.discount') }}</span><span class="text-emerald-600 dark:text-emerald-400">-{{ number_format((float) $discount, 2) }} {{ __('messages.currency') }}</span></div>
                    <div class="divider"></div>
                    <div class="flex justify-between pt-1 font-serif text-xl font-bold text-bunny-text dark:text-bunny-dark-text"><span>{{ __('messages.cart.total') }}</span><span>{{ number_format((float) $total, 2) }} {{ __('messages.currency') }}</span></div>
                </div>
                <button type="submit" class="pill-btn-primary mt-6 w-full text-center">تأكيد الأوردر 🐰</button>
            </aside>
        </form>
    </section>

    <script>
        function checkoutForm() {
            return {
                governorate: '{{ old('governorate', '') }}',
                city: '{{ old('city', '') }}',
                areas: [],
                paymentMethod: '{{ old('payment_method', 'cash_on_delivery') }}',

                init() {
                    if (this.governorate) {
                        this.fetchAreas();
                    }
                },

                async fetchAreas() {
                    if (!this.governorate) {
                        this.areas = [];
                        this.city = '';
                        return;
                    }
                    try {
                        const res = await fetch(`/egypt/areas?governorate=${encodeURIComponent(this.governorate)}`);
                        this.areas = await res.json();
                        // Keep old selection if it exists in the new list
                        if (!this.areas.includes(this.city)) {
                            this.city = '';
                        }
                    } catch (e) {
                        this.areas = [];
                    }
                }
            }
        }
    </script>
@endsection
