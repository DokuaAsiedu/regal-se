<?php

namespace App\Services;

use App\Enums\PaymentGateways;
use App\Events\PaymentProcessed;
use App\Exceptions\CustomException;
use App\Models\Payment;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionService
{
    protected $transactionRepository;
    protected $paystackService;
    protected $statusService;

    /**
     * Create a new class instance.
     */
    public function __construct(TransactionRepository $transactionRepository, PaystackService $paystackService, StatusService $statusService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->transactionRepository = $transactionRepository;
        $this->paystackService = $paystackService;
        $this->statusService = $statusService;
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

    public function handlePaystackWebhook(Request $request)
    {
        $secret = config('services.paystack.secret_key');
        $hash = hash_hmac('sha512', $request->getContent(), $secret);

        if ($hash !== $request->header('X-Paystack-Signature', '')) {
            throw new CustomException('Invalid signature');
        }

        $payload = $request->all();

        $event = $payload['event'] ?? null;

        switch ($event) {
            case 'charge.success':
                $this->processPaystackTransaction($payload);
                break;
            default:
                logger()->error("Unhandled Paystack event: {$event}");
                break;
        }
    }

    public function processPaystackTransaction($input)
    {
        $request_data = $input['data'];
        $reference = $request_data['reference'] ?? null;
        $transaction = $this->allQuery([
            'reference' => $reference
        ])
        ->first();

        $transaction->payload = json_encode($input);
        $transaction->channel = $request_data['channel'];
        $transaction->paid_at = Carbon::parse($request_data['paid_at']);
        $transaction->transaction_id = $request_data['id'];
        $transaction->processed_at = Carbon::now();

        switch ($request_data['status']) {
            case 'success':
                $transaction->status_id = $this->statusService->success()->id;
                break;
            case 'failed':
                $transaction->status_id = $this->statusService->failed()->id;
                break;
            case 'abandoned':
                $transaction->status_id = $this->statusService->abandoned()->id;
                break;
            default:
                $transaction->status_id = $this->statusService->unknown()->id;
                logger()->warning("Unknown status from paystack: {$request_data['status']} for transaction with reference: {$transaction['reference']}");
        }
        $transaction->save();

        // process payment linked to the transaction
        event(new PaymentProcessed($transaction));
    }
}
