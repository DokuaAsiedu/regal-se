<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
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

    public function model()
    {
        return Order::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
