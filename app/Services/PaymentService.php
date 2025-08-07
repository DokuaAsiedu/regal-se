<?php

namespace App\Services;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Database\Eloquent\Model;

class PaymentService
{
    protected $paymentRepository;
    protected $transactionService;

    /**
     * Create a new class instance.
     */
    public function __construct(
        PaymentRepository $paymentRepository,
        TransactionService $transactionService,
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->transactionService = $transactionService;
    }

    public function find($id)
    {
        return $this->paymentRepository->find($id);
    }

    public function all()
    {
        return $this->paymentRepository->all();
    }

    public function allQuery($search = [])
    {
        return $this->paymentRepository->allQuery($search);
    }

    public function store($input)
    {
        return $this->paymentRepository->create($input);
    }

    public function update($id, $input)
    {
        return $this->paymentRepository->update($input, $id);
    }

    public function delete($ids)
    {
        $this->paymentRepository->delete($ids);
    }

    public function getPaymentLink(Payment $payment, string $customer_email)
    {
        $amount = $payment->amount * 100;
        $currency = $payment->currency;
        $data = [
            'email' => $customer_email,
            'amount' => $amount,
            'currency' => $currency,
        ];

        $response = $this->transactionService->initializePaystackTransaction($data, $payment);

        $payment_link = $response['authorization_url'];
        return $payment_link;
    }

    public function getNextDuePayment(Model $payable)
    {
        return $payable->payments()
            ->orderBy('due_date')
            ->whereNull('paid_at')
            ->first();
    }
}
