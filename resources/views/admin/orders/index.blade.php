<x-layouts.admin :title="__('Orders')">
    <div>
        <flux:heading size="xl">{{ __('Orders') }}</flux:heading>

        <livewire:tables.admin.orders.orders-table />
    </div>
</x-layouts.admin>
<!-- Simplicity is the essence of happiness. - Cedric Bledsoe -->
