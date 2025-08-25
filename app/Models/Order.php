<?php

namespace App\Models;

use App\Enums\Status as StatusEnum;
use App\Traits\HasStatusScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use LogsActivity, HasStatusScopes;

    protected $fillable = [
        'code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'delivery_address',
        'landmark',
        'delivery_note',
        'total_amount',
        'user_id',
        'status_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
