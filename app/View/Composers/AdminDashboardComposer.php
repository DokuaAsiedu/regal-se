<?php

namespace App\View\Composers;

use App\Models\KYCSubmission;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\View\View;

class AdminDashboardComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $order_count = Order::count();
        $pending_order_count = Order::query()->pending()->count();
        $orders_today = Order::whereDate('created_at', today())->get();
        $payment_sum = formatCurrency(Payment::sum('amount'));
        $overdue_payments = Payment::overdue()->count();
        $kyc_count = KYCSubmission::count();
        $pending_kyc_count = KYCSubmission::pending()->count();
        $quick_stats = [
            [
                'title' => [
                    'main' => $order_count,
                    'sub' => 'Orders',
                ],
                'sub' => [
                    'main' => $pending_order_count,
                    'sub' => 'pending',
                ]
            ],
            [
                'title' => [
                    'main' => $payment_sum,
                    'sub' => 'Payments',
                ],
                'sub' => [
                    'main' => $overdue_payments,
                    'sub' => 'overdue',
                ]
            ],
            [
                'title' => [
                    'main' => $kyc_count,
                    'sub' => 'KYC Submissions',
                ],
                'sub' => [
                    'main' => $pending_kyc_count,
                    'sub' => 'pending',
                ]
            ],
        ];

        $orders_last_30_days = $this->getDataFromGivenPeriod(Order::class);
        $payments_last_30_days = $this->getDataFromGivenPeriod(Payment::class);
        $kycs_last_30_days = $this->getDataFromGivenPeriod(KYCSubmission::class);
        $users_last_30_days = $this->getDataFromGivenPeriod(User::class);
        $chart_data = [
            [
                'chart_cont_id' => 'orders-chart-cont',
                'header' => __('Orders'),
                'data' => $orders_last_30_days,
            ],
            [
                'chart_cont_id' => 'kycs-chart-cont',
                'header' => __('KYC Submissions'),
                'data' => $kycs_last_30_days,
            ],
            [
                'chart_cont_id' => 'payments-chart-cont',
                'header' => __('Payments'),
                'data' => $payments_last_30_days,
            ],
            [
                'chart_cont_id' => 'users-chart-cont',
                'header' => __('Users'),
                'data' => $users_last_30_days,
            ],
        ];

        $data = [
            'quick_stats' => $quick_stats,
            'orders_today' => $orders_today,
            'chart_data' => $chart_data,
        ];
        $view->with($data);
    }

    public function getDataFromGivenPeriod($model, $period = 30)
    {
        $data_over_given_period = $model::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // If no data, just return an array with 0 totals for each day in the period
        if ($data_over_given_period->isEmpty()) {
            $start_date = Carbon::now()->subDays($period)->startOfDay();
            $end_date   = Carbon::now()->endOfDay();
        } else {
            $start_date = $data_over_given_period->min(fn ($elem) => Carbon::parse($elem['date']));
            $end_date   = $data_over_given_period->max(fn ($elem) => Carbon::parse($elem['date']));
        }

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start_date, $interval, $end_date->copy()->addDay());

        $res = [];
        foreach ($period as $date) {
            $formatted_date = $date->format('Y-m-d');
            $record = $data_over_given_period->firstWhere('date', $formatted_date);
            $res[] = [
                'date' => $formatted_date,
                'total' => $record ? $record->total : 0,
            ];
        }

        return $res;
    }
}