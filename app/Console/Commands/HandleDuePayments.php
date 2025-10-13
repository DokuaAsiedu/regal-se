<?php

namespace App\Console\Commands;

use App\Services\PaymentService;
use Illuminate\Console\Command;

class HandleDuePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:handle-due-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process any payments due today';

    /**
     * Execute the console command.
     */
    public function handle(PaymentService $paymentService)
    {
        $paymentService->handleDuePayments();
    }
}
