<?php

namespace App\Livewire\Admin\Payments;

use App\Services\OrderService;
use App\Services\PaymentService;
use App\Traits\HandlesErrorMessage;
use Livewire\Component;
use Throwable;

class Show extends Component
{
    use HandlesErrorMessage;

    public $payment_id;
    public $payment;

    protected $paymentService;

    public function boot(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function mount($paymentId)
    {
        try {
            $this->payment_id = $paymentId;
            $this->loadData();
        } catch (Throwable $err) {
            $default_message = __('Error showing payment');
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
        }
    }

    public function loadData()
    {
        $this->payment = $this->paymentService->find($this->payment_id);
    }

    public function render()
    {
        return view('livewire.admin.payments.show');
    }
}
