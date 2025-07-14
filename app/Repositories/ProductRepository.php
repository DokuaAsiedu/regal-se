<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'code',
        'name',
        'cost_price',
        'selling_price',
        'quantity',
        'description',
        'status_id',
    ];

    public function model()
    {
        return Product::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
