<?php

namespace App\Services;

use App\Repositories\StoreSettingsRepository;

class StoreSettingsService
{
    protected $storeSettingsRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(StoreSettingsRepository $storeSettingsRepository)
    {
        $this->storeSettingsRepository = $storeSettingsRepository;
    }

    public function find($id)
    {
        return $this->storeSettingsRepository->find($id);
    }

    public function settings()
    {
        return $this->storeSettingsRepository->allQuery();
    }

    public function store($input)
    {
        return $this->storeSettingsRepository->create($input);
    }

    public function update($id, $input)
    {
        return $this->storeSettingsRepository->update($input, $id);
    }

    public function allQuery($search)
    {
        return $this->storeSettingsRepository->allQuery($search);
    }
}
