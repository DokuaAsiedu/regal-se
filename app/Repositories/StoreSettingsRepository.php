<?php

namespace App\Repositories;

use App\Models\StoreSettings;
use App\Repositories\BaseRepository;

class StoreSettingsRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'name',
        'code',
        'value',
        'repayment_months',
    ];

    public function model()
    {
        return StoreSettings::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
