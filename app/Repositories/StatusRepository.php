<?php

namespace App\Repositories;

use App\Models\Status;

class StatusRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'name',
        'code',
    ];

    public function model()
    {
        return Status::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
