<?php

namespace App\Repositories;

use App\Models\KYC;

class KYCRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'name',
        'phone_prefix',
        'phone',
        'phone_country_code',
        'email',
        'address',
        'ghana_card_number',
        'date_of_birth',
        'current_position',
        'employment_start_date',
        'staff_id',
        'company_id',
        'status_id',
        'user_id',
        'reviewed_by',
        'rejection_reason',
    ];

    public function model()
    {
        return KYC::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
