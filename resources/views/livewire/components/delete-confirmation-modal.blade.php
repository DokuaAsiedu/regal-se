<x-card>
    <flux:icon.exclamation-triangle class="size-32 text-red-500" />
    <flux:heading size="lg">Are you sure?</flux:heading>
    <flux:text>This action cannot be reversed</flux:text>
    <div class="flex gap-2">
        <flux:button wire:click="$dispatch('closeModal')" variant="filled">Cancel</flux:button>
        <flux:button type="button" variant="danger" wire:click="delete">Delete</flux:button>
    </div>
</x-card>
{{-- Success is as dangerous as failure. --}}
