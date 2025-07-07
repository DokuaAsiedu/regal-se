@if (auth()->check() && auth()->user()->id != $row->id)
    <div class="flex gap-2">
        <x-button :name="__('Edit')" icon:trailing="pencil-square" :href="route('users.edit', ['user' => $row->id])" />
        <x-button :name="__('Delete')" variant="danger" wire:click="showDeleteModal({{ $row->id }})"
            icon:trailing="trash" :loading="false" />
    </div>
@endif
