<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'payable_type',
        'payable_id',
        'amount',
        'currency',
        'status_id',
        'payment_channel',
        'reference',
        'transaction_id',
        'payload',
        'due_date',
        'paid_at',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
