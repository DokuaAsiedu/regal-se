<div class="flex flex-col gap-6">
    <flux:heading size="xl" class="border-b">{{ __('Payment Details') }}</flux:heading>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Amount:') }}</flux:heading>
            <flux:text>{{ formatCurrency($payment->amount, $payment->currency) }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Status:') }}</flux:heading>
            <x-status :status="$payment->status" />
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Payment Channel:') }}</flux:heading>
            <flux:text>{{ $payment->payment_channel ?? 'N/A' }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Transaction ID:') }}</flux:heading>
            <flux:text>{{ $payment->transaction_id ?? 'N/A' }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Paid At:') }}</flux:heading>
            <flux:text>{{ $payment->paid_at ? formatDate($payment->paid_at, 'F j, Y g:i A') : 'Not paid' }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Due Date:') }}</flux:heading>
            <flux:text>{{ formatDate($payment->due_date,'F j, Y') }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Linked To:') }}</flux:heading>
            <flux:text>
                <flux:link :href="route('orders.show', ['order' => $payment->payable_id])">{{ class_basename($payment->payable_type) }} #{{ $payment->payable->code }}</flux:link>
            </flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Created At:') }}</flux:heading>
            <flux:text>{{ formatDate($payment->created_at, 'jS F Y, h:i A') }}</flux:text>
        </div>

    </div>

</div>
{{-- Stop trying to control. --}}
