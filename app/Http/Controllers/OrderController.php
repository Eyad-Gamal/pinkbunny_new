<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\{CartService, OrderService};
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly CartService  $cartService,
    ) {}

    public function checkout(Request $request)
    {
        $user = $request->user();

        if ($user->cartItems()->count() === 0) {
            return redirect()->route('cart.index')->with('error', __('messages.cart.empty'));
        }

        $summary   = $this->cartService->getSummary($user, session('cart_discount', 0));
        $addresses = $user->addresses()->get();

        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        return view('orders.checkout', array_merge($summary, compact('addresses', 'defaultAddress')));
    }

    public function placeOrder(StoreOrderRequest $request)
    {
        $data = $request->validated();

        // If no existing address was selected, create one from the form fields
        if (empty($data['address_id'])) {
            $address = $request->user()->addresses()->create([
                'label'       => $data['label'],
                'full_name'   => $data['full_name'],
                'phone'       => $data['phone'],
                'street'      => $data['street'],
                'city'        => $data['city'],
                'governorate' => $data['governorate'],
                'country'     => $data['country'] ?? 'Egypt',
                'is_default'  => $request->user()->addresses()->count() === 1,
            ]);
            $data['address_id'] = $address->id;
        }

        $order = $this->orderService->placeOrder($request->user(), $data);

        session()->forget(['cart_discount', 'coupon_code']);

        return redirect()->route('orders.show', $order->id)
            ->with('toast', __('messages.orders.placed', ['number' => $order->order_number]));
    }

    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with(['items'])->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, string $id)
    {
        $order = $request->user()->orders()
            ->with(['items.product', 'address', 'tracking'])
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }
}
