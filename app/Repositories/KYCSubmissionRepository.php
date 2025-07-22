<?php

namespace App\Repositories;

use App\Models\KYCSubmission;

class KYCSubmissionRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'customer_name',
        'customer_phone_prefix',
        'customer_phone',
        'customer_phone_country_code',
        'customer_email',
        'customer_address',
        'customer_ghana_card_number',
        'customer_date_of_birth',
        'company_name',
        'customer_current_position',
        'company_phone_prefix',
        'company_phone',
        'company_phone_country_code',
        'company_address',
        'company_email',
        'customer_employment_start_date',
        'status_id',
        'user_id'
    ];

    public function model()
    {
        return KYCSubmission::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
