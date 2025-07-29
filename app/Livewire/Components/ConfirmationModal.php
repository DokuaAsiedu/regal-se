<?php

namespace App\Livewire\Components;

use App\Traits\HandlesErrorMessage;
use LivewireUI\Modal\ModalComponent;

class ConfirmationModal extends ModalComponent
{
    use HandlesErrorMessage;

    public $id;
    public $event;

    public $icon;
    public $title;
    public $sub_title;
    public $confirm_text;
    public $reason;
    public $show_input;

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public function mount($id, $event, $showInput = false, $title = null, $sub_title = null, $confirmText = null, $icon = null)
    {
        $this->id = $id;
        $this->event = $event;
        $this->show_input = $showInput;
        $this->title = $title ?? __('Are you sure?');
        $this->sub_title = $sub_title ?? __('This action cannot be reversed');
        $this->confirm_text = $confirmText ?? ('Confirm');
        $this->icon = $icon ?? 'exclamation-triangle';
    }

    public function confirm()
    {
        $this->dispatch($this->event, id: $this->id, reason: $this->reason);
    }

    public function render()
    {
        return view('livewire.components.confirmation-modal');
    }
}
