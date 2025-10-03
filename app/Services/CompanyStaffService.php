<?php

namespace App\Services;

use App\Enums\Status as EnumsStatus;
use App\Models\Status;
use App\Repositories\CompanyStaffRepository;

class CompanyStaffService
{
    protected $companyStaffRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(CompanyStaffRepository $companyStaffRepository)
    {
        $this->companyStaffRepository = $companyStaffRepository;
    }

    public function find($id)
    {
        return $this->companyStaffRepository->find($id);
    }

    public function all()
    {
        return $this->companyStaffRepository->all();
    }

    public function store($input)
    {
        $companyStaff = $this->companyStaffRepository->create($input);

        return $companyStaff;
    }

    public function update($id, $input)
    {
        $companyStaff = $this->companyStaffRepository->update($input, $id);

        return $companyStaff;
    }

    public function delete($ids)
    {
        $this->companyStaffRepository->delete($ids);
    }

    public function allQuery($search = [])
    {
        return $this->companyStaffRepository->allQuery($search);
    }

    public function validStatuses()
    {
        $status_codes = [
            EnumsStatus::active->value,
            EnumsStatus::inActive->value,
            EnumsStatus::pending->value,
        ];

        return Status::whereIn('code', $status_codes)
            ->get();
    }
}
