<x-layouts.client>
    <div class="flex flex-col items-center justify-center gap-3">
        <flux:icon.check-badge variant="solid" class="text-green-500 size-1/4"/>
        <flux:heading level="1" size="xl">{{ __('Payment Successful!') }}</flux:heading>
        <flux:text>{{ __('Thank you for using our services') }}</flux:text>
        <div>
            <flux:heading level="3" size="lg">{{ ('Payment Details') }}</flux:heading>
            <div></div>
        </div>
        <flux:button :href="route('home')" variant="primary">{{ __('Back to homepage') }}</flux:button>
    </div>
</x-layouts.client>
<!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
