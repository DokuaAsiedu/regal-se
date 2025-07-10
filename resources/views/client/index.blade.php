<x-layouts.client>
    <div class="grid sm:grid-cols-3 gap-4">
        @foreach ($products as $item)
            <x-client.product-card class="rounded-lg border-2 border-orange-200" :name="$item->name" :price="$item->selling_price" :description="$item->description" :currency="$currency" />
        @endforeach
    </div>
</x-layouts.client>
