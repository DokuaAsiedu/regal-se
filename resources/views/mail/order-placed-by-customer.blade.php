<x-mail::message>
<style>
    .main-table thead tr, .main-table tbody tr {
        border-bottom: 1px solid gray;
    }

    .main-table tbody td {
        padding: 0.5rem 0;
    }
</style>
# Hello {{ $order->customer_name ?? 'there' }},

We have received your order **#{{ $order->code }}** and it is being processed. We will get back in touch with you on the next steps. In the mean time, please find your order summary below:

<table class="main-table" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th align="left">{{ __('Item') }}</th>
            <th align="right">{{ __('Quantity') }}</th>
            <th align="right">{{ __('Unit Price') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->orderItems as $elem)
            <tr style="padding: 0.5rem 0;">
                <td>{{ $elem->product_name ?? $elem->product->name ?? 'N/A' }}</td>
                <td align="right">{{ $elem->quantity }}</td>
                <td align="right">
                    <div>
                        <flux:text>{{ formatCurrency($elem->unit_price, 2) }}</flux:text>
                        @if (isset($elem->down_payment_amount))
                            <flux:text style="font-size: 0.75rem;">{{ __('Down payment') }}: {{ formatCurrency($elem->down_payment_amount) }} ({{ $elem->down_payment_percentage }}%)</flux:text>

                            <flux:text style="font-size: 0.75rem;">{{ __('Installment Amount') }}: {{ formatCurrency($elem->installment_amount) }}</flux:text>

                            <flux:text style="font-size: 0.75rem;">{{ __('Installment period') }}: {{ $elem->installment_months }} {{ __('months') }}</flux:text>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<br/>

<div style="display:flex; flex-direction: column; align-items: end">
    <flux:text><b>{{ __('Sub Total:') }}</b> {{ formatCurrency($sub_total) }}</flux:text>
</div>

<br/>
Thank you for choosing us!

<br/>
<br/>
Regards,
<br/>
{{ config('app.name') }}
</x-mail::message>
<!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
