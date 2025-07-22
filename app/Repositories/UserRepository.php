<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'name',
        'email',
        'phone_prefix',
        'phone',
        'phone_country_code',
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
