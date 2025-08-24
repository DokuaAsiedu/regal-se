<div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($products as $product)
            <div class="p-4 rounded-2xl border-1 border-gray-300">
                <div class="flex flex-col gap-4">
                    <div class="flex gap-2 overflow-hidden">
                        <x-placeholder-pattern
                            class="flex-grow-1 inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div class="flex flex-col gap-4">
                        <h2 class="text-xl font-bold">{{ $product->name }}</h2>
                    </div>
                    @if ($product->quantity > 0)
                        <div class="flex justify-between items-stretch">
                            <button type="button" class="px-6 py-2 bg-gray-200 rounded-full text-xs" wire:click="addToCart({{ $product->id }})">CASH
                                {{ $this->currency . ' ' . number_format($product->selling_price) }}</button>
                            @php
                                $down_payment_amount = ($this->downPaymentPercentage / 100) * $product->selling_price;
                                $remainder = $product->selling_price - $down_payment_amount;
                                $installment_amount = $remainder / $this->repaymentMonths;
                            @endphp
                            <button type="button" class="px-6 py-2 bg-gray-200 flex flex-col gap-px rounded-full text-xs" wire:click="addToCart({{ $product->id }}, {{ true }})">
                                <span>{{ $this->currency . ' ' . number_format($installment_amount) . '/' . $this->repaymentMonths . ' months' }}</span>
                                <span class="text-tiny">{{ "Plus $this->downPaymentPercentage% down payment" }}</span>
                            </button>
                        </div>
                    @else
                        <span class="self-center px-6 py-2 rounded-md bg-gray-100">{{ __('Out Of Stock') }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="md:col-span-2 lg:col-span-3 flex flex-col items-center gap-4">
                <div class="w-1/2 md:w-1/3 lg:w-1/6">
                    <img src="{{ asset('images/empty-box.png') }}" alt="empty box" class="w-full aspect-auto" />
                </div>
                <flux:heading level="1" size="lg" class="text-center">{{ __('Oops! Nothing here yet. Please check back soon') }}</flux:heading>
            </div>
        @endforelse

    </div>
    {{ $products->links(data: ['scrollTo' => false]) }}
</div>
{{-- Success is as dangerous as failure. --}}
