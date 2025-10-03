<?php

namespace App\Repositories;

use App\Models\CompanyStaff;

class CompanyStaffRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'staff_id',
        'ghana_card_number',
        'company_id',
        'user_id',
    ];

    public function model()
    {
        return CompanyStaff::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
