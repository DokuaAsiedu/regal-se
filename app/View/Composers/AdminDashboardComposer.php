<?php

namespace App\View\Composers;

use App\Models\KYCSubmission;
use App\Models\Order;
use App\Models\Payment;
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

        $data = [
            'quick_stats' => $quick_stats,
            'orders_today' => $orders_today,
        ];
        $view->with($data);
    }
}