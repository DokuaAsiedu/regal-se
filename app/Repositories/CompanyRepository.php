<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'code',
        'name',
        'email',
        'address',
        'phone_prefix',
        'phone',
        'phone_country_code',
        'status_id',
    ];

    public function model()
    {
        return Company::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
