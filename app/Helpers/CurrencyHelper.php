<?php

//namespace App\Helpers;

use App\Enums\Utilities\ConversionCurrencyType;
use App\Services\Currency\CurrencyConversionService;
use App\ValueObjects\Money;

if (!function_exists('currency')) {
    /**
     * Get currency conversion service instance
     */
    function currency(): CurrencyConversionService
    {
        return app(CurrencyConversionService::class);
    }
}

if (!function_exists('format_price')) {
    /**
     * Format a price with currency
     */
    function format_price(int|float $amount, ConversionCurrencyType|string $currency, bool $showCode = false): string
    {
        $currency = is_string($currency) ? ConversionCurrencyType::from($currency) : $currency;
        return $currency->format($amount, $showCode);
    }
}

if (!function_exists('convert_currency')) {
    /**
     * Convert amount from one currency to another
     */
    function convert_currency(
        int|float $amount,
        ConversionCurrencyType|string $from,
        ConversionCurrencyType|string $to
    ): float {
        $from = is_string($from) ? ConversionCurrencyType::from($from) : $from;
        $to = is_string($to) ? ConversionCurrencyType::from($to) : $to;

        return currency()->convert($amount, $from, $to);
    }
}

if (!function_exists('money')) {
    /**
     * Create a money object for easier calculations
     */
    function money(int|float $amount, ConversionCurrencyType|string $currency): Money
    {
        $currency = is_string($currency) ? ConversionCurrencyType::from($currency) : $currency;
        return new Money($amount, $currency);
    }
}