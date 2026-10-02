<?php

namespace Database\Seeders;

use App\Models\{Order, OrderItem, OrderTracking, Product, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Creates realistic orders spread across the last 30 days.
     * Covers all statuses, multiple items, and varied totals.
     */
    public function run(): void
    {
        $customers = User::role('customer')->with('addresses')->get();
        $products  = Product::where('is_active', true)->get();

        if ($customers->isEmpty()) {
            $this->command->warn('⚠️  No customers found — run CustomerSeeder first.');
            return;
        }
        if ($products->isEmpty()) {
            $this->command->warn('⚠️  No products found — run ProductSeeder first.');
            return;
        }

        // Pre-defined order scenarios for realistic data
        $scenarios = [
            // [customer_index, product_slugs_with_qty, status, payment_status, days_ago]
            ['nour.elsayed@gmail.com',    ['bunny-blush-palette' => 2, 'rose-lip-tint' => 1],  'delivered',   'paid',    28],
            ['mariam.hassan@outlook.com', ['glow-serum-pro' => 1],                              'delivered',   'paid',    25],
            ['sara.mahmoud@yahoo.com',    ['hydrating-cloud-cream' => 1, 'glow-serum-pro' => 1],'delivered',   'paid',    22],
            ['layla.karim@gmail.com',     ['bond-repair-shampoo' => 1],                         'shipped',     'paid',    15],
            ['dina.farouk@gmail.com',     ['bunny-blush-palette' => 1, 'velvet-body-lotion' => 2], 'shipped',  'paid',    12],
            ['hana.youssef@hotmail.com',  ['glow-serum-pro' => 2],                              'processing',  'paid',     8],
            ['rania.ibrahim@gmail.com',   ['hydrating-cloud-cream' => 1],                       'confirmed',   'pending',  5],
            ['yasmine.adel@gmail.com',    ['bond-repair-shampoo' => 1, 'velvet-body-lotion' => 1], 'confirmed','pending',  4],
            ['mona.tarek@gmail.com',      ['bunny-blush-palette' => 1],                         'pending',     'pending',  2],
            ['salma.nasser@gmail.com',    ['glow-serum-pro' => 1, 'rose-lip-tint' => 2],        'pending',     'pending',  1],
            // A cancelled order
            ['nour.elsayed@gmail.com',    ['velvet-body-lotion' => 1],                          'cancelled',   'pending', 20],
            // A refunded order
            ['mariam.hassan@outlook.com', ['rose-lip-tint' => 1],                               'refunded',    'refunded', 18],
            // Extra recent pending orders (good for testing real-time notifications)
            ['sara.mahmoud@yahoo.com',    ['bunny-blush-palette' => 1],                         'pending',     'pending',  0],
            ['layla.karim@gmail.com',     ['glow-serum-pro' => 1, 'hydrating-cloud-cream' => 1],'pending',     'pending',  0],
        ];

        $productMap = $products->keyBy('slug');

        foreach ($scenarios as $i => $scenario) {
            [$email, $items, $status, $paymentStatus, $daysAgo] = $scenario;

            $user = User::where('email', $email)->first();
            if (!$user) continue;

            $address = $user->addresses()->first();
            if (!$address) continue;

            DB::transaction(function () use ($user, $address, $items, $status, $paymentStatus, $daysAgo, $productMap, $i) {
                $createdAt = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

                // Build order number manually (bypasses the model boot for historical dates)
                $date        = $createdAt->format('Ymd');
                $orderNumber = 'PB-' . $date . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

                // Calculate totals
                $subtotal = 0;
                $lineItems = [];

                foreach ($items as $slug => $qty) {
                    $product = $productMap->get($slug);
                    if (!$product) continue;

                    $unitPrice  = (float) ($product->sale_price ?? $product->price);
                    $lineTotal  = $unitPrice * $qty;
                    $subtotal  += $lineTotal;

                    $lineItems[] = [
                        'product'    => $product,
                        'qty'        => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal'   => $lineTotal,
                    ];
                }

                $shipping = $subtotal >= 500 ? 0 : 50;
                $total    = $subtotal + $shipping;

                $order = Order::create([
                    'order_number'   => $orderNumber,
                    'user_id'        => $user->id,
                    'address_id'     => $address->id,
                    'status'         => $status,
                    'subtotal'       => $subtotal,
                    'shipping_fee'   => $shipping,
                    'discount_amount'=> 0,
                    'total_amount'   => $total,
                    'payment_method' => 'cash_on_delivery',
                    'payment_status' => $paymentStatus,
                    'created_at'     => $createdAt,
                    'updated_at'     => $createdAt,
                ]);

                // Order items
                foreach ($lineItems as $line) {
                    OrderItem::create([
                        'order_id'        => $order->id,
                        'product_id'      => $line['product']->id,
                        'product_name_en' => $line['product']->name_en,
                        'product_name_ar' => $line['product']->name_ar,
                        'product_image'   => $line['product']->first_image,
                        'unit_price'      => $line['unit_price'],
                        'quantity'        => $line['qty'],
                        'subtotal'        => $line['subtotal'],
                        'created_at'      => $createdAt,
                        'updated_at'      => $createdAt,
                    ]);
                }

                // Tracking history — build a realistic timeline
                $this->addTrackingHistory($order, $status, $createdAt);
            });
        }

        $this->command->info('✅ ' . count($scenarios) . ' orders seeded.');
    }

    private function addTrackingHistory(Order $order, string $finalStatus, $createdAt): void
    {
        $flow = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];

        // Find how far through the flow this order got
        $statusIndex = array_search($finalStatus, $flow);

        if ($finalStatus === 'cancelled') {
            $this->track($order, 'pending',   $createdAt);
            $this->track($order, 'cancelled', $createdAt->copy()->addHours(2));
            return;
        }

        if ($finalStatus === 'refunded') {
            $this->track($order, 'pending',   $createdAt);
            $this->track($order, 'confirmed', $createdAt->copy()->addHours(1));
            $this->track($order, 'refunded',  $createdAt->copy()->addDays(3));
            return;
        }

        $offsets = [0, 2, 6, 24, 72]; // hours after order creation per step

        for ($i = 0; $i <= $statusIndex; $i++) {
            $this->track(
                $order,
                $flow[$i],
                $createdAt->copy()->addHours($offsets[$i])
            );
        }

        // Set shipped_at / delivered_at timestamps on the order
        if ($statusIndex >= 3) {
            $order->update(['shipped_at' => $createdAt->copy()->addHours(24)]);
        }
        if ($statusIndex >= 4) {
            $order->update(['delivered_at' => $createdAt->copy()->addHours(72)]);
        }
    }

    private function track(Order $order, string $status, $occurredAt): void
    {
        $descriptions = [
            'pending'    => ['en' => 'Order received and pending confirmation',  'ar' => 'تم استلام الطلب وبانتظار التأكيد'],
            'confirmed'  => ['en' => 'Order confirmed by our team',              'ar' => 'تم تأكيد الطلب من قِبل فريقنا'],
            'processing' => ['en' => 'Order is being prepared',                  'ar' => 'جاري تجهيز طلبك'],
            'shipped'    => ['en' => 'Order has been shipped',                   'ar' => 'تم شحن الطلب'],
            'delivered'  => ['en' => 'Order delivered successfully',             'ar' => 'تم توصيل الطلب بنجاح'],
            'cancelled'  => ['en' => 'Order has been cancelled',                 'ar' => 'تم إلغاء الطلب'],
            'refunded'   => ['en' => 'Order has been refunded',                  'ar' => 'تم استرجاع مبلغ الطلب'],
        ];

        OrderTracking::create([
            'order_id'       => $order->id,
            'status'         => $status,
            'description_en' => $descriptions[$status]['en'] ?? $status,
            'description_ar' => $descriptions[$status]['ar'] ?? $status,
            'occurred_at'    => $occurredAt,
            'created_at'     => $occurredAt,
            'updated_at'     => $occurredAt,
        ]);
    }
}
