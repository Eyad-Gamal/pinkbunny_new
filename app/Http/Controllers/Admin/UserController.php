<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withTrashed()
            ->withCount(['orders'])
            ->when($request->search, fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
            )
            ->when($request->status === 'active',   fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = User::withTrashed()
            ->with(['orders.items', 'addresses', 'reviews'])
            ->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function toggleActive(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->hasRole('admin')) {
            return response()->json(['error' => 'Cannot deactivate an admin account.'], 403);
        }
        $user->update(['is_active' => !$user->is_active]);
        return response()->json(['is_active' => $user->is_active]);
    }
}
