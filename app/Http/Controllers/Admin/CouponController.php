<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Coupon, CouponUsage};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::withCount('usages')->latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                => 'nullable|string|max:50|unique:coupons,code',
            'description'         => 'nullable|string|max:255',
            'type'                => 'required|in:percentage,fixed',
            'value'               => 'required|numeric|min:0.01',
            'min_order_amount'    => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'is_active'           => 'boolean',
            'starts_at'           => 'nullable|date',
            'expires_at'          => 'nullable|date|after:starts_at',
            'max_uses'            => 'nullable|integer|min:1',
            'max_uses_per_user'   => 'required|integer|min:1',
        ]);

        $validated['code'] = strtoupper($validated['code'] ?? Str::random(8));
        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('toast', 'Coupon created.');
    }

    public function update(Request $request, string $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'description'         => 'nullable|string|max:255',
            'type'                => 'required|in:percentage,fixed',
            'value'               => 'required|numeric|min:0.01',
            'min_order_amount'    => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'is_active'           => 'boolean',
            'starts_at'           => 'nullable|date',
            'expires_at'          => 'nullable|date',
            'max_uses'            => 'nullable|integer|min:1',
            'max_uses_per_user'   => 'required|integer|min:1',
        ]);

        $coupon->update($validated);
        return redirect()->route('admin.coupons.index')->with('toast', 'Coupon updated.');
    }

    public function toggle(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => !$coupon->is_active]);
        return response()->json(['is_active' => $coupon->is_active]);
    }

    public function destroy(string $id)
    {
        Coupon::findOrFail($id)->delete();
        return back()->with('toast', 'Coupon deleted.');
    }

    public function usages(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $usages = CouponUsage::where('coupon_id', $id)->with(['user', 'order'])->latest()->paginate(20);
        return view('admin.coupons.usages', compact('coupon', 'usages'));
    }
}
