<div class="flex flex-col gap-2">
    <flux:heading level="1" size="lg">{{ __('Transaction Payload') }}</flux:heading>
    @if (isset($data) && !empty($data))
        @php
            $decoded = json_decode($data, true);
        @endphp
        <pre class="bg-gray-100 text-sm p-4 overflow-x-auto whitespace-pre-wrap max-h-96">{!! json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}</pre>
    @else
        <flux:text>{{ __('Payload not available') }}</flux:text>
    @endif
</div>
<!-- The only way to do great work is to love what you do. - Steve Jobs -->