<div {{ $attributes }}>
    <x-card>
        <div class="flex flex-col">
            <div class="flex gap-2">
                <x-placeholder-pattern class="flex-grow-1 inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                <div>
                    <h2>{{ $name }}</h2>
                    <div>{{ $description }}</div>
                </div>
            </div>
            <div>{{ $currency . ' ' . $price }}</div>
        </div>
    </x-card>
</div>
