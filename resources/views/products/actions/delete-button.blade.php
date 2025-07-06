<div>
    <flux:button variant="danger" wire:click="showDeleteModal({{ $row->id }})" icon:trailing="trash" :loading="false">
        {{ __('Delete') }}
    </flux:button>
</div>
<!-- Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less. - Marie Curie -->
