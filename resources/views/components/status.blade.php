<div>
    @php
        $code = $status->code ?? null;
        $color = match ($code) {
            'active' => 'lime',
            'success' => 'emerald',
            'inactive' => 'red',
            'pending' => 'sky',
            'approved' => 'emerald',
            'completed' => 'green',
            'declined' => 'red',
            'rejected' => 'rose',
            'failed' => 'red',
            'suspended' => 'zinc',
            'abandoned' => 'amber',
            'cancelled' => 'pink',
            'invalid' => 'violet',
            'unknown' => 'amber',
            default => 'teal'
        };
        $badge = match ($code) {
            'active' => 'pill',
            'success' => 'solid',
            'inactive' => 'pill',
            'pending' => 'pill',
            'approved' => 'solid',
            'completed' => 'solid',
            'declined' => 'solid',
            'rejected' => 'solid',
            'failed' => 'solid',
            'suspended' => 'pill',
            'abandoned' => 'solid',
            'cancelled' => 'pill',
            'invalid' => 'solid',
            'unknown' => 'pill',
            default => 'pill'
        };
        $value = $status->name ?? 'N/A';
    @endphp
    <flux:badge variant="{{ $badge }}" color="{{ $color }}">{{ $value }}</flux:badge>
</div>
<!-- We must ship. - Taylor Otwell -->
