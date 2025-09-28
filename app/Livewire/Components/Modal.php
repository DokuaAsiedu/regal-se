<?php

namespace App\Livewire\Components;

use App\Traits\HandlesErrorMessage;
use LivewireUI\Modal\ModalComponent;

class Modal extends ModalComponent
{
    use HandlesErrorMessage;

    public $view;
    public $data;

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public function mount($view, $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function render()
    {
        return view('livewire.components.modal');
    }
}
