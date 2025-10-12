<div class="flex flex-col gap-4">
    <div class="flex items-center justify-center gap-2">
        <flux:icon.check-circle class="text-green-500" />
        <flux:heading level="1" size="lg">{{ __('Order Placed!') }}</flux:heading>
    </div>
    <div class="flex flex-col gap-4">
        <flux:text>{{ __('Your order has been successfully placed. Please use the button below to make payment') }}</flux:text>
        <flux:button :href="$data" icon="banknotes" variant="primary">{{ __('Pay') }}</flux:button>
    </div>
</div>
<!-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin -->