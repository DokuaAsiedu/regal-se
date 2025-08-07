<?php

namespace App\Services;

use App\Enums\PaymentGateways;
use App\Models\Payment;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Str;

class TransactionService
{
    protected $transactionRepository;
    protected $paystackService;

    /**
     * Create a new class instance.
     */
    public function __construct(TransactionRepository $transactionRepository, PaystackService $paystackService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->transactionRepository = $transactionRepository;
        $this->paystackService = $paystackService;
    }

    public function find($id)
    {
        return $this->transactionRepository->find($id);
    }

    public function all()
    {
        return $this->transactionRepository->all();
    }

    public function allQuery($search = [])
    {
        return $this->transactionRepository->allQuery($search);
    }

    public function store($input)
    {
        return $this->transactionRepository->create($input);
    }

    public function update($id, $input)
    {
        return $this->transactionRepository->update($input, $id);
    }

    public function delete($ids)
    {
        $this->transactionRepository->delete($ids);
    }

    public function generateReference()
    {
        do {
            $reference = Str::random();
        } while ($this->transactionRepository->allQuery(['reference' => $reference])->exists());

        return $reference;
    }

    public function initializePaystackTransaction($data, Payment $payment)
    {
        $data = array_merge($data, [
            'callback_url' => route('transactions.paystack.verify'),
            'reference' => $this->generateReference(),
        ]);

        $response = $this->paystackService->initializeTransaction($data);

        $authorization_url = $response['authorization_url'];
        $reference = $response['reference'];
        $transaction_payload = [
            'authorization_url' => $authorization_url,
            'reference' => $reference,
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'gateway' => PaymentGateways::Paystack->value,
        ];
        $this->store($transaction_payload);

        return $response;
    }

    public function verifyPaystackTransaction($reference)
    {
        $response = $this->paystackService->verifyTransaction($reference);
        $response_data = $response['data'];

        return $response_data;
    }
}
