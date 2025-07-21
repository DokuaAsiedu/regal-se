<div class="flex flex-col gap-6">
    <flux:heading size="xl">{{ __('Order Details') }}</flux:heading>

    <div class="flex flex-col gap-6">
        <div class="flex justify-between gap-2">
            <div class="flex items-center gap-4">
                <flux:heading size="lg">{{ __('Order') }} #{{ $order->code }}</flux:heading>
                <x-status :status="$order->status" />
                <div class="flex items-center gap-4">
                    <flux:icon.calendar size="2" />
                    <flux:text>{{ date('l jS F, Y, h:i a', strtotime($order->created_at)) }}</flux:text>
                </div>
            </div>
            <div>
                @if ($pending_order)
                    <flux:button variant="primary" color="green" wire:click="approve">{{ __('Approve') }}</flux:button>
                @endif
            </div>
        </div>

        <div class="grid md:grid-cols-5 gap-4">
            <div class="col-span-1 md:col-span-3">
                <div class="flex items-center gap-2">
                    <flux:heading level="3" size="lg">{{ __('Items') }}</flux:heading>
                    <flux:badge size="sm" variant="pill" color="blue">{{ $order->orderItems->count() }}
                    </flux:badge>
                </div>
                <livewire:tables.admin.orders.order-items-table :order_id="$order->id" />
            </div>
            <div class="col-span-1 md:col-span-2 flex flex-col gap-2">
                <flux:heading level="3" size="lg">{{ __('Customer') }}</flux:heading>
                <hr />
                <div class="flex flex-col gap-2">
                    <div class="flex flex-col gap-2">
                        <flux:text class="text-base">{{ $order->customer_name }}</flux:text>
                        <div class="flex items-center gap-2">
                            @if ($order->user)
                                <flux:badge>{{ $order->user->role->name }}</flux:badge>
                                <flux:badge>{{ $order->user->status->name }}</flux:badge>
                            @else
                                <flux:badge>{{ __('Guest') }}</flux:badge>
                            @endif
                        </div>
                    </div>
                    <hr />
                    <div class="flex flex-col gap-4">
                        <flux:heading level="4">{{ __('Contact Info') }}</flux:heading>
                        <div class="flex items-center gap-3">
                            <flux:icon.phone class="size-4" />
                            <flux:text inline>{{ $order->customer_phone ?? 'N/A' }}</flux:text>
                        </div>
                        <div class="flex items-center gap-3">
                            <flux:icon.at-symbol class="size-4" />
                            <flux:text inline>{{ $order->customer_email ?? 'N/A' }}</flux:text>
                        </div>
                    </div>
                    <hr />
                    <div class="flex flex-col gap-4">
                        <flux:heading level="4">{{ __('Delivery Address') }}</flux:heading>
                        <div class="flex items-center gap-3">
                            <flux:icon.map-pin class="size-4" />
                            <flux:text inline>{{ $order->delivery_address }}</flux:text>
                        </div>
                        <div>
                            <flux:text inline>{{ __('Landmark') }}: </flux:text>
                            <flux:text inline>{{ $order->landmark ?? 'N/A' }}</flux:text>
                        </div>
                        <div>
                            <flux:text inline>{{ __('Note') }}: </flux:text>
                            <flux:text inline>{{ $order->delivery_note ?? 'N/A' }}</flux:text>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
