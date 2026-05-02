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
                'country'     => 'Egypt',
                'is_default'  => $request->user()->addresses()->count() === 1,
            ]);
            $data['address_id'] = $address->id;
        }

        try {
            $order = $this->orderService->placeOrder($request->user(), $data);
        } catch (\Exception $e) {
            return redirect()->route('checkout')
                ->withInput()
                ->with('error', 'حصلت مشكلة أثناء تسجيل الأوردر. حاولي تاني.');
        }

        session()->forget(['cart_discount', 'coupon_code']);

        $toast = "تم تأكيد أوردرك #{$order->order_number} بنجاح! 🐰 الفاتورة اتبعتت على الإيميل 📧";

        return redirect()->route('orders.show', $order->id)
            ->with('toast', $toast)
            ->with('just_placed', true);
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
