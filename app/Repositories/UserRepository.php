<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'name',
        'email',
        'status_id',
    ];

    public function model()
    {
        return User::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
