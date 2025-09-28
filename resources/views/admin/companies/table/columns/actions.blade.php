<div class="flex gap-2">
    <x-button :name="__('Edit')" icon:trailing="pencil-square" :href="route('companies.edit', ['company' => $row->id])" />
    <x-button :name="__('Delete')" variant="danger" wire:click="showDeleteModal({{ $row->id }})" icon:trailing="trash" :loading="false" />
</div>
