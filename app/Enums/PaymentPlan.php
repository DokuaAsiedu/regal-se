<?php

namespace App\Enums;

enum PaymentPlan: string
{
    case Once = 'once';
    case Installment = 'installment';
}
