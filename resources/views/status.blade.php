<div>
    @php
        $color = match ($status->code) {
            'active' => 'lime',
            'inactive' => 'red',
            'pending' => 'sky',
            'approved' => 'emerald',
            'completed' => 'green',
            'declined' => 'red',
            'suspended' => 'zinc',
            default => 'teal'
        };
        $badge = match ($status->code) {
            'active' => 'pill',
            'inactive' => 'pill',
            'pending' => 'solid',
            'approved' => 'solid',
            'completed' => 'solid',
            'declined' => 'solid',
            'suspended' => 'pill',
            default => 'pill'
        };
        $value = $status->name ?? 'N/A';
    @endphp
    <flux:badge variant="{{ $badge }}" color="{{ $color }}">{{ $value }}</flux:badge>
</div>
<!-- We must ship. - Taylor Otwell -->
