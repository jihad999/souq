<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class CodGateway implements PaymentGatewayInterface
{
    public function initiate(Order $order): ?string
    {
        PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'cod',
            'amount' => $order->total,
            'status' => 'pending', // بينتقل لـ paid يدويًا من الأدمن عند التسليم فعليًا
        ]);

        return null; // ما في توجيه خارجي، بنكمل مباشرة لصفحة النجاح
    }

    public function verify(Request $request, Order $order): bool
    {
        return true; // COD ما بيحتاج تحقق فوري
    }

    public function refund(Order $order, float $amount): bool
    {
        // استرجاع COD بيصير يدويًا (كاش)، بنسجله بس بالنظام
        return true;
    }
}