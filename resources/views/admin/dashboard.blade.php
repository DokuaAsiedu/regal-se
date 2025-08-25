<x-layouts.admin :title="__('Dashboard')">
    <div class="h-full lg:max-h-screen flex flex-col gap-4">
        <div class="grid auto-rows-min gap-4 sm:grid-cols-2 md:grid-cols-3">
            @foreach ($quick_stats as $elem)
                <div class="p-6 grid border-zinc-200 border-1 rounded-md shadow-lg">
                    <div class="flex flex-col gap-2">
                        <flux:text size="sm">{{ $elem['title']['sub'] }}</flux:text>
                        <h1 class="text-xl">{{ $elem['title']['main'] }}</h1>
                        <flux:text size="sm">
                            <span>{{ $elem['sub']['main'] }} </span>
                            <span>{{ $elem['sub']['sub'] }}</span>
                        </flux:text>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="grow grid lg:grid-cols-3 gap-4 overflow-hidden">
            <div
                class="h-full max-lg:max-h-screen flex flex-col border-1 border-zinc-200 rounded-md shadow-lg overflow-hidden">
                <div class="p-4 border-b-2 border-b-zinc-200">
                    <flux:heading level="2" size="lg">{{ __('Orders today') }}</flux:heading>
                </div>
                <div class="grow overflow-auto">
                    @forelse ($orders_today as $order)
                        <div class="p-4 flex gap-2 border-b-2 border-b-zinc-200">
                            <flux:text>{{ $loop->iteration . '.' }}</flux:text>
                            <div class="grow flex flex-col gap-2">
                                <flux:heading level="3" size="base">{{ $order->code }}</flux:heading>
                                <div class="flex flex-wrap gap-2">
                                    <x-status :status="$order->status" />
                                    <flux:badge color="purple">
                                        {{ $order->orderItems->count() . ' ' . Str::plural(__('item'), $order->orderItems->count()) }}
                                    </flux:badge>
                                </div>
                            </div>
                            <div class="flex flex-col justify-center">
                                <flux:button icon="eye" variant="filled"
                                    :href="route('orders.show', ['order' => $order->id])">{{ __('View') }}
                                </flux:button>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center">
                            {{ __('No orders today...') }}
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="lg:col-span-2 p-4 flex flex-col gap-3">
                <flux:heading level="2" size="lg">{{ __('Stats over the last 30 days') }}</flux:heading>
                <div class="h-full grid grid-cols-2 gap-4">
                    @foreach ($chart_data as $elem)
                        <div class="p-4 flex flex-col gap-2 border-2 border-zinc-200 rounded-lg shadow-lg">
                            <flux:heading level="4">{{ $elem['header'] }}</flux:heading>
                            <div class="w-full grow">
                                <canvas id="{{ $elem['chart_cont_id'] }}"></canvas>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        function loadCharts() {
            const charts = @json($chart_data);

            charts.forEach(chart => {
                const ctx = document.getElementById(chart.chart_cont_id);

                new window.Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chart.data.map(item => item.date),
                        datasets: [{
                            label: chart.header,
                            data: chart.data.map(item => item.total),
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                            fill: false,
                            tension: 0.3,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointRadius: 1,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                        },
                        scales: {
                            x: {
                                // display: false,
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    display: false
                                },
                            },
                            y: {
                                grid: {
                                    display: false
                                },
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                            },
                        }
                    },
                });
            });
        }
        window.addEventListener("DOMContentLoaded", function() {
            loadCharts()
        })
    </script>
</x-layouts.admin>
