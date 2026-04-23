<?php

namespace App\Http\Controllers;

use App\Services\{CartService, CouponService};
use Illuminate\Http\{Request, JsonResponse, RedirectResponse};
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService   $cartService,
        private readonly CouponService $couponService,
    ) {}

    public function index(Request $request): View
    {
        $discount = session('cart_discount', 0);
        $summary  = $this->cartService->getSummary($request->user(), $discount);
        return view('cart.index', $summary);
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'    => 'nullable|integer|min:1|max:99',
        ]);

        $result = $this->cartService->add($request->user(), $data['product_id'], (int) ($data['quantity'] ?? 1));

        return back()->with($result['success'] ? 'toast' : 'error', $result['message']);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $data = $request->validate(['quantity' => 'required|integer|min:0|max:99']);

        $result = $this->cartService->update($request->user(), $id, (int) $data['quantity']);
        return back()->with($result['success'] ? 'toast' : 'error', $result['message']);
    }

    public function remove(Request $request, string $id): RedirectResponse
    {
        $this->cartService->remove($request->user(), $id);
        return back()->with('toast', __('messages.cart.removed'));
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $data = $request->validate(['code' => 'required|string|max:50']);
        $cart = $this->cartService->getSummary($request->user());
        $result = $this->couponService->validate($data['code'], $cart['subtotal'], $request->user()->id);

        if ($result['valid']) {
            session(['cart_discount' => $result['discount'], 'coupon_code' => $data['code']]);
        }

        return back()->with($result['valid'] ? 'toast' : 'error', $result['message']);
    }
}
