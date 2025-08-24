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
            <label for="cost_price">{{ __('Cost Price') }}<x-required /></label>
            <flux:input type="number" step="0.01" id="cost_price" wire:model="cost_price" />
            <flux:error name="cost_price" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="selling_price">{{ __('Selling Price') }}</label>
            <flux:input type="number" step="0.01" id="selling_price" wire:model="selling_price" />
            <flux:error name="selling_price" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="quantity">{{ __('Quantity') }}<x-required /></label>
            <flux:input type="number" id="quantity" wire:model="quantity" />
            <flux:error name="quantity" />
        </div>

        <div class="flex flex-col gap-2">
            <label for="description">{{ __('Description') }}</label>
            <flux:input type="text" id="description" wire:model="description" />
            <flux:error name="description" />
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

        <div class="flex flex-col gap-2">
            <label for="available-categories">{{ __('Available Categories') }}</label>
            <flux:select id="available-categories" wire:model="selected_category_id" wire:change="addCategory">
                <flux:select.option value="" disabled>{{ __('Select a category') }}</flux:select.option>
                @foreach ($available_categories as $item)
                    <flux:select.option value="{{ $item['id'] }}">{{ $item['name'] }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="flex flex-col gap-6">
            <flux:heading>{{ __('Categories') }}</flux:heading>
            <div class="flex gap-4">
                @forelse($product_categories as $item)
                    <flux:badge variant="pill" color="emerald" class="flex items-center gap-2">
                        <span>{{ $item['name'] }}</span>
                        <flux:icon.x-circle class="cursor-pointer text-red-500" wire:click="removeCategory({{ $item['id'] }})" />
                    </flux:badge>
                @empty
                    <flux:badge>{{ ('No categories assigned') }}</flux:badge>
                @endforelse
            </div>
        </div>

        <x-button :name="__('Save')" type="submit" class="lg:col-span-2" />
    </form>
</div>
