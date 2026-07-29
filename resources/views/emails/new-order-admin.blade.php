<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background: #F8FAFC; padding: 30px; margin: 0;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">

        <div style="background: #F97316; padding: 20px; text-align: center;">
            <h2 style="color: white; margin: 0;">🔔 طلب جديد وصل!</h2>
        </div>

        <div style="padding: 30px;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <tr>
                    <td style="padding: 10px 0; color: #64748B; font-size: 14px;">رقم الطلب</td>
                    <td style="padding: 10px 0; color: #0F172A; font-weight: bold; text-align: left;">{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #64748B; font-size: 14px;">اسم العميل</td>
                    <td style="padding: 10px 0; color: #0F172A; text-align: left;">{{ $order->customer_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #64748B; font-size: 14px;">البريد الإلكتروني</td>
                    <td style="padding: 10px 0; color: #0F172A; text-align: left;" dir="ltr">{{ $order->customer_email }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #64748B; font-size: 14px;">رقم الهاتف</td>
                    <td style="padding: 10px 0; color: #0F172A; text-align: left;" dir="ltr">{{ $order->customer_phone }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #64748B; font-size: 14px;">عنوان الشحن</td>
                    <td style="padding: 10px 0; color: #0F172A; text-align: left;">{{ $order->shipping_address }}</td>
                </tr>
                @if($order->latitude && $order->longitude)
                <tr>
                    <td style="padding: 10px 0; color: #64748B; font-size: 14px;">الموقع</td>
                    <td style="padding: 10px 0; text-align: left;">
                        <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" style="color: #F97316;">عرض على الخريطة</a>
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 10px 0; color: #64748B; font-size: 14px;">طريقة الدفع</td>
                    <td style="padding: 10px 0; color: #0F172A; text-align: left;">
                        @switch($order->payment_method)
                            @case('cod') الدفع عند الاستلام @break
                            @case('stripe') Stripe @break
                            @case('paytabs') PayTabs @break
                        @endswitch
                    </td>
                </tr>
                <tr style="border-top: 2px solid #0F172A;">
                    <td style="padding: 12px 0; color: #0F172A; font-weight: bold; font-size: 16px;">الإجمالي</td>
                    <td style="padding: 12px 0; color: #F97316; font-weight: bold; font-size: 16px; text-align: left;">{{ number_format($order->total, 2) }} ₪</td>
                </tr>
            </table>

            <h3 style="color: #0F172A; font-size: 15px;">المنتجات المطلوبة</h3>
            <ul style="padding-right: 20px; color: #475569; font-size: 14px; line-height: 1.8;">
                @foreach($order->items as $item)
                    <li>{{ $item->product_name }} × {{ $item->quantity }} — {{ number_format($item->total_price, 2) }} ₪</li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>