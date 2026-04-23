<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        return view('settings.index', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'preferred_language' => ['required', 'in:en,ar'],
            'preferred_theme' => ['required', 'in:light,dark'],
        ]);

        $request->user()->update($data);
        $request->session()->put('locale', $data['preferred_language']);

        return back()->with('toast', __('messages.settings.saved'));
    }
}
