<?php

namespace App\Repositories;

use App\Models\OrderItem;

class OrderItemRepository extends BaseRepository
{
   private $fieldsSearchable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'payment_plan',
        'down_payment_amount',
        'installment_months',
        'installment_amount',
    ];

    public function model()
    {
        return OrderItem::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
