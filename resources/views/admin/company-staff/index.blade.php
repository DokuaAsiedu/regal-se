<x-layouts.admin :title="__('Company Staff')">
    <div class="flex flex-col gap-5">
        <flux:heading size="xl">{{ __('Company Staff') }}</flux:heading>

        <livewire:admin.company-staff.create />

        <livewire:tables.admin.companies.staff-table />
    </div>
</x-layouts.admin>
<!-- Because you are alive, everything is possible. - Thich Nhat Hanh -->
