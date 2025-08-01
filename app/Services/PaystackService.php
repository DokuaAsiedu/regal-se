<?php

namespace App\Services;

use App\Enums\PaymentGateways;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class PaystackService
{
    protected $transactionService;

    /**
     * Create a new class instance.
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function initializeTransaction(array $data, Payment $payment)
    {
        $initialize_transaction_endpoint = config('services.paystack.endpoints.initialize_transaction');
        $secret_key = config('services.paystack.secret_key');

        $data = array_merge($data, [
            'callback_url' => route('payment.successful'),
        ]);

        $response = Http::withToken($secret_key)
            ->withHeader('Content-Type', 'application/json')
            ->post($initialize_transaction_endpoint, $data)
            ->throw()
            ->json();

        $authorization_url = $response['data']['authorization_url'];
        $reference = $response['data']['reference'];
        $transaction_payload = [
            'authorization_url' => $authorization_url,
            'reference' => $reference,
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'gateway' => PaymentGateways::Paystack->value,
        ];
        $this->transactionService->store($transaction_payload);

        return $response;
    }
}
