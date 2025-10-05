<div class="flex flex-col gap-6">
    @if (!$edit_mode)
        <div class="grid lg:grid-cols-2 gap-6">
            @if ($kyc)
                <div class="col-span-2 ms-auto">
                    <x-status :status="$kyc->status" />
                </div>
            @endif
            <div class="col-span-1 lg:col-span-2 flex flex-col gap-2">
                <flux:heading level="2" size="xl" class="">{{ __('Personal Details') }}
                </flux:heading>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Full Name') }}</flux:heading>
                <flux:text>{{ $name }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Phone Number') }}</flux:heading>
                <flux:text>{{ $phone_prefix . $phone }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Address') }}</flux:heading>
                <flux:text>{{ $address }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Ghana Card Number') }}</flux:heading>
                <flux:text>{{ $ghana_card_number }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Date Of Birth') }}</flux:heading>
                <flux:text>{{ $date_of_birth }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Email') }}</flux:heading>
                <flux:text>{{ $email }}</flux:text>
            </div>

            <div class="col-span-1 lg:col-span-2 mt-8 flex flex-col gap-2">
                <flux:heading level="2" size="xl" class="">{{ __('Employment Details') }}
                </flux:heading>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Company Name') }}</flux:heading>
                <flux:text>{{ $company_name }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Staff Id') }}</flux:heading>
                <flux:text>{{ $staff_id }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Current Position') }}</flux:heading>
                <flux:text>{{ $current_position }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Employment Start Date') }}</flux:heading>
                <flux:text>{{ $employment_start_date }}</flux:text>
            </div>
        </div>
        @if (!$kyc_approved)
            <div class="self-end">
                <x-button :name="__('Edit')" type="button" variant="primary" wire:click="edit" />
            </div>
        @endif
    @else
        <flux:heading level="1" size="xl" class="text-center">{{ $header }}</flux:heading>

        <form wire:submit="save" class="grid lg:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
                <label for="name">{{ __('Full Name') }} <x-required /></label>
                <flux:input type="text" id="name" wire:model="name" />
                <flux:error name="name" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="phone">{{ __('Phone Number') }} <x-required /></label>
                <flux:input type="text" id="phone" wire:model="phone" />
                <flux:error name="phone" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="address">{{ __('Address') }} <x-required /></label>
                <flux:input type="text" id="address" wire:model="address" />
                <flux:error name="address" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="ghana_card_number">{{ __('Ghana Card Number') }} <x-required /></label>
                <flux:input type="text" id="ghana_card_number" wire:model="ghana_card_number" />
                <flux:error name="ghana_card_number" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="date_of_birth">{{ __('Date Of Birth') }} <x-required /></label>
                <input type="date" id="date_of_birth" class="p-2 border rounded-lg"
                    wire:model="date_of_birth" />
                <flux:error name="date_of_birth" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="email">{{ __('Email') }} <x-required /></label>
                <flux:input type="text" id="email" wire:model="email" />
                <flux:error name="email" />
            </div>

            @if (!auth()->check())
                <div class="flex flex-col gap-2">
                    <label for="password">{{ __('Password') }} <x-required /></label>
                    <flux:input type="password" id="password" wire:model="password" />
                    <flux:error name="password" />
                </div>

                <div class="flex flex-col gap-2">
                    <label for="password_confirmation">{{ __('Confirm Password') }} <x-required /></label>
                    <flux:input type="password" id="password_confirmation" wire:model="password_confirmation" />
                    <flux:error name="password_confirmation" />
                </div>
            @endif

            <div class="col-span-1 lg:col-span-2 mt-8 flex flex-col items-center gap-2">
                <flux:heading level="2" size="xl" class="text-center">{{ __('Employment Details') }}
                </flux:heading>
            </div>

            <div class="flex flex-col gap-2">
                <label for="company_id">{{ __('Company') }} <x-required /></label>
                <flux:select id="company_id" wire:model.live="company_id">
                    <flux:select.option value="">Select an option...</flux:select.option>
                    @foreach ($this->companies as $item)
                        <flux:select.option value="{{ $item->id }}">{{ $item->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="company_id" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="staff_id">{{ __('Staff Id') }} <x-required /></label>
                <flux:input type="text" id="staff_id" wire:model="staff_id" />
                <flux:error name="staff_id" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="current_position">{{ __('Current Position') }} <x-required /></label>
                <flux:input type="text" id="current_position" wire:model="current_position" />
                <flux:error name="current_position" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="employment_start_date">{{ __('Employment Start Date') }} <x-required /></label>
                <input type="date" id="employment_start_date" class="p-2 border rounded-lg"
                    wire:model="employment_start_date" />
                <flux:error name="employment_start_date" />
            </div>

            <div class="col-span-1 lg:col-span-2 flex justify-between gap-8">
                <x-button :name="__('Cancel')" type="button" class="lg:col-span-2 grow" variant="filled" wire:click="cancel" />
                <x-button :name="__('Save')" type="submit" class="lg:col-span-2 grow" variant="primary" />
            </div>
        </form>
    @endif
</div>
@script
    <script>
        let iti = window.initIntlTelInput('phone');
        if (iti) iti.setCountry($wire.phone_country_code)

        Livewire.hook('morphed', ({
            component,
            cleanup
        }) => {
            iti = window.initIntlTelInput('phone');
            if (iti) iti.setCountry(component.canonical.phone_country_code)
        })

        Livewire.hook('commit', ({
            component,
            commit,
            respond,
            succeed,
            fail
        }) => {
            // Runs immediately before a commit's payload is sent to the server...
            if ($wire.edit_mode) {
                commit.updates.phone_prefix = iti.getSelectedCountryData().dialCode
                commit.updates.phone_country_code = iti.getSelectedCountryData().iso2
            }
        })
    </script>
@endscript
{{-- Do your work, then step back. --}}
