<?php

namespace App\Models;

use App\Enums\PaymentPlan;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OrderItem extends Model
{
    use LogsActivity;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'payment_plan',
        'down_payment_percentage',
        'down_payment_amount',
        'installment_months',
        'installment_amount',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'payment_plan' => PaymentPlan::class
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
}
