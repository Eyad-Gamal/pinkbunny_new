@extends('layouts.app')

@section('title', 'العناوين - Pink Bunny')

@section('content')
    <section class="page-shell">
        <h1 class="section-title">العناوين</h1>
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
                                    <span class="mt-3 inline-flex rounded-full bg-bunny-accent px-3 py-1 text-xs font-bold">الافتراضي</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('profile.addresses.default', $address) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="pill-btn border border-bunny-border px-4 py-2">تعيين كافتراضي</button>
                                </form>
                                <form action="{{ route('profile.addresses.delete', $address) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pill-btn border border-red-200 px-4 py-2 text-red-600">حذف</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card-surface p-8 text-center text-bunny-muted">لا يوجد عناوين بعد.</div>
                @endforelse
            </div>

            <div class="card-surface p-6" x-data="addressForm()">
                <h2 class="text-2xl font-extrabold">إضافة عنوان جديد</h2>
                <form action="{{ route('profile.addresses.store') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <input type="text" name="label" class="field mt-0" placeholder="اسم العنوان (البيت / الشغل)">
                    <input type="text" name="full_name" class="field mt-0" placeholder="الاسم بالكامل">
                    <input type="text" name="phone" class="field mt-0" placeholder="رقم الموبايل">
                    <textarea name="street" rows="3" class="field mt-0" placeholder="العنوان بالتفصيل (الشارع، العمارة، الدور)"></textarea>

                    {{-- Governorate Dropdown --}}
                    <select name="governorate" class="field mt-0" x-model="governorate" @change="fetchAreas()">
                        <option value="">— اختاري المحافظة —</option>
                        @foreach(array_keys(config('egypt', [])) as $gov)
                            <option value="{{ $gov }}">{{ $gov }}</option>
                        @endforeach
                    </select>

                    {{-- Area Dropdown --}}
                    <select name="city" class="field mt-0" x-model="city" :disabled="areas.length === 0">
                        <option value="">— اختاري المنطقة —</option>
                        <template x-for="area in areas" :key="area">
                            <option :value="area" x-text="area"></option>
                        </template>
                    </select>

                    <input type="hidden" name="country" value="Egypt">

                    <label class="flex items-center gap-3 text-sm"><input type="checkbox" name="is_default" value="1"> تعيين كعنوان افتراضي</label>
                    <button type="submit" class="pill-btn w-full bg-bunny-primary px-6 py-3 text-white">حفظ العنوان</button>
                </form>
            </div>
        </div>
    </section>

    <script>
        function addressForm() {
            return {
                governorate: '',
                city: '',
                areas: [],
                async fetchAreas() {
                    if (!this.governorate) { this.areas = []; this.city = ''; return; }
                    try {
                        const res = await fetch(`/egypt/areas?governorate=${encodeURIComponent(this.governorate)}`);
                        this.areas = await res.json();
                        this.city = '';
                    } catch (e) { this.areas = []; }
                }
            }
        }
    </script>
@endsection
