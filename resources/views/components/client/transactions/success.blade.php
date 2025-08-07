<div class="w-full flex flex-col items-center justify-center gap-3">
    <flux:icon.check-badge variant="solid" class="text-green-500 size-1/3 md:size-1/6" />
    <flux:heading level="1" size="xl">{{ __('Payment Successful!') }}</flux:heading>
    <flux:text>{{ __('Thank you for using our services. Below is a summary of your transaction') }}</flux:text>
    <div class="w-full md:max-w-1/3 flex flex-col gap-2">
        <div class="flex max-md:flex-col md:justify-between md:items-center md:gap:10">
            <h5 class="!px-0">{{ 'Transaction ID:' }}</h5>
            <flux:text>{{ $transaction_details['id'] }}</flux:text>
            {{-- {{ json_encode($transaction_details) }} --}}
        </div>
        <div class="flex max-md:flex-col md:justify-between md:items-center md:gap:10">
            <h5 class="!px-0">{{ 'Date:' }}</h5>
            <flux:text>{{ formatDate($transaction_details['paid_at'], 'M d Y, h:i a') }}</flux:text>
        </div>
        <div class="flex max-md:flex-col md:justify-between md:items-center md:gap:10">
            <h5 class="!px-0">{{ 'Payment Method:' }}</h5>
            <flux:text>{{ $transaction_details['channel'] }}</flux:text>
        </div>
        <div class="flex max-md:flex-col md:justify-between md:items-center md:gap:10">
            <h5 class="!px-0">{{ 'Amount:' }}</h5>
            <flux:text>{{ $transaction_details['currency'] . ' ' . $transaction_details['amount'] }}</flux:text>
        </div>
    </div>
    <flux:button variant="primary" href="#">{{ __('Track your order') }}</flux:button>
    <flux:button :href="route('home')">{{ __('Back to homepage') }}</flux:button>
</div>
<!-- If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius -->
