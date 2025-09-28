<div class="flex flex-col gap-6">
    <flux:heading size="xl">{{ __('Transaction Details') }}</flux:heading>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Amount --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Amount:') }}</flux:heading>
            <flux:text>{{ formatCurrency($transaction->amount, $transaction->currency) }}</flux:text>
        </div>

        {{-- Status --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Status:') }}</flux:heading>
            <x-status :status="$transaction->status" />
        </div>

        {{-- Payment Gateway --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Gateway:') }}</flux:heading>
            <flux:text>{{ $transaction->gateway }}</flux:text>
        </div>

        {{-- Channel --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Channel:') }}</flux:heading>
            <flux:text>{{ $transaction->channel ?? 'N/A' }}</flux:text>
        </div>

        {{-- Reference --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Reference:') }}</flux:heading>
            <flux:text>{{ $transaction->reference }}</flux:text>
        </div>

        {{-- Transaction ID --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Transaction ID:') }}</flux:heading>
            <flux:text>{{ $transaction->transaction_id ?? 'N/A' }}</flux:text>
        </div>

        {{-- Authorization URL --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Payment Link:') }}</flux:heading>
            <flux:text>
                <flux:link href="{{ $transaction->authorization_url }}" target="_blank">
                    {{ __('View Link') }}
                </flux:link>
            </flux:text>
        </div>

        {{-- Payment Relation --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Payment:') }}</flux:heading>
            <flux:text>
                <flux:link :href="route('payments.show', $transaction->payment_id)">
                    {{ __('Payment #') . $transaction->payment->id }}
                </flux:link>
            </flux:text>
        </div>

        {{-- Paid At --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Paid At:') }}</flux:heading>
            <flux:text>
                {{ $transaction->paid_at ? formatDate($transaction->paid_at, 'F j, Y g:i A') : __('Not paid') }}
            </flux:text>
        </div>

        {{-- Processed At --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Processed At:') }}</flux:heading>
            <flux:text>
                {{ $transaction->processed_at ? formatDate($transaction->processed_at, 'F j, Y g:i A') : __('Not processed') }}
            </flux:text>
        </div>

        {{-- Payload --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Payload:') }}</flux:heading>
            <flux:button icon:trailing="eye" wire:click="viewPayload" class="self-start">{{ __('View') }}</flux:button>
        </div>

        {{-- Created At --}}
        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Created At:') }}</flux:heading>
            <flux:text>{{ formatDate($transaction->created_at, 'jS F Y, h:i A') }}</flux:text>
        </div>
    </div>
</div>
{{-- The whole world belongs to you. --}}
