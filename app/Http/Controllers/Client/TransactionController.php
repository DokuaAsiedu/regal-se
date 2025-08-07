<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function verifyPaystackTransaction(Request $request)
    {
        $reference = $request->query('reference');

        return view('client.transactions.paystack.verify', compact('reference'));
    }

    public function webhook(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->transactionService->handlePaystackWebhook($request);
            DB::commit();
            return response()->json([
                'message' => 'Webhook received',
            ]);
        } catch (Throwable $err) {
            $default_message = 'Error processing webhook';
            $message = $this->handle($err, $default_message)->message;
            return response()->json([
                'message' => $message,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
