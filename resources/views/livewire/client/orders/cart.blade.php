<div class="flex flex-col gap-8">
    <div class="flex flex-col gap-6">
        @forelse ($cart_items as $elem)
            <div class="p-5 flex flex-col gap-3 border-1 rounded-lg border-gray-200">
                <div class="flex items-center justify-between gap-2">
                    <flux:heading size="lg">{{ $elem->name }}</flux:heading>
                    <flux:icon.trash class="size-4 text-red-500" wire:click="removeFromCart({{ $elem->product_id }})" />
                </div>
                <div class="flex max-sm:flex-col sm:items-center sm:justify-between gap-2">
                    <flux:text class="text-sm">{{ $this->currency . ' ' . $elem->price }}</flux:text>
                    <div class="flex items-center gap-2">
                        <flux:icon.plus class="cursor-pointer" wire:click="increment({{ $elem->product_id }})" />
                        <flux:input type="number" class="max-w-20" :value="$elem->quantity" min="0"
                            wire:change="updateQuantity($event.target.value, {{ $elem->product_id }})" />
                        <flux:icon.minus class="cursor-pointer" wire:click="decrement({{ $elem->product_id }})" />
                    </div>
                </div>
            </div>
        @empty
            <div>{{ __('No items added') }}</div>
        @endforelse
    </div>

    @if ($allow_order)
        <div class="self-end">
            <flux:button variant="primary" :href="route('checkout')">{{ __('Proceed to Checkout') }}</flux:button>
        </div>
    @endif
</div>
{{-- Do your work, then step back. --}}
