<?php

namespace App\Livewire\Admin\Transactions;

use App\Services\TransactionService;
use App\Traits\HandlesErrorMessage;
use Livewire\Component;
use Throwable;

class Show extends Component
{
    use HandlesErrorMessage;

    public $transaction_id;
    public $transaction;

    protected $transactionService;

    public function boot(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function mount($paymentId)
    {
        try {
            $this->transaction_id = $paymentId;
            $this->loadData();
        } catch (Throwable $err) {
            $default_message = __('Error showing transaction');
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
        }
    }

    public function loadData()
    {
        $this->transaction = $this->transactionService->find($this->transaction_id);
    }

    public function viewPayload()
    {
        $this->dispatch('openModal', component: 'components.modal', arguments: [
            'view' => 'components.admin.transaction.view-payload',
            'data' => $this->transaction->payload,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.transactions.show');
    }
}
