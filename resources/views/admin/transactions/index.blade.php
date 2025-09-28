<x-layouts.admin :title="__('Transactions')">
    <div>
        <flux:heading size="xl">{{ __('Transactions') }}</flux:heading>

        <livewire:tables.admin.transactions.transactions-table />
    </div>
</x-layouts.admin>
<!-- It is not the man who has too little, but the man who craves more, that is poor. - Seneca -->
