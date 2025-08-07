<?php

namespace App\View\Components\Client\Transactions;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Success extends Component
{
    public $transaction_details;
    /**
     * Create a new component instance.
     */
    public function __construct($transactionDetails)
    {
        $this->transaction_details = $transactionDetails;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.client.transactions.success');
    }
}
