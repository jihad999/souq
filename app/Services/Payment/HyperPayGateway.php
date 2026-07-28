<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HyperPayGateway implements PaymentGatewayInterface
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.hyperpay.mode') === 'live'
            ? 'https://oppwa.com'
            : 'https://eu-test.oppwa.com';
    }

    public function initiate(Order $order): ?string
    {
        $response = Http::withToken(config('services.hyperpay.access_token'))
        ->asForm()
        ->post("{$this->baseUrl}/v1/checkouts", [
            'entityId' => config('services.hyperpay.entity_id'),
            'amount' => number_format($order->total, 2, '.', ''),
            'currency' => 'USD',
            'paymentType' => 'DB',
            'merchantTransactionId' => $order->order_number,
            'shopperResultUrl' => route('payment.callback', ['order' => $order->order_number, 'gateway' => 'hyperpay']),
        ]);

        $data = $response->json();

        PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'hyperpay',
            'transaction_id' => $data['id'] ?? null,
            'amount' => $order->total,
            'status' => 'pending',
        ]);

        // HyperPay بيحتاج صفحة widget خاصة فيه (JS SDK) مش redirect عادي متل الباقي
        // بنرجع checkout ID، ونستخدمه بصفحة وسيطة فيها widget.js تبع HyperPay
        return route('payment.hyperpay.widget', ['order' => $order->order_number, 'checkoutId' => $data['id']]);
    }

    public function verify(Request $request, Order $order): bool
    {
        $checkoutId = $request->get('id');

        if (! $checkoutId) {
            return false;
        }

        $response = Http::withToken(config('services.hyperpay.access_token'))
            ->get("{$this->baseUrl}/v1/checkouts/{$checkoutId}/payment", [
                'entityId' => config('services.hyperpay.entity_id'),
            ]);

        $data = $response->json();
        $resultCode = $data['result']['code'] ?? '';

        // HyperPay: النجاح لو الكود يبدأ بـ 000.000. أو 000.100.1
        $isSuccess = (bool) preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $resultCode);

        $order->transactions()->where('gateway', 'hyperpay')->latest()->first()?->update([
            'status' => $isSuccess ? 'success' : 'failed',
            'gateway_response' => $data,
        ]);

        return $isSuccess;
    }

    public function refund(Order $order, float $amount): bool
    {
        $transaction = $order->transactions()->where('gateway', 'hyperpay')->where('status', 'success')->latest()->first();

        if (! $transaction) {
            return false;
        }

        $response = Http::withToken(config('services.hyperpay.access_token'))
            ->asForm()
            ->post("{$this->baseUrl}/v1/payments/{$transaction->transaction_id}", [
                'entityId' => config('services.hyperpay.entity_id'),
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => 'USD',
                'paymentType' => 'RF',
            ]);

        return $response->successful();
    }
}