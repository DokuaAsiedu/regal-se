<div class="flex flex-col gap-5">
    <flux:heading level="1" size="xl">{{ $header }}</flux:heading>

    <form wire:submit="save" class="grid lg:grid-cols-2 gap-6">
        <div class="flex flex-col gap-2">
            <label for="name">{{ __('Name') }}<x-required /></label>
            <flux:input type="text" id="name" wire:model="name" />
            <flux:error name="name" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="email">{{ __('Email') }}</label>
            <flux:input type="text" id="email" wire:model="email" />
            <flux:error name="email" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="password">{{ __('Password') }}</label>
            <flux:input type="password" id="password" wire:model="password" viewable />
            <flux:error name="password" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="password_confirmation">{{ __('Confirm password') }}</label>
            <flux:input id="password_confirmation" wire:model="password_confirmation" type="password" viewable />
            <flux:error name="password_confirmation" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="status">{{ __('Status') }}<x-required /></label>
            <flux:select id="status" wire:model="status">
                <flux:select.option value="" disabled>{{ __('Select an option...') }}</flux:select.option>
                @foreach ($this->validStatuses as $item)
                    <flux:select.option value="{{ $item->id }}">{{ $item->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="status" />
        </div>

        <x-button :name="__('Save')" type="submit" class="lg:col-span-2" />
    </form>
</div>
{{-- Care about people's approval and you will be their prisoner. --}}
