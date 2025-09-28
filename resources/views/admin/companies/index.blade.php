<x-layouts.admin :title="__('Companies')">
    <div>
        <flux:heading size="xl">{{ __('Companies') }}</flux:heading>

        <livewire:tables.admin.companies.companies-table />
    </div>
</x-layouts.admin>
<!-- Very little is needed to make a happy life. - Marcus Aurelius -->
