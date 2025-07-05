<x-layouts.app :title="__('Products')">
    <div>
        <flux:heading size="xl">{{ __('Products') }}</flux:heading>

        <livewire:tables.products.product-table />
    </div>
</x-layouts.app>
<!-- Nothing worth having comes easy. - Theodore Roosevelt -->
