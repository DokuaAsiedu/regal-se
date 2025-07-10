<x-layouts.admin :title="__('Products')">
    <div>
        <flux:heading size="xl">{{ __('Products') }}</flux:heading>

        <livewire:tables.admin.products.product-table />
    </div>
</x-layouts.admin>
<!-- Nothing worth having comes easy. - Theodore Roosevelt -->
