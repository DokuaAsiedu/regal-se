<div class="mb-3 flex justify-end gap-5">
    <x-button :name="__('New Company')" variant="primary" icon="plus-circle" :href="route('companies.create')" />

    <x-button :name="__('Add Staff')" icon="plus-circle" :href="route('company-staff.index')" />
</div>
