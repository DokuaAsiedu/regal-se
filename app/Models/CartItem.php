<?php

namespace App\Models;

use App\Enums\PaymentPlan;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CartItem extends Model
{
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
}
