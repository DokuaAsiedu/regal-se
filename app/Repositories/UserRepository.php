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
        'role_id',
        'delivery_address',
        'delivery_address_landmark',
        'ghana_card_number',
        'date_of_birth',
        'company_name',
        'company_email',
        'company_phone_prefix',
        'company_phone',
        'company_phone_country_code',
        'company_address',
        'current_position',
        'employment_start_date'
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
