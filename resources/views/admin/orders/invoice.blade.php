<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        @page {
            size: A5 portrait;
            margin: 12mm;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            line-height: 1.5;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .invoice {
            max-width: 148mm;
            margin: 0 auto;
            padding: 8mm;
        }

        /* ─── Header ─── */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 10px;
            border-bottom: 2px solid #f472b6;
            margin-bottom: 12px;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a2e;
            letter-spacing: -0.5px;
        }
        .brand-name span { color: #f472b6; }
        .brand-tagline {
            font-size: 8px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 2px;
        }

        .invoice-badge {
            text-align: right;
        }
        .invoice-badge h2 {
            font-size: 16px;
            font-weight: 800;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .invoice-badge .order-num {
            font-size: 11px;
            color: #f472b6;
            font-weight: 600;
            font-family: 'Consolas', 'Monaco', monospace;
            margin-top: 2px;
        }
        .invoice-badge .order-date {
            font-size: 9px;
            color: #999;
            margin-top: 2px;
        }

        /* ─── Info Grid ─── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 14px;
        }
        .info-box {
            padding: 8px 10px;
            background: #fdf2f8;
            border-radius: 6px;
            border-left: 3px solid #f472b6;
        }
        .info-box-label {
            font-size: 7px;
            font-weight: 700;
            color: #f472b6;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 3px;
        }
        .info-box-name {
            font-size: 12px;
            font-weight: 700;
            color: #1a1a2e;
        }
        .info-box-detail {
            font-size: 9px;
            color: #666;
            margin-top: 1px;
        }

        /* ─── Items Table ─── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .items-table th {
            background: #1a1a2e;
            color: #fff;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 6px 8px;
            text-align: left;
        }
        .items-table th:last-child { text-align: right; }
        .items-table td {
            padding: 7px 8px;
            font-size: 10px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        .items-table td:last-child { text-align: right; font-weight: 600; }
        .items-table tbody tr:last-child td { border-bottom: none; }
        .items-table .product-name {
            font-weight: 600;
            color: #1a1a2e;
        }
        .items-table .product-name-ar {
            font-size: 9px;
            color: #999;
            display: block;
        }

        /* ─── Totals ─── */
        .totals {
            margin-left: auto;
            width: 55%;
            margin-bottom: 14px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 10px;
            color: #666;
        }
        .totals-row.discount { color: #16a34a; }
        .totals-divider {
            border-top: 1px dashed #ddd;
            margin: 6px 0;
        }
        .totals-total {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            font-weight: 800;
            color: #1a1a2e;
        }
        .totals-total span:last-child { color: #f472b6; }

        /* ─── Payment ─── */
        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 10px;
            background: #f8f8fa;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 10px;
        }
        .payment-method {
            font-weight: 700;
            color: #1a1a2e;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1e40af; }
        .status-processing { background: #ede9fe; color: #5b21b6; }
        .status-shipped { background: #e0e7ff; color: #3730a3; }
        .status-delivered { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        /* ─── Footer ─── */
        .invoice-footer {
            border-top: 1px solid #eee;
            padding-top: 8px;
            text-align: center;
        }
        .footer-thanks {
            font-size: 12px;
            font-weight: 700;
            color: #f472b6;
            margin-bottom: 3px;
        }
        .footer-contact {
            font-size: 8px;
            color: #999;
        }
        .footer-note {
            margin-top: 6px;
            font-size: 7px;
            color: #bbb;
            font-style: italic;
        }

        /* ─── Print ─── */
        .no-print { margin: 20px auto; text-align: center; }
        .no-print button {
            background: #f472b6;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin: 0 6px;
        }
        .no-print button.outline {
            background: transparent;
            color: #666;
            border: 1px solid #ddd;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .invoice { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Print Invoice</button>
        <button class="outline" onclick="window.close()">✕ Close</button>
    </div>

    <div class="invoice">
        {{-- Header --}}
        <div class="invoice-header">
            <div>
                <div class="brand-name">Pink <span>Bunny</span></div>
                <div class="brand-tagline">Beauty & Cosmetics</div>
            </div>
            <div class="invoice-badge">
                <h2>Invoice</h2>
                <div class="order-num">{{ $order->order_number }}</div>
                <div class="order-date">{{ $order->created_at->format('F j, Y') }}</div>
            </div>
        </div>

        {{-- Customer & Shipping --}}
        <div class="info-grid">
            <div class="info-box">
                <div class="info-box-label">Customer</div>
                <div class="info-box-name">{{ $order->user->name }}</div>
                <div class="info-box-detail">{{ $order->user->email }}</div>
                <div class="info-box-detail">{{ $order->user->phone ?? '—' }}</div>
            </div>
            @if($order->address)
            <div class="info-box">
                <div class="info-box-label">Ship To</div>
                <div class="info-box-name">{{ $order->address->full_name }}</div>
                <div class="info-box-detail">{{ $order->address->phone }}</div>
                <div class="info-box-detail">{{ $order->address->street }}</div>
                <div class="info-box-detail">{{ $order->address->city }}, {{ $order->address->governorate }}</div>
            </div>
            @endif
        </div>

        {{-- Items --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th>Product</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 18%; text-align: right;">Price</th>
                    <th style="width: 20%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $i => $item)
                    <tr>
                        <td style="color: #999;">{{ $i + 1 }}</td>
                        <td>
                            <span class="product-name">{{ $item->product_name_en }}</span>
                            @if($item->product_name_ar)
                                <span class="product-name-ar">{{ $item->product_name_ar }}</span>
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->subtotal ?: $item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals">
            <div class="totals-row">
                <span>Subtotal</span>
                <span>{{ number_format($order->subtotal, 2) }} EGP</span>
            </div>
            <div class="totals-row">
                <span>Shipping</span>
                <span>{{ number_format($order->shipping_fee, 2) }} EGP</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="totals-row discount">
                    <span>Discount{{ $order->coupon_code ? " ({$order->coupon_code})" : '' }}</span>
                    <span>−{{ number_format($order->discount_amount, 2) }} EGP</span>
                </div>
            @endif
            <div class="totals-divider"></div>
            <div class="totals-total">
                <span>Total</span>
                <span>{{ number_format($order->total_amount, 2) }} EGP</span>
            </div>
        </div>

        {{-- Payment --}}
        <div class="payment-row">
            <div>
                <span style="color: #999;">Payment:</span>
                <span class="payment-method">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
            </div>
            @php $sc = match($order->status) { 'pending' => 'status-pending', 'confirmed' => 'status-confirmed', 'processing' => 'status-processing', 'shipped' => 'status-shipped', 'delivered' => 'status-delivered', default => 'status-cancelled' }; @endphp
            <span class="status-badge {{ $sc }}">{{ $order->status }}</span>
        </div>

        {{-- Footer --}}
        <div class="invoice-footer">
            <div class="footer-thanks">Thank you for shopping with Pink Bunny! 🐰</div>
            <div class="footer-contact">pinkbunnybeauty.com · hello@pinkbunnybeauty.com · +20 100 555 0199</div>
            <div class="footer-note">This is a computer-generated invoice and does not require a signature.</div>
        </div>
    </div>
</body>
</html>
