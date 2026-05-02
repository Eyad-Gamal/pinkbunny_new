<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة أوردر #{{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8f4f6; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 30px rgba(0,0,0,0.06);">
        {{-- Header --}}
        <tr>
            <td style="background: linear-gradient(135deg, #f472b6, #ec4899); padding: 28px 36px; text-align: center;">
                <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">Pink Bunny 🐰</h1>
                <p style="margin: 8px 0 0; font-size: 11px; color: rgba(255,255,255,0.85); text-transform: uppercase; letter-spacing: 2px;">فاتورة أوردر</p>
            </td>
        </tr>

        {{-- Body --}}
        <tr>
            <td style="padding: 32px 36px;">
                {{-- Order Info --}}
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                    <tr>
                        <td style="padding: 12px 16px; background: #fdf2f8; border-radius: 12px; border-right: 4px solid #f472b6;">
                            <p style="margin: 0 0 4px; font-size: 10px; color: #ec4899; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700;">رقم الأوردر</p>
                            <p style="margin: 0; font-size: 18px; font-weight: 800; color: #1a1a2e; font-family: 'Consolas', monospace;">{{ $order->order_number }}</p>
                            <p style="margin: 4px 0 0; font-size: 12px; color: #888;">{{ $order->created_at->format('Y/m/d — h:i A') }}</p>
                        </td>
                    </tr>
                </table>

                {{-- Customer & Address --}}
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                    <tr>
                        <td width="50%" style="padding: 12px 16px; background: #f9fafb; border-radius: 10px; vertical-align: top;">
                            <p style="margin: 0 0 4px; font-size: 10px; color: #ec4899; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">العميل</p>
                            <p style="margin: 0; font-size: 14px; font-weight: 700; color: #1a1a2e;">{{ $order->user->name }}</p>
                            <p style="margin: 2px 0 0; font-size: 12px; color: #666;">{{ $order->user->email }}</p>
                            <p style="margin: 2px 0 0; font-size: 12px; color: #666;">{{ $order->user->phone ?? '—' }}</p>
                        </td>
                        <td width="8"></td>
                        @if($order->address)
                        <td width="50%" style="padding: 12px 16px; background: #f9fafb; border-radius: 10px; vertical-align: top;">
                            <p style="margin: 0 0 4px; font-size: 10px; color: #ec4899; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">عنوان الشحن</p>
                            <p style="margin: 0; font-size: 14px; font-weight: 700; color: #1a1a2e;">{{ $order->address->full_name }}</p>
                            <p style="margin: 2px 0 0; font-size: 12px; color: #666;">{{ $order->address->phone }}</p>
                            <p style="margin: 2px 0 0; font-size: 12px; color: #666;">{{ $order->address->street }}</p>
                            <p style="margin: 2px 0 0; font-size: 12px; color: #666;">{{ $order->address->city }}، {{ $order->address->governorate }}</p>
                        </td>
                        @endif
                    </tr>
                </table>

                {{-- Items --}}
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="background: #1a1a2e; color: #fff; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 10px 12px; text-align: right; border-radius: 8px 0 0 0;">المنتج</th>
                            <th style="background: #1a1a2e; color: #fff; font-size: 10px; font-weight: 700; padding: 10px 8px; text-align: center;">الكمية</th>
                            <th style="background: #1a1a2e; color: #fff; font-size: 10px; font-weight: 700; padding: 10px 8px; text-align: center;">السعر</th>
                            <th style="background: #1a1a2e; color: #fff; font-size: 10px; font-weight: 700; padding: 10px 12px; text-align: left; border-radius: 0 8px 0 0;">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td style="padding: 10px 12px; font-size: 13px; font-weight: 600; color: #1a1a2e; border-bottom: 1px solid #f0f0f0;">
                                    {{ $item->product_name_ar ?: $item->product_name_en }}
                                </td>
                                <td style="padding: 10px 8px; font-size: 13px; color: #666; text-align: center; border-bottom: 1px solid #f0f0f0;">{{ $item->quantity }}</td>
                                <td style="padding: 10px 8px; font-size: 13px; color: #666; text-align: center; border-bottom: 1px solid #f0f0f0;">{{ number_format($item->unit_price, 2) }}</td>
                                <td style="padding: 10px 12px; font-size: 13px; font-weight: 600; color: #1a1a2e; text-align: left; border-bottom: 1px solid #f0f0f0;">{{ number_format($item->subtotal ?: $item->unit_price * $item->quantity, 2) }} جنيه</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Totals --}}
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                    <tr>
                        <td width="50%"></td>
                        <td width="50%">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 5px 0; font-size: 13px; color: #666;">المنتجات</td>
                                    <td style="padding: 5px 0; font-size: 13px; color: #666; text-align: left;">{{ number_format($order->subtotal, 2) }} جنيه</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0; font-size: 13px; color: #666;">الشحن</td>
                                    <td style="padding: 5px 0; font-size: 13px; color: #666; text-align: left;">{{ number_format($order->shipping_fee, 2) }} جنيه</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td style="padding: 5px 0; font-size: 13px; color: #16a34a;">الخصم{{ $order->coupon_code ? " ({$order->coupon_code})" : '' }}</td>
                                    <td style="padding: 5px 0; font-size: 13px; color: #16a34a; text-align: left;">−{{ number_format($order->discount_amount, 2) }} جنيه</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="2" style="padding: 8px 0;"><div style="border-top: 2px dashed #eee;"></div></td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; font-size: 18px; font-weight: 800; color: #1a1a2e;">الإجمالي</td>
                                    <td style="padding: 8px 0; font-size: 18px; font-weight: 800; color: #f472b6; text-align: left;">{{ number_format($order->total_amount, 2) }} جنيه</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                {{-- Payment Method --}}
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                    <tr>
                        <td style="padding: 10px 16px; background: #f8f8fa; border-radius: 10px;">
                            <span style="font-size: 12px; color: #999;">طريقة الدفع:</span>
                            <span style="font-size: 13px; font-weight: 700; color: #1a1a2e; margin-right: 8px;">
                                @php
                                    $paymentLabels = [
                                        'cash_on_delivery' => '💵 كاش عند الاستلام',
                                        'instapay' => '📱 إنستاباي',
                                        'vodafone_cash' => '📲 فودافون كاش',
                                    ];
                                @endphp
                                {{ $paymentLabels[$order->payment_method] ?? ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            </span>
                        </td>
                    </tr>
                </table>

                {{-- Footer --}}
                <div style="border-top: 1px solid #f0f0f0; padding-top: 20px; text-align: center;">
                    <p style="margin: 0 0 4px; font-size: 14px; font-weight: 700; color: #f472b6;">شكراً لتسوقك مع Pink Bunny! 🐰💕</p>
                    <p style="margin: 0; font-size: 11px; color: #999;">pinkbunnyeg.com</p>
                    <p style="margin: 8px 0 0; font-size: 10px; color: #ccc; font-style: italic;">هذه فاتورة إلكترونية تم إنشاؤها تلقائياً</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
