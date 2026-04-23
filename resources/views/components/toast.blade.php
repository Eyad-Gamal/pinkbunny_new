@props(['message', 'type' => 'success'])

<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-3"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-3"
    class="mx-auto mb-6 flex max-w-xl items-center gap-3 rounded-2xl border px-5 py-3.5 text-sm font-medium shadow-elevated backdrop-blur-xl {{ $type === 'error' ? 'border-red-200 bg-red-50/90 text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400' : 'border-bunny-border bg-white/90 text-bunny-text dark:border-bunny-dark-border dark:bg-bunny-dark-surface/90 dark:text-bunny-dark-text' }}">
    @if($type === 'error')
        <svg class="h-5 w-5 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
    @else
        <svg class="h-5 w-5 flex-shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
    @endif
    <span class="flex-1">{{ $message }}</span>
    <button @click="show = false" class="inline-flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full text-current opacity-50 transition-opacity hover:opacity-100">
        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
    </button>
</div>
