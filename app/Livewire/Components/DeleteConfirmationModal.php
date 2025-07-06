<?php

namespace App\Livewire\Components;

use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;
use Throwable;

class DeleteConfirmationModal extends ModalComponent
{
    use HandlesErrorMessage;

    public $ids;
    public $class;

    protected $service;

    public function boot()
    {
        $this->service = app($this->class);
    }

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return 'sm';
    }

    public function delete()
    {
        try {
            DB::beginTransaction();
            $this->service->delete($this->ids);
            DB::commit();
            $message = 'Successfully deleted item' . (count($this->ids) > 1 ? 's': '');
            toastr()->success($message);
            $this->dispatch('refresh');
            $this->closeModal();
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            toastr()->error($message);
        }
    }

    public function render()
    {
        return view('livewire.components.delete-confirmation-modal');
    }
}
