<?php

namespace App\Services;

use App\Repositories\TransactionRepository;

class TransactionService
{
    protected $transactionRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function find($id)
    {
        return $this->transactionRepository->find($id);
    }

    public function all()
    {
        return $this->transactionRepository->all();
    }

    public function allQuery($search = [])
    {
        return $this->transactionRepository->allQuery($search);
    }

    public function store($input)
    {
        return $this->transactionRepository->create($input);
    }

    public function update($id, $input)
    {
        return $this->transactionRepository->update($input, $id);
    }

    public function delete($ids)
    {
        $this->transactionRepository->delete($ids);
    }
}
