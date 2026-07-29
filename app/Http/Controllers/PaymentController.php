<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function process(Order $order)
    {
        if ($order->payment_status === 'paid' || $order->payment_method === 'cod') {
            return redirect()->route('checkout.success', $order->order_number);
        }

        $gateway = PaymentGatewayFactory::make($order->payment_method);
        $redirectUrl = $gateway->initiate($order);

        if (! $redirectUrl) {
            return redirect()->route('checkout.index')->with('error', 'حدث خطأ أثناء بدء عملية الدفع، حاول مرة أخرى أو اختر طريقة دفع أخرى.');
        }

        return redirect()->away($redirectUrl);
    }

    public function callback(Request $request, Order $order)
    {
        $gatewayName = $request->get('gateway', $order->payment_method);
        $gateway = PaymentGatewayFactory::make($gatewayName);

        $isSuccess = $gateway->verify($request, $order);

        if ($isSuccess) {
            $order->update(['payment_status' => 'paid', 'status' => 'processing']);

            $this->orderService->sendOrderEmails($order);

            return redirect()->route('checkout.success', $order->order_number);
        }

        $order->update(['payment_status' => 'failed']);

        return redirect()->route('checkout.index')->with('error', 'فشلت عملية الدفع، حاول مرة أخرى أو اختر طريقة دفع أخرى.');
    }
}