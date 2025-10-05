<div class="flex flex-col gap-6">
    <flux:heading size="xl">{{ __('KYC') }}</flux:heading>

    <div class="flex justify-between gap-2">
        <div class="flex items-center gap-4">
            <x-status :status="$kyc->status" />
            <div class="flex items-center gap-4">
                <flux:icon.calendar size="2" />
                <flux:text>{{ formatDate($kyc->created_at) }}</flux:text>
            </div>
        </div>
    </div>
    <div class="grid lg:grid-cols-2 gap-6">
        <div class="col-span-1 lg:col-span-2 flex flex-col gap-2">
            <flux:heading level="2" size="xl" class="">{{ __('Personal Details') }}
            </flux:heading>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Full Name') }}</flux:heading>
            <flux:text>{{ $kyc->name }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Phone Number') }}</flux:heading>
            <flux:text>{{ $phone }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Address') }}</flux:heading>
            <flux:text>{{ $kyc->address }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Ghana Card Number') }}</flux:heading>
            <flux:text>{{ $kyc->ghana_card_number }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Date Of Birth') }}</flux:heading>
            <flux:text>{{ $date_of_birth }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Email') }}</flux:heading>
            <flux:text>{{ $kyc->email }}</flux:text>
        </div>

        <div class="col-span-1 lg:col-span-2 mt-8 flex flex-col gap-2">
            <flux:heading level="2" size="xl" class="">{{ __('Employment Details') }}
            </flux:heading>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Company') }}</flux:heading>
            <flux:text>{{ $company->name ?? "N/A" }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Current Position') }}</flux:heading>
            <flux:text>{{ $kyc->current_position }}</flux:text>
        </div>

        <div class="flex flex-col gap-2">
            <flux:heading level="4" size="lg">{{ __('Employment Start Date') }}</flux:heading>
            <flux:text>{{ $kyc->employment_start_date }}</flux:text>
        </div>
    </div>
    <div class="flex items-center justify-between gap-6">
        @if ($is_pending)
            <flux:button variant="primary" color="red" class="grow" wire:click="showRejectionModal({{ $kyc->id }})">{{ __('Reject') }}</flux:button>
            <flux:button variant="primary" color="green" class="grow" wire:click="approve">{{ __('Approve') }}</flux:button>
        @endif
    </div>
</div>
{{-- The Master doesn't talk, he acts. --}}
