<?php

namespace App\Http\Controllers;

use App\Http\Requests\{UpdateProfileRequest, StoreAddressRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load(['orders' => fn($q) => $q->latest()->limit(5)]);
        return view('profile.index', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $request->user()->update($request->only('name', 'email', 'phone'));
        return back()->with('toast', 'Profile updated successfully.');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048']);

        $user = $request->user();
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('toast', 'Avatar updated.');
    }

    public function addresses(Request $request)
    {
        $addresses = $request->user()->addresses()->get();
        return view('profile.addresses', compact('addresses'));
    }

    public function storeAddress(StoreAddressRequest $request)
    {
        $user = $request->user();
        if ($request->boolean('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }
        $user->addresses()->create($request->validated());
        return back()->with('toast', 'Address added.');
    }

    public function updateAddress(StoreAddressRequest $request, string $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);
        if ($request->boolean('is_default')) {
            $request->user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        }
        $address->update($request->validated());
        return back()->with('toast', 'Address updated.');
    }

    public function deleteAddress(string $id)
    {
        auth()->user()->addresses()->findOrFail($id)->delete();
        return back()->with('toast', 'Address deleted.');
    }

    public function setDefaultAddress(string $id)
    {
        $user = auth()->user();
        $user->addresses()->update(['is_default' => false]);
        $user->addresses()->findOrFail($id)->update(['is_default' => true]);
        return back()->with('toast', 'Default address set.');
    }

    public function destroy(Request $request)
    {
        $request->user()->delete();
        auth()->logout();
        return redirect()->route('home');
    }
}
