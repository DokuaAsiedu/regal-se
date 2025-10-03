<div>
    <form wire:submit="save" class="grid lg:grid-cols-2 gap-6">
        <div class="flex flex-col gap-2">
            <label for="company_id">{{ __('Company') }}<x-required /></label>
            <flux:select id="company_id" wire:model.live="company_id">
                <flux:select.option value="">Select an option...</flux:select.option>
                @foreach ($this->companies as $item)
                    <flux:select.option value="{{ $item->id }}">{{ $item->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="company_id" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="staff_id">{{ __('Staff Id') }}<x-required /></label>
            <flux:input type="text" id="staff_id" wire:model="staff_id" />
            <flux:error name="staff_id" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="ghana_card_number">{{ __('Ghana Card Number') }}<x-required /></label>
            <flux:input type="text" id="ghana_card_number" wire:model="ghana_card_number" />
            <flux:error name="ghana_card_number" />
        </div>

        <x-button :name="__('Save')" type="submit" class="lg:col-span-2" />
    </form>
</div>
{{-- The Master doesn't talk, he acts. --}}
