<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerifyEmailCodeController extends Controller
{
    /**
     * Show the verification code entry form.
     */
    public function show(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return view('auth.verify-email', [
            'email' => $request->user()->email,
        ]);
    }

    /**
     * Verify the submitted code.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $code = $request->input('code');

        if ($request->user()->verifyEmailCode($code)) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        return back()->withErrors([
            'code' => __('The verification code is invalid or has expired.'),
        ]);
    }

    /**
     * Resend a new verification code.
     */
    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if (!$request->user()->email) {
            return back()->withErrors(['email' => 'No email address on file.']);
        }

        $request->user()->sendEmailVerificationCode();

        return back()->with('status', 'verification-code-sent');
    }
}
