<?php

namespace App\Livewire\Admin\Categories;

use App\Services\CategoryService;
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
    public $description;
    public $status = "";

    public $id;
    public $category;

    public $header;
    public $success_message;
    public $error_message;

    protected $categoryService;

    protected $rules = [
        'name' => 'required|string',
        'code' => 'nullable|string',
        'description' => 'nullable|string',
        'status' => 'required|exists:status,id',
    ];

    public function mount($id = null)
    {
        try {
            $this->loadData($id);
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            toastr()->error(__('Error mounting component') . ': '. $message);
        }
    }

    public function boot(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    #[Computed]
    public function validStatuses()
    {
        return $this->categoryService->validStatuses();
    }

    public function loadData($id)
    {
        if (isset($id)) {
            $this->category = $this->categoryService->find($this->id);
            $this->name = $this->category->name ?? '';
            $this->code = $this->category->code ?? '';
            $this->description = $this->category->description ?? '';
            $this->status = $this->category->status->id ?? '';

            $this->header = __('Edit Category');
            $this->success_message = __('Category successfully updated');
        } else {
            $this->header = __('Create New Category');
            $this->success_message = __('Category successfully created');
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
                'description' => $this->description,
                'status_id' => $this->status,
            ];
            if (isset($this->id)) {
                $this->categoryService->update($this->id, $payload);
            } else {
                $this->categoryService->store($payload);
            }
            DB::commit();
            toastr()->success($this->success_message);
            return redirect()->route('categories.index');
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            toastr()->error($message);
        }
    }

    public function render()
    {
        return view('livewire.admin.categories.create');
    }
}
