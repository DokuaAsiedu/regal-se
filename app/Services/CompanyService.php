<?php

namespace App\Services;

use App\Enums\Status as EnumsStatus;
use App\Exceptions\CustomException;
use App\Models\Company;
use App\Models\Status;
use App\Repositories\CompanyRepository;

class CompanyService
{
    protected $companyRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function find($id)
    {
        return $this->companyRepository->find($id);
    }

    public function all()
    {
        return $this->companyRepository->all();
    }

    public function store($input)
    {
        if (empty($input['code'])) {
            $input['code'] = $this->generateCode($input['code']);
        } else {
            $this->checkIfCodeExists($input['code']);
        }

        $this->checkIfNameExists($input['name']);

        $company = $this->companyRepository->create($input);

        return $company;
    }

    public function update($id, $input)
    {
        if (empty($input['code'])) {
            $input['code'] = $this->generateCode($input['code']);
        } else {
            $this->checkIfCodeExists($input['code'], $id);
        }

        $this->checkIfNameExists($input['name'], $id);

        $company = $this->companyRepository->update($input, $id);

        return $company;
    }

    public function delete($ids)
    {
        $this->companyRepository->delete($ids);
    }

    public function allQuery($search = [])
    {
        return $this->companyRepository->allQuery($search);
    }

    public function validStatuses()
    {
        return Status::whereIn('code', [EnumsStatus::active->value, EnumsStatus::inActive->value])
            ->get();
    }

    public function generateCode($code)
    {
        $last_id = Company::latest('id')->first('id')->id ?? 1;
        do {
            $padded_id = str_pad($last_id, 5, '0', STR_PAD_LEFT);
            $code = "COM-$padded_id";
            $last_id++;
        } while (Company::where('code', $code)->exists());

        return $code;
    }

    public function checkIfCodeExists($code, $id = null)
    {
        $exists = $this->allQuery()
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->where('code', $code)
            ->exists();

        if ($exists) {
            throw new CustomException('Company code already exists');
        }
    }

    public function checkIfNameExists($name, $id = null)
    {
        $exists = $this->allQuery()
            ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->where('name', $name)
            ->exists();

        if ($exists) {
            throw new CustomException('Company name already exists');
        }
    }
}
