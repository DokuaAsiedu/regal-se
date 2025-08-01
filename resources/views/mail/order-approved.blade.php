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

Your order **#{{ $order->code }}** has been approved. Please find your order summary below:

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
                        <flux:text>{{ formatCurrency($elem->unit_price) }}</flux:text>
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

<flux:text style="text-end"><b>{{ __('Total:') }}</b> {{ formatCurrency($sub_total) }}</flux:text>
<flux:text style="text-end"><b>{{ __('Pay Now:') }}</b> {{ $formatted_initial_payment_amount }}</flux:text>

<br />
<flux:text>{{ __('Please use this button to make payment:') }}</flux:text>

<x-mail::button :url="$payment_link" color="primary">
{{ $action_text }}
</x-mail::button>

{{ __('Thank you for choosing us!') }}
<br/>
{{ __('Regards,') }}
<br/>
{{ config('app.name') }}

<x-slot:subcopy>
{{ __("If you're having trouble clicking the \"$action_text\" button, copy and paste the following URL into your web browser:") }} <a :href="$payment_link" class="
break-all" style="cursor: pointer">{{ $payment_link }}</a>
</x-slot:subcopy>

</x-mail::message>

<!-- Very little is needed to make a happy life. - Marcus Aurelius -->
