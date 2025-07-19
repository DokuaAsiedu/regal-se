<?php

namespace App\Models;

use App\Enums\PaymentPlan;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
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
}
