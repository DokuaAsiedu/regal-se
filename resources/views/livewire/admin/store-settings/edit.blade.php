<div class="flex flex-col gap-5">
    <flux:heading level="1" size="xl">{{ __('Update Store Settings') }}</flux:heading>

    <form wire:submit="save" class="grid lg:grid-cols-2 gap-6">
        <div class="flex flex-col gap-2">
            <label for="currency_name">{{ __('Currency Name') }} <x-required /></label>
            <flux:input type="text" id="currency_name" wire:model="currency_name" />
            <flux:error name="currency_name" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="currency_code">{{ __('Currency Code') }} <x-required /></label>
            <flux:input type="text" id="currency_code" wire:model="currency_code" />
            <flux:error name="currency_code" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="currency_symbol">{{ __('Currency Symbol') }} <x-required /></label>
            <flux:input type="text" id="currency_symbol" wire:model="currency_symbol" />
            <flux:error name="currency_symbol" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="repayment_months">{{ __('Repayment Months') }} <x-required /></label>
            <flux:input type="number" id="repayment_months" wire:model="repayment_months" />
            <flux:error name="repayment_months" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="down_payment_percentage">{{ __('Down Payment Percentage') }} <x-required /></label>
            <flux:input type="number" id="down_payment_percentage" wire:model="down_payment_percentage" step="0.1" />
            <flux:error name="down_payment_percentage" />
        </div>

        <x-button :name="__('Save')" type="submit" class="lg:col-span-2" />
    </form>
</div>
{{-- Close your eyes. Count to one. That is how long forever feels. --}}
