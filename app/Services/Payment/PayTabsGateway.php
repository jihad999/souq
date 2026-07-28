<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayTabsGateway implements PaymentGatewayInterface
{
    protected string $baseUrl;

    public function __construct()
    {
        $region = config('services.paytabs.region', 'ARE');
        $this->baseUrl = "https://secure-{$region}.paytabs.com";
    }

    public function initiate(Order $order): ?string
    {
        $response = Http::withHeaders([
            'authorization' => config('services.paytabs.server_key'),
        ])->post("{$this->baseUrl}/payment/request", [
            'profile_id' => config('services.paytabs.profile_id'),
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'cart_id' => $order->order_number,
            'cart_currency' => 'USD',
            'cart_amount' => (float) $order->total,
            'cart_description' => "طلب رقم {$order->order_number}",
            'customer_details' => [
                'name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'street1' => $order->shipping_address,
                'country' => 'PS',
            ],
            'return' => route('payment.callback', ['order' => $order->order_number, 'gateway' => 'paytabs']),
        ]);

        $data = $response->json();

        PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'paytabs',
            'transaction_id' => $data['tran_ref'] ?? null,
            'amount' => $order->total,
            'status' => 'pending',
        ]);

        return $data['redirect_url'] ?? null;
    }

    public function verify(Request $request, Order $order): bool
    {
        $tranRef = $request->get('tranRef');

        if (! $tranRef) {
            return false;
        }

        $response = Http::withHeaders([
            'authorization' => config('services.paytabs.server_key'),
        ])->post("{$this->baseUrl}/payment/query", [
            'profile_id' => config('services.paytabs.profile_id'),
            'tran_ref' => $tranRef,
        ]);

        $data = $response->json();
        $isSuccess = ($data['payment_result']['response_status'] ?? null) === 'A'; // A = Authorized/Approved

        $order->transactions()->where('gateway', 'paytabs')->latest()->first()?->update([
            'status' => $isSuccess ? 'success' : 'failed',
            'gateway_response' => $data,
        ]);

        return $isSuccess;
    }

    public function refund(Order $order, float $amount): bool
    {
        $transaction = $order->transactions()->where('gateway', 'paytabs')->where('status', 'success')->latest()->first();

        if (! $transaction) {
            return false;
        }

        $response = Http::withHeaders([
            'authorization' => config('services.paytabs.server_key'),
        ])->post("{$this->baseUrl}/payment/request", [
            'profile_id' => config('services.paytabs.profile_id'),
            'tran_type' => 'refund',
            'tran_class' => 'ecom',
            'cart_id' => $order->order_number,
            'cart_currency' => 'USD',
            'cart_amount' => $amount,
            'cart_description' => "استرجاع طلب {$order->order_number}",
            'tran_ref' => $transaction->transaction_id,
        ]);

        return $response->successful();
    }
}