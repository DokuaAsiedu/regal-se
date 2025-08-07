<?php

namespace App\Livewire\Client\Transactions\Paystack;

use App\Services\TransactionService;
use App\Traits\HandlesErrorMessage;
use Livewire\Component;
use Throwable;

class Verify extends Component
{
    use HandlesErrorMessage;

    public $reference;
    public $pending_verification = true;
    public $verification_success;
    public $verification_data;

    protected $transactionService;

    public function boot(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function mount($reference)
    {
        try {
            $this->reference = $reference;
            $this->loadData();
        } catch (Throwable $err) {
            $default_message = 'Error verifying transaction';
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
            $this->pending_verification = false;
            $this->verification_success = false;
        }
    }

    public function loadData()
    {
        $this->verifyTransaction();
    }

    public function verifyTransaction()
    {
        $this->verification_data = $this->transactionService->verifyPaystackTransaction($this->reference);

        switch ($this->verification_data['status']) {
            case 'success':
                $this->verification_success = true;
                break;
            default:
                $this->verification_success = false;
                break;
        }

        $this->pending_verification = false;
    }

    public function render()
    {
        return view('livewire.client.transactions.paystack.verify');
    }
}
