<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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
}
