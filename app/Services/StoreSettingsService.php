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

    public function downPaymentPercentage()
    {
        return $this->allQuery(['code' => 'down_payment_percentage'])
            ->first()
            ->value;
    }

    public function repaymentMonths()
    {
        return $this->allQuery(['code' => 'repayment_months'])
            ->first()
            ->value;
    }

    public function currencySymbol()
    {
        return $this->allQuery(['code' => 'currency_symbol'])
            ->first()
            ->value;
    }
}
