<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'payment_id',
        'amount',
        'currency',
        'authorization_url',
        'reference',
        'gateway',
        'status_id',
        'channel',
        'transaction_id',
        'payload',
        'paid_at',
        'processed_at',
    ];

    public function model()
    {
        return Transaction::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
