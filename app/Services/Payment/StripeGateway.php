<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Refund;
use Stripe\Stripe;

class StripeGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function initiate(Order $order): ?string
    {
        $successUrl = route('payment.callback', [
            'order' => $order->order_number,
            'gateway' => 'stripe',
        ]) . '&session_id={CHECKOUT_SESSION_ID}';

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => "طلب رقم {$order->order_number}"],
                    'unit_amount' => (int) round($order->total * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => route('checkout.index'),
            'metadata' => ['order_id' => $order->id],
        ]);

        PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'stripe',
            'transaction_id' => $session->id,
            'amount' => $order->total,
            'status' => 'pending',
        ]);

        return $session->url;
    }

    public function verify(Request $request, Order $order): bool
    {
        $sessionId = $request->get('session_id');

        if (! $sessionId) {
            return false;
        }

        $session = Session::retrieve($sessionId);

        $isPaid = $session->payment_status === 'paid';

        $order->transactions()->where('gateway', 'stripe')->latest()->first()?->update([
            'status' => $isPaid ? 'success' : 'failed',
            'gateway_response' => $session->toArray(),
        ]);

        return $isPaid;
    }

    public function refund(Order $order, float $amount): bool
    {
        $transaction = $order->transactions()->where('gateway', 'stripe')->where('status', 'success')->latest()->first();

        if (! $transaction) {
            return false;
        }

        try {
            $session = Session::retrieve($transaction->transaction_id);

            Refund::create([
                'payment_intent' => $session->payment_intent,
                'amount' => (int) round($amount * 100),
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}