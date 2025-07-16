<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'code',
        'name',
        'description',
        'status_id',
    ];

    public function model()
    {
        return Category::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
