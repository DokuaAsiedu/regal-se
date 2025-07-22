<div class="flex flex-col gap-6">
    @if (!$edit_mode)
        <div wire:submit="save" class="grid lg:grid-cols-2 gap-6">
            <div class="col-span-1 lg:col-span-2 flex flex-col gap-2">
                <flux:heading level="2" size="xl" class="">{{ __('Personal Details') }}
                </flux:heading>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Full Name') }}</flux:heading>
                <flux:text>{{ $customer_name }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Phone Number') }}</flux:heading>
                <flux:text>{{ $customer_phone_prefix . $customer_phone }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Address') }}</flux:heading>
                <flux:text>{{ $customer_address }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Ghana Card Number') }}</flux:heading>
                <flux:text>{{ $customer_ghana_card_number }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Date Of Birth') }}</flux:heading>
                <flux:text>{{ $customer_date_of_birth }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Email') }}</flux:heading>
                <flux:text>{{ $customer_email }}</flux:text>
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
                <flux:heading level="4" size="lg">{{ __('Current Position') }}</flux:heading>
                <flux:text>{{ $customer_current_position }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Company Contact Number') }}</flux:heading>
                <flux:text>{{ $company_phone_prefix . $company_phone }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Company Address') }}</flux:heading>
                <flux:text>{{ $company_address }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Company Email') }}</flux:heading>
                <flux:text>{{ $company_email }}</flux:text>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading level="4" size="lg">{{ __('Employment Start Date') }}</flux:heading>
                <flux:text>{{ $customer_employment_start_date }}</flux:text>
            </div>
        </div>
        @if (!$kyc_submission_approved)
            <div class="self-end">
                <x-button :name="__('Edit')" type="button" variant="primary" wire:click="edit" />
            </div>
        @endif
    @else
        <flux:heading level="1" size="xl" class="text-center">{{ $header }}</flux:heading>

        <form wire:submit="save" class="grid lg:grid-cols-2 gap-6">
            <div class="flex flex-col gap-2">
                <label for="customer_name">{{ __('Full Name') }} <x-required /></label>
                <flux:input type="text" id="customer_name" wire:model="customer_name" />
                <flux:error name="customer_name" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="customer_phone">{{ __('Phone Number') }} <x-required /></label>
                <flux:input type="text" id="customer_phone" wire:model="customer_phone" />
                <flux:error name="customer_phone" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="customer_address">{{ __('Address') }} <x-required /></label>
                <flux:input type="text" id="customer_address" wire:model="customer_address" />
                <flux:error name="customer_address" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="customer_ghana_card_number">{{ __('Ghana Card Number') }} <x-required /></label>
                <flux:input type="text" id="customer_ghana_card_number" wire:model="customer_ghana_card_number" />
                <flux:error name="customer_ghana_card_number" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="customer_date_of_birth">{{ __('Date Of Birth') }} <x-required /></label>
                <input type="date" id="customer_date_of_birth" class="p-2 border rounded-lg"
                    wire:model="customer_date_of_birth" />
                <flux:error name="customer_date_of_birth" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="customer_email">{{ __('Email') }} <x-required /></label>
                <flux:input type="text" id="customer_email" wire:model="customer_email" />
                <flux:error name="customer_email" />
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
                <label for="company_name">{{ __('Company Name') }} <x-required /></label>
                <flux:input type="text" id="company_name" wire:model="company_name" />
                <flux:error name="company_name" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="customer_current_position">{{ __('Current Position') }} <x-required /></label>
                <flux:input type="text" id="customer_current_position" wire:model="customer_current_position" />
                <flux:error name="customer_current_position" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="company_phone">{{ __('Company Contact Number') }} <x-required /></label>
                <flux:input type="text" id="company_phone" wire:model="company_phone" />
                <flux:error name="company_phone" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="company_address">{{ __('Company Address') }} <x-required /></label>
                <flux:input type="text" id="company_address" wire:model="company_address" />
                <flux:error name="company_address" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="company_email">{{ __('Company Email') }} <x-required /></label>
                <flux:input type="text" id="company_email" wire:model="company_email" />
                <flux:error name="company_email" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="customer_employment_start_date">{{ __('Employment Start Date') }} <x-required /></label>
                <input type="date" id="customer_employment_start_date" class="p-2 border rounded-lg"
                    wire:model="customer_employment_start_date" />
                <flux:error name="customer_employment_start_date" />
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
        let iti = window.initIntlTelInput('customer_phone');
        let companyIti = window.initIntlTelInput('company_phone');
        if (iti) iti.setCountry($wire.customer_phone_country_code)
        if (companyIti) companyIti.setCountry($wire.company_phone_country_code)

        Livewire.hook('morphed', ({
            component,
            cleanup
        }) => {
            iti = window.initIntlTelInput('customer_phone');
            companyIti = window.initIntlTelInput('company_phone');
            if (iti) iti.setCountry(component.canonical.customer_phone_country_code)
            if (companyIti) companyIti.setCountry(component.canonical.company_phone_country_code)
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
                commit.updates.customer_phone_prefix = iti.getSelectedCountryData().dialCode
                commit.updates.customer_phone_country_code = iti.getSelectedCountryData().iso2
                commit.updates.company_phone_prefix = companyIti.getSelectedCountryData().dialCode
                commit.updates.company_phone_country_code = companyIti.getSelectedCountryData().iso2
            }
        })
    </script>
@endscript
{{-- Do your work, then step back. --}}
