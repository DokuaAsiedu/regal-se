<div class="flex flex-col gap-5">
    <flux:heading level="1" size="xl">{{ $header }}</flux:heading>

    <form wire:submit="save" class="grid lg:grid-cols-2 gap-6">
        <div class="flex flex-col gap-2">
            <label for="name">{{ __('Name') }}<x-required /></label>
            <flux:input type="text" id="name" wire:model="name" />
            <flux:error name="name" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="code">{{ __('Code') }}</label>
            <flux:input type="text" id="code" wire:model="code" />
            <flux:error name="code" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="phone">{{ __('Phone') }} <x-required /></label>
            <flux:input type="text" id="phone" wire:model="phone" class="w-full" />
            <flux:error name="phone" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="email">{{ __('Email') }} <x-required /></label>
            <flux:input type="text" id="email" wire:model="email" />
            <flux:error name="email" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="address">{{ __('Address') }} <x-required /></label>
            <flux:input type="text" id="address" wire:model="address" />
            <flux:error name="address" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="status">{{ __('Status') }}<x-required /></label>
            <flux:select id="status" wire:model="status">
                <flux:select.option value="" disabled>Select an option...</flux:select.option>
                @foreach ($this->validStatuses as $item)
                    <flux:select.option value="{{ $item->id }}">{{ $item->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="status" />
        </div>

        <x-button :name="__('Save')" type="submit" class="lg:col-span-2" />
    </form>
</div>
@script
<script>
    let iti = window.initIntlTelInput('phone');
    iti.setCountry($wire.phone_country_code)

    Livewire.hook('morphed', ({ component, cleanup }) => {
        iti = window.initIntlTelInput('phone');
        iti.setCountry(component.canonical.phone_country_code)
    })

    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        // Runs immediately before a commit's payload is sent to the server...
        commit.updates.phone_prefix = iti.getSelectedCountryData().dialCode
        commit.updates.phone_country_code = iti.getSelectedCountryData().iso2
    })
</script>
@endscript
{{-- The Master doesn't talk, he acts. --}}
