<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository extends BaseRepository
{
    private $fieldsSearchable = [
        'id',
        'payable_type',
        'payable_id',
        'amount',
        'currency',
        'status_id',
        'payment_channel',
        'reference',
        'transaction_id',
        'payload',
        'due_date',
        'paid_at',
    ];

    public function model()
    {
        return Payment::class;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }
}
