<div class="flex flex-col gap-6">
    @forelse ($products as $elem)
        <div class="p-5 flex flex-col gap-3 border-1 rounded-lg border-gray-200">
            <flux:heading size="lg">{{ $elem->name }}</flux:heading>
            <div class="flex max-sm:flex-col sm:items-center sm:justify-between gap-2">
                <flux:text class="text-sm">{{ $this->currency . ' ' . $elem->selling_price }}</flux:text>
                <div class="flex items-center gap-2">
                    <flux:icon.plus class="cursor-pointer" wire:click="increment({{ $elem->id }})" />
                    <flux:input type="number" class="max-w-20" :value="$elem->order_quantity" min="0" wire:change="updateQuantity($event.target.value, {{ $elem->id }})" />
                    <flux:icon.minus class="cursor-pointer" wire:click="decrement({{ $elem->id }})" />
                </div>
            </div>
        </div>
    @empty
        <div>{{ __('No items added') }}</div>
    @endforelse
</div>
{{-- Do your work, then step back. --}}
