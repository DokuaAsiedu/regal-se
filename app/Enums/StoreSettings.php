<?php

namespace App\Enums;

enum StoreSettings: string
{
    case repayment_months = 'repayment_months';
    case currency_symbol = 'currency_symbol';
    case currency_code = 'currency_code';
    case currency_name = 'currency_name';
    case auto_approve_kyc = 'auto_approve_kyc';
}
