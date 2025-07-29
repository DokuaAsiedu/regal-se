<x-card>
    <flux:icon :name="$icon" class="size-32 text-red-500" />
    <flux:heading size="lg">{{ $title }}</flux:heading>
    <flux:text>{{ $sub_title }}</flux:text>
    <div class="flex flex-col items-center gap-2">
    @if ($show_input)
        <div class="flex items-center gap-2">
            <label for="reason" class="text-gray-500">{{ __('Reason') }}:</label>
            <flux:input id="reason" class="h-10" wire:model="reason" />
        </div>
    @endif
    <div class="flex gap-2">
        <flux:button wire:click="$dispatch('closeModal')" variant="filled">{{ __('Cancel') }}</flux:button>
        <flux:button type="button" variant="danger" wire:click="confirm">{{ $confirm_text }}</flux:button>
    </div>
    </div>
</x-card>
{{-- Success is as dangerous as failure. --}}
