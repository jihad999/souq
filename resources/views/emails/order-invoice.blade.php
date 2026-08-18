<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background: #F8FAFC; padding: 30px; margin: 0;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">

        {{-- الهيدر --}}
        <div style="background: #0F172A; padding: 25px; text-align: center;">
            <h1 style="color: #F97316; margin: 0; font-size: 24px;">سوق</h1>
        </div>

        {{-- محتوى الفاتورة --}}
        <div style="padding: 30px;">
            <h2 style="color: #0F172A; margin-top: 0;">شكرًا لطلبك، {{ $order->customer_name }}! 🎉</h2>
            <p style="color: #475569; line-height: 1.8;">
                تم استلام طلبك بنجاح، وهاي تفاصيل الفاتورة الكاملة.
            </p>

            {{-- معلومات الطلب --}}
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0; background: #F8FAFC; border-radius: 8px; overflow: hidden;">
                <tr>
                    <td style="padding: 12px 15px; color: #64748B; font-size: 14px;">رقم الطلب</td>
                    <td style="padding: 12px 15px; color: #0F172A; font-weight: bold; text-align: left;">{{ $order->order_number }}</td>
                </tr>
                <tr style="background: white;">
                    <td style="padding: 12px 15px; color: #64748B; font-size: 14px;">تاريخ الطلب</td>
                    <td style="padding: 12px 15px; color: #0F172A; text-align: left;">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 15px; color: #64748B; font-size: 14px;">طريقة الدفع</td>
                    <td style="padding: 12px 15px; color: #0F172A; text-align: left;">
                        @switch($order->payment_method)
                            @case('cod') الدفع عند الاستلام @break
                            @case('stripe') بطاقة ائتمان (Stripe) @break
                            @case('paytabs') PayTabs @break
                        @endswitch
                    </td>
                </tr>
                <tr style="background: white;">
                    <td style="padding: 12px 15px; color: #64748B; font-size: 14px;">عنوان الشحن</td>
                    <td style="padding: 12px 15px; color: #0F172A; text-align: left;">{{ $order->shipping_address }}</td>
                </tr>
            </table>

            {{-- المنتجات --}}
            <h3 style="color: #0F172A; font-size: 16px; margin-bottom: 10px;">المنتجات</h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background: #0F172A;">
                        <th style="padding: 10px; color: white; font-size: 13px; text-align: right;">المنتج</th>
                        <th style="padding: 10px; color: white; font-size: 13px; text-align: center;">الكمية</th>
                        <th style="padding: 10px; color: white; font-size: 13px; text-align: left;">السعر</th>
                        <th style="padding: 10px; color: white; font-size: 13px; text-align: left;">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr style="border-bottom: 1px solid #E2E8F0;">
                        <td style="padding: 10px; color: #0F172A; font-size: 14px;">
                            {{ $item->product_name }}
                            @if($item->variant_label)
                                <br><span style="color: #94A3B8; font-size: 12px;">{{ $item->variant_label }}</span>
                            @endif
                        </td>
                        <td style="padding: 10px; color: #475569; font-size: 14px; text-align: center;">{{ $item->quantity }}</td>
                        <td style="padding: 10px; color: #475569; font-size: 14px; text-align: left;">{{ number_format($item->unit_price, 2) }} ₪</td>
                        <td style="padding: 10px; color: #0F172A; font-weight: bold; font-size: 14px; text-align: left;">{{ number_format($item->total_price, 2) }} ₪</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- الإجماليات --}}
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #64748B; font-size: 14px;">المجموع الفرعي</td>
                    <td style="padding: 8px 0; color: #0F172A; text-align: left; font-size: 14px;">{{ number_format($order->subtotal, 2) }} ₪</td>
                </tr>
                @if($order->discount_amount > 0)
                <tr>
                    <td style="padding: 8px 0; color: #16A34A; font-size: 14px;">
                        الخصم @if($order->promoCode) ({{ $order->promoCode->code }}) @endif
                    </td>
                    <td style="padding: 8px 0; color: #16A34A; text-align: left; font-size: 14px;">- {{ number_format($order->discount_amount, 2) }} ₪</td>
                </tr>
                @endif
                <tr style="border-top: 2px solid #0F172A;">
                    <td style="padding: 12px 0; color: #0F172A; font-weight: bold; font-size: 18px;">الإجمالي</td>
                    <td style="padding: 12px 0; color: #F97316; font-weight: bold; font-size: 18px; text-align: left;">{{ number_format($order->total, 2) }} ₪</td>
                </tr>
            </table>

            <p style="color: #94A3B8; font-size: 13px; text-align: center; margin-top: 30px;">
                لأي استفسار، تواصل معنا على {{env('COMPANY_EMAIL')}}
            </p>
        </div>

        {{-- الفوتر --}}
        <div style="background: #F8FAFC; padding: 15px; text-align: center; border-top: 1px solid #E2E8F0;">
            <p style="color: #94A3B8; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} سوق. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>