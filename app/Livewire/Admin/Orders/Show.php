<?php

namespace App\Livewire\Admin\Orders;

use App\Services\OrderService;
use App\Services\StatusService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class Show extends Component
{
    use HandlesErrorMessage;

    public $order_id;
    public $order;
    public $pending_order;

    protected $orderService;
    protected $statusService;

    public function boot(OrderService $orderService, StatusService $statusService)
    {
        $this->orderService = $orderService;
        $this->statusService = $statusService;
    }

    public function mount($orderId)
    {
        try {
            $this->order_id = $orderId;
            $this->loadData();
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            toastr()->error(__('Error mounting component') . ': '. $message);
        }
    }

    public function loadData()
    {
        $this->order = $this->orderService->find($this->order_id);
        $this->pending_order = $this->orderService->isPending($this->order);
    }

    public function approve()
    {
        try {
            DB::beginTransaction();
            $payload = [
                'status_id' => $this->statusService->approved()->id,
            ];
            $this->orderService->update($this->order_id, $payload);
            DB::commit();
            $this->loadData();
            toastr()->success(__('Successfully approved order'));
        } catch (Throwable $err) {
            DB::rollBack();
            $default_message = __('Error approving order');
            $message = $this->handle($err, $default_message)->message;
            toastr()->error($message);
        }
    }

    public function render()
    {
        return view('livewire.admin.orders.show');
    }
}
