<?php

namespace App\Enums;

enum PaymentPlan: string
{
    case Once = 'one-time';
    case Installment = 'installment';
}
