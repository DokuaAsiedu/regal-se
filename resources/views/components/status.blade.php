<div>
    @php
        $code = $status->code ?? null;
        $color = match ($code) {
            'active' => 'lime',
            'inactive' => 'red',
            'pending' => 'sky',
            'approved' => 'emerald',
            'completed' => 'green',
            'declined' => 'red',
            'rejected' => 'rose',
            'suspended' => 'zinc',
            default => 'teal'
        };
        $badge = match ($code) {
            'active' => 'pill',
            'inactive' => 'pill',
            'pending' => 'pill',
            'approved' => 'solid',
            'completed' => 'solid',
            'declined' => 'solid',
            'rejected' => 'solid',
            'suspended' => 'pill',
            default => 'pill'
        };
        $value = $status->name ?? 'N/A';
    @endphp
    <flux:badge variant="{{ $badge }}" color="{{ $color }}">{{ $value }}</flux:badge>
</div>
<!-- We must ship. - Taylor Otwell -->
