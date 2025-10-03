<div class="flex gap-2">
    <x-button :name="__('Edit')" icon:trailing="pencil-square" wire:click="$dispatch('edit-staff', { id: {{ $row->id }} })" />

    <x-button :name="__('Delete')" variant="danger" wire:click="showDeleteModal({{ $row->id }})" icon:trailing="trash" :loading="false" />
</div>
