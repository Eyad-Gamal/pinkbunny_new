<x-guest-layout>
    <div class="text-center">
        <div class="mx-auto mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-bunny-primary/10">
            <svg class="h-8 w-8 text-bunny-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
        </div>
        <h1 class="font-serif text-2xl font-bold text-bunny-text dark:text-bunny-dark-text">Verify Your Email</h1>
        <p class="mt-2 text-sm text-bunny-muted dark:text-bunny-dark-muted">
            We sent a 6-digit code to
        </p>
        <p class="mt-1 text-sm font-semibold text-bunny-primary">{{ $email }}</p>
    </div>

    @if (session('status') == 'verification-code-sent')
        <div class="mt-5 rounded-2xl bg-emerald-50 p-4 text-center text-sm font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
            ✅ A new verification code has been sent!
        </div>
    @endif

    @if ($errors->has('code'))
        <div class="mt-5 rounded-2xl bg-red-50 p-4 text-center text-sm font-medium text-red-600 dark:bg-red-500/10 dark:text-red-400">
            {{ $errors->first('code') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.verify') }}" class="mt-8" data-no-swup id="verify-form">
        @csrf

        {{-- 6-digit code input --}}
        <div class="flex justify-center gap-3" id="code-inputs">
            @for ($i = 0; $i < 6; $i++)
                <input
                    type="text"
                    maxlength="1"
                    inputmode="numeric"
                    pattern="[0-9]"
                    class="h-14 w-12 rounded-2xl border-2 border-bunny-border bg-white text-center text-xl font-bold text-bunny-text shadow-sm outline-none transition-all duration-200 focus:border-bunny-primary focus:ring-4 focus:ring-bunny-primary/20 dark:border-bunny-dark-border dark:bg-bunny-dark-card dark:text-bunny-dark-text dark:focus:border-bunny-primary dark:focus:ring-bunny-primary/20"
                    data-code-input="{{ $i }}"
                    autocomplete="one-time-code"
                    required
                >
            @endfor
        </div>

        {{-- Hidden field combining all digits --}}
        <input type="hidden" name="code" id="code-hidden">

        <button type="submit" class="pill-btn-primary mt-8 w-full">
            Verify Email
        </button>
    </form>

    <div class="mt-6 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}" data-no-swup>
            @csrf
            <button type="submit" class="text-sm font-semibold text-bunny-primary transition-colors hover:text-bunny-primary-dark">
                Resend Code
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}" data-no-swup>
            @csrf
            <button type="submit" class="text-sm font-semibold text-bunny-muted transition-colors hover:text-bunny-primary dark:text-bunny-dark-muted dark:hover:text-bunny-primary">
                Log Out
            </button>
        </form>
    </div>

    {{-- JavaScript for code input UX --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('[data-code-input]');
            const hidden = document.getElementById('code-hidden');
            const form = document.getElementById('verify-form');

            function updateHidden() {
                let code = '';
                inputs.forEach(input => code += input.value);
                hidden.value = code;
            }

            inputs.forEach((input, index) => {
                // Focus first input
                if (index === 0) input.focus();

                input.addEventListener('input', function(e) {
                    const val = this.value.replace(/[^0-9]/g, '');
                    this.value = val.charAt(0) || '';
                    updateHidden();

                    if (val && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    // Auto-submit when all 6 digits are filled
                    if (hidden.value.length === 6) {
                        form.submit();
                    }
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                        updateHidden();
                    }
                    if (e.key === 'ArrowLeft' && index > 0) {
                        e.preventDefault();
                        inputs[index - 1].focus();
                    }
                    if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                        e.preventDefault();
                        inputs[index + 1].focus();
                    }
                });

                // Handle paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                    if (pasted.length >= 6) {
                        for (let i = 0; i < 6; i++) {
                            inputs[i].value = pasted[i] || '';
                        }
                        updateHidden();
                        inputs[5].focus();
                        if (hidden.value.length === 6) {
                            form.submit();
                        }
                    }
                });

                input.addEventListener('focus', function() {
                    this.select();
                });
            });
        });
    </script>
</x-guest-layout>
