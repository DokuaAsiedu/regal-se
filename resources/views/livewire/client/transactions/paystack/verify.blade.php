<div>
    @if ($pending_verification)
    <div class="flex flex-col items-center justify-center gap-4">
        <flux:icon.loading/>
        <flux:text>{{ __('Processing...') }}</flux:text>
    </div>
    @else
        @if ($verification_success)
            <x-client.transactions.success :transactionDetails="$verification_data" />
        @else
            <x-client.transactions.failure />
        @endif
    @endif
</div>

{{-- The best athlete wants his opponent at his best. --}}
