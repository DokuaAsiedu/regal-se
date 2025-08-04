<?php

namespace App\Services;

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

        return $response['data'];
    }


        return $response;
    }
}
