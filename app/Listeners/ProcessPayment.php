<?php

namespace App\Listeners;

use App\Events\PaymentProcessed;
use App\Services\PaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessPayment
{
    protected $paymentService;

    /**
     * Create the event listener.
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentProcessed $event): void
    {
        $this->paymentService->processPayment($event->transaction);
    }
}
