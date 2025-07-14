<?php

namespace App\Repositories;

use App\Models\CartItem;

class CartItemRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'user_id',
        'product_id',
        'quantity',
        'payment_plan'
    ];

    public function model()
    {
        return CartItem::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
