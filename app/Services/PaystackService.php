<?php

namespace App\Services;

use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Http;

class PaystackService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function initializeTransaction(array $data)
    {
        $initialize_transaction_endpoint = config('services.paystack.endpoints.initialize_transaction');
        $secret_key = config('services.paystack.secret_key');

        $response = Http::withToken($secret_key)
            ->withHeader('Content-Type', 'application/json')
            ->post($initialize_transaction_endpoint, $data)
            ->throw()
            ->json();

        if (!$response['status']) {
            throw new CustomException($response['message']);
        }

        return $response['data'];
    }

    public function verifyTransaction($reference)
    {
        $verify_transaction_endpoint = config('services.paystack.endpoints.verify_transaction');
        $url = strtr($verify_transaction_endpoint, [
            ':reference' => $reference,
        ]);
        $secret_key = config('services.paystack.secret_key');

        $response = Http::withToken($secret_key)
            ->withHeader('Content-Type', 'application/json')
            ->get($url)
            ->throw()
            ->json();

        if (!$response['status']) {
            throw new CustomException($response['message']);
        }

        return $response;
    }
}
