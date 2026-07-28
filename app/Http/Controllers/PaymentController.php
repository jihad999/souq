<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * يبدأ عملية الدفع الفعلية (يستدعى بعد ما يصير الطلب pending بحالة checkout.store)
     */
    public function process(Order $order)
    {
        // لو الطلب مدفوع مسبقًا أو COD، ما في داعي نعيد العملية
        if ($order->payment_status === 'paid' || $order->payment_method === 'cod') {
            return redirect()->route('checkout.success', $order->order_number);
        }

        $gateway = PaymentGatewayFactory::make($order->payment_method);
        $redirectUrl = $gateway->initiate($order);

        if (! $redirectUrl) {
            return redirect()->route('checkout.success', $order->order_number);
        }

        return redirect()->away($redirectUrl);
    }

    /**
     * يستقبل رجوع العميل من بوابة الدفع الخارجية (Stripe/PayTabs) ويتحقق من نجاح العملية
     */
    public function callback(Request $request, Order $order)
    {
        $gatewayName = $request->get('gateway', $order->payment_method);
        $gateway = PaymentGatewayFactory::make($gatewayName);

        $isSuccess = $gateway->verify($request, $order);

        if ($isSuccess) {
            $order->update(['payment_status' => 'paid', 'status' => 'processing']);

            // إرسال الفاتورة بالإيميل رح ينضاف هون بالخطوة الجاية

            return redirect()->route('checkout.success', $order->order_number);
        }

        $order->update(['payment_status' => 'failed']);

        return redirect()->route('checkout.index')->with('error', 'فشلت عملية الدفع، حاول مرة أخرى أو اختر طريقة دفع أخرى.');
    }

    /**
     * صفحة وسيطة خاصة بـ HyperPay لأنها تحتاج JS Widget مش Redirect عادي
     */
    public function hyperPayWidget(Request $request, Order $order)
    {
        $checkoutId = $request->get('checkoutId');

        abort_if(! $checkoutId, 404);

        return view('payment.hyperpay-widget', compact('order', 'checkoutId'));
    }
}