<?php

namespace App\Models;

use App\Enums\PaymentPlan;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'guest_id',
        'product_id',
        'quantity',
        'payment_plan',
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
