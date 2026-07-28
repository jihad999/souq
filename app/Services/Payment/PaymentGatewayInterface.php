<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * يبدأ عملية الدفع ويرجع رابط إعادة توجيه (redirect) لصفحة الدفع الخارجية،
     * أو null لو الدفع بيصير مباشرة بدون توجيه خارجي.
     */
    public function initiate(Order $order): ?string;

    /**
     * يتحقق من نجاح العملية بعد رجوع العميل من بوابة الدفع (Callback/Webhook).
     */
    public function verify(Request $request, Order $order): bool;

    /**
     * يسترجع مبلغ (كامل أو جزئي) من عملية دفع سابقة.
     */
    public function refund(Order $order, float $amount): bool;
}