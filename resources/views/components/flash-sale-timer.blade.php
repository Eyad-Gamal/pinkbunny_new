@props(['endsAt'])

<div x-data="flashTimer('{{ optional($endsAt)->toIso8601String() }}')" x-init="start()" class="flex items-center gap-2 text-center">
    <template x-for="(part, index) in parts" :key="part.label">
        <div class="flex items-center gap-2">
            <div class="min-w-[60px] rounded-2xl border border-white/20 bg-white/15 px-3 py-2.5 backdrop-blur-sm">
                <div class="font-serif text-2xl font-bold text-white" x-text="part.value"></div>
                <div class="mt-0.5 text-[10px] font-semibold uppercase tracking-wider text-white/70" x-text="part.label"></div>
            </div>
            <span x-show="index < parts.length - 1" class="text-lg font-light text-white/50">:</span>
        </div>
    </template>
</div>
