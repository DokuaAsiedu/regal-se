<?php

namespace App\Services;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Database\Eloquent\Model;

class PaymentService
{
    protected $paymentRepository;
    protected $paystackService;

    /**
     * Create a new class instance.
     */
    public function __construct(
        PaymentRepository $paymentRepository,
        PaystackService $paystackService,
    )
    {
        $this->paymentRepository = $paymentRepository;
        $this->paystackService = $paystackService;
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

    public function getPaymentLink(Payment $payment, string $customer_email, $reference = null)
    {
        $amount = $payment->amount * 100;
        $currency = $payment->currency;
        $data = [
            'email' => $customer_email,
            'amount' => $amount,
            'currency' => $currency,
        ];
        if ($reference) $data['reference'] = $reference;
        $response = $this->paystackService->initializeTransaction($data, $payment);

        $payment_link = $response['data']['authorization_url'];
        return $payment_link ?? 'sdfas';
    }

    public function getNextDuePayment(Model $payable)
    {
        return $payable->payments()
            ->orderBy('due_date')
            ->whereNull('paid_at')
            ->first();
    }
}
