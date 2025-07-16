<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'code',
        'name',
        'status_id',
    ];

    public function model()
    {
        return Role::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
