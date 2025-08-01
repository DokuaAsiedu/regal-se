<?php

use App\Services\StoreSettingsService;

function formatPhone(string $phone, string $prefix)
{
    return $prefix . $phone;
}

function formatDate($date, string $format = 'l jS F, Y, h:i a')
{
    return date($format, strtotime($date));
}

function storeSettings()
{
    return app(StoreSettingsService::class);
}

function currency()
{
    return storeSettings()->currencySymbol();
}

function repaymentMonths()
{
    return storeSettings()->repaymentMonths();
}

function downPaymentPercentage()
{
    return storeSettings()->downPaymentPercentage();
}

function formatCurrency($amount, $currency_code = null)
{
    $currency = $currency_code ?? currency();

    return $currency . ' ' . $amount;
}
