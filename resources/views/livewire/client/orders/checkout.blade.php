<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-4">
    <div class="">
        <form wire:submit="placeOrder" class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <label for="customer_name">{{ __('Name') }} <x-required /></label>
                <flux:input type="text" id="customer_name" wire:model="customer_name" />
                <flux:error name="customer_name" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="customer_phone">{{ __('Phone') }} <x-required /></label>
                <flux:input type="text" id="customer_phone" wire:model="customer_phone" class="w-full" />
                <flux:error name="customer_phone" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="customer_email">{{ __('Email') }} <x-required /></label>
                <flux:input type="text" id="customer_email" wire:model="customer_email" />
                <flux:error name="customer_email" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="delivery_address">{{ __('Delivery Address') }} <x-required /></label>
                <flux:input type="text" id="delivery_address" wire:model="delivery_address" />
                <flux:error name="delivery_address" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="landmark">{{ __('Landmark') }} <x-required /></label>
                <flux:input type="text" id="landmark" wire:model="landmark" />
                <flux:error name="landmark" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="delivery_note">{{ __('Delivery Note') }}</label>
                <flux:textarea id="delivery_note" wire:model="delivery_note" />
                <flux:error name="delivery_note" />
            </div>

            <x-button :name="__('Save')" type="submit" class="lg:col-span-2" />
        </form>
    </div>
    <div class="lg:self-start p-5 flex flex-col gap-6 border-2 rounded-lg">
        <flux:heading level="2" size="lg" class="text-lg">{{ __('Summary') }}</flux:heading>
        <div class="max-h-96 flex flex-col gap-4 overflow-auto">
            @forelse ($cart_items as $elem)
                <div class="grid grid-cols-5 items-center gap-2">
                    <div class="col-span-1">
                        <x-placeholder-pattern class="flex-grow-1 inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20 aspect-square" />
                    </div>
                    <div class="col-span-4">
                        <flux:heading size="lg">{{ $elem->name }}</flux:heading>

                        <flux:text>{{ __('Quantity') }}: {{ $elem->quantity }}</flux:text>

                        <flux:text>{{ __('Price') }}: {{ $this->currency . ' ' . $elem->price }}</flux:text>

                        @if (isset($elem->down_payment_amount))
                            <flux:text class="text-xs">{{ __('Down payment') }}: {{ $this->currency . ' ' . $elem->down_payment_amount }} ({{ $elem->down_payment_percentage }}%)</flux:text>

                            <flux:text class="text-xs">{{ __('Installment Amount') }}: {{ $this->currency . ' ' . $elem->installment_amount }}</flux:text>

                            <flux:text class="text-xs">{{ __('Installment period') }}: {{ $elem->installment_months }} {{ __('months') }}</flux:text>
                        @endif
                    </div>
                </div>
                <hr />
            @empty
                <div class="">{{ __('No items added to cart') }}</div>
                <flux:separator />
            @endforelse
        </div>
        <div class="flex flex-col gap-5">
            <flux:text class="flex items-center justify-between">
                <span>{{ __('Subtotal') }}:</span>
                <span>{{ $this->currency . ' ' . $subtotal }}</span>
            </flux:text>
            <flux:separator />
            <flux:text class="flex items-center justify-between text-lg font-bold">
                <span>{{ __('Total Amount') }}:</span>
                <span>{{ $this->currency . ' ' . $total_amount }}</span>
            </flux:text>
        </div>
    </div>
</div>
@script
<script>
    let iti = window.initIntlTelInput('customer_phone');
    iti.setCountry($wire.customer_phone_country_code)

    Livewire.hook('morphed', ({ component, cleanup }) => {
        iti = window.initIntlTelInput('customer_phone');
        iti.setCountry(component.canonical.customer_phone_country_code)
    })

    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        // Runs immediately before a commit's payload is sent to the server...
        commit.updates.customer_phone_prefix = iti.getSelectedCountryData().dialCode
        commit.updates.customer_phone_country_code = iti.getSelectedCountryData().iso2
    })
</script>
@endscript
{{-- Because she competes with no one, no one can compete with her. --}}
