<?php

namespace App\Livewire\Admin\Products;

use App\Services\ProductService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use HandlesErrorMessage;

    public $name;
    public $code;
    public $cost_price;
    public $selling_price;
    public $quantity;
    public $description;
    public $status = "";

    public $id;
    public $product;

    public $header;
    public $success_message;
    public $error_message;

    protected $productService;

    protected $rules = [
        'name' => 'required|string',
        'code' => 'nullable|string',
        'cost_price' => 'required|numeric|min:0',
        'selling_price' => 'nullable|numeric|min:0',
        'quantity' => 'required|integer|min:1',
        'description' => 'nullable|string',
        'status' => 'required|exists:status,id',
    ];

    public function mount($id = null)
    {
        try {
            $this->loadData($id);
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            flash()->error(__('Error mounting component') . ': '. $message);
        }
    }

    public function boot(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Computed]
    public function validStatuses()
    {
        return $this->productService->validStatuses();
    }

    public function loadData($id)
    {
        if (isset($id)) {
            $this->product = $this->productService->find($this->id);
            $this->name = $this->product->name ?? '';
            $this->code = $this->product->code ?? '';
            $this->cost_price = $this->product->cost_price ?? '';
            $this->selling_price = $this->product->selling_price ?? '';
            $this->quantity = $this->product->quantity ?? '';
            $this->description = $this->product->description ?? '';
            $this->status = $this->product->status->id ?? '';

            $this->header = __('Edit Product');
            $this->success_message = __('Product successfully updated');
        } else {
            $this->header = __('Create New Product');
            $this->success_message = __('Product successfully created');
        }
    }

    public function save()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $payload = [
                'name' => $this->name,
                'code' => $this->code,
                'cost_price' => $this->cost_price,
                'selling_price' => $this->selling_price,
                'quantity' => $this->quantity,
                'description' => $this->description,
                'status_id' => $this->status,
            ];
            if (isset($this->id)) {
                $this->productService->update($this->id, $payload);
            } else {
                $this->productService->store($payload);
            }
            DB::commit();
            flash()->success($this->success_message);
            return redirect()->route('products.index');
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            flash()->error($message);
        }
    }

    public function render()
    {
        return view('livewire.admin.products.create');
    }
}
