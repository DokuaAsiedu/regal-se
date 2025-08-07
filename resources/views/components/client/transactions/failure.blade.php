<div class="flex flex-col items-center justify-center gap-3">
    <flux:icon.exclamation-circle variant="solid" class="text-red-500 size-1/3 md:size-1/6" />
    <flux:heading level="1" size="xl" class="text-center">{{ __('Payment Failed') }}</flux:heading>
    <flux:text>
        {{ __('Hey there! We tried processing payment but it seems something went wrong. Please use the button below to try again') }}
    </flux:text>
    <flux:button :href="route('home')" variant="primary">{{ __('Retry') }}</flux:button>
    <flux:text>
        <span>{{ __('Need help?') }} </span>
        <flux:link href="#">{{ 'Contact Support' }}</flux:link>
    </flux:text>
</div>
<!-- Be present above all else. - Naval Ravikant -->
