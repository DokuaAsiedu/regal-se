<?php

namespace App\Livewire\Admin\Products;

use App\Services\CategoryService;
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
    public $selected_category_id = '';
    public $product_categories = [];
    public $available_categories = [];

    public $id;
    public $product;

    public $header;
    public $success_message;
    public $error_message;

    protected $productService;
    protected $categoryService;

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

    public function boot(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
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

        $this->product_categories = $this->getProductCategories();
        $this->available_categories = $this->getAvailableCategories();
    }

    public function getProductCategories()
    {
        $product_categories = isset($this->id) ? $this->product
            ->categories()
            ->get()
            ->map(fn ($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                ]
            )
            ->all() : [];

        return $product_categories;
    }

    public function getAvailableCategories()
    {
        $available_categories = $this->categoryService
            ->allQuery()
            ->active()
            ->get()
            ->filter(function ($item) {
                $product_categories = collect($this->product_categories);
                return !$product_categories->contains('id', $item['id']);
            })
            ->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
            ])
            ->all();

        return $available_categories;
    }

    public function addCategory()
    {
        try {
            $available_categories = collect($this->available_categories);
            $category = $available_categories
                ->where('id', $this->selected_category_id)
                ->first();
            // dd($category, $this->product_categories);
            $this->product_categories[] = $category;
            $this->selected_category_id = '';
            $this->available_categories = $this->getAvailableCategories();
        } catch (Throwable $err) {
            $default_message = 'Error adding category';
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
        }
    }

    public function removeCategory($category_id)
    {
        try {
            $product_categories = collect($this->product_categories);
            $this->product_categories = $product_categories->filter(fn ($item) => $item['id'] != $category_id)->all();
            $this->available_categories = $this->getAvailableCategories();
        } catch (Throwable $err) {
            $default_message = 'Error adding category';
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
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
                'categories' => $this->product_categories,
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
