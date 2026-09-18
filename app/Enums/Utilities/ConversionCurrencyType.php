<?php

namespace App\Enums\Utilities;

enum ConversionCurrencyType: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case MAD = 'MAD';
    case GBP = 'GBP';
    case JPY = 'JPY';

    public function getLabel(): string
    {
        return match ($this) {
            self::USD => 'US Dollar',
            self::EUR => 'Euro',
            self::MAD => 'Moroccan Dirham',
            self::GBP => 'British Pound',
            self::JPY => 'Japanese Yen',
        };
    }

    public function getSymbol(): string
    {
        return match ($this) {
            self::USD => '$',
            self::EUR => '€',
            self::MAD => 'DH',
            self::GBP => '£',
            self::JPY => '¥',
        };
    }


    /**
     * Check if currency is supported by Frankfurter API
     */
    public function isSupportedByFrankfurter(): bool
    {
        return match ($this) {
            self::USD, self::EUR, self::GBP, self::JPY => true,
            self::MAD => false,
        };
    }

    /**
     * Get all currencies supported by Frankfurter
     */
    public static function frankfurterSupported(): array
    {
        return array_filter(
            self::cases(),
            fn(self $currency) => $currency->isSupportedByFrankfurter()
        );
    }

    public function getCode(): string
    {
        return $this->value;
    }

    /**
     * Get decimal places for currency (some currencies don't use decimals)
     */
    public function getDecimals(): int
    {
        return match ($this) {
            self::JPY => 0,
            default => 2,
        };
    }

    /**
     * Get the smallest unit divisor (e.g., 100 for cents, 1 for yen)
     */
    public function getDivisor(): int
    {
        return match ($this) {
            self::JPY => 1,
            default => 100,
        };
    }

    /**
     * Optimized Format Method
     */
    public function format(int|float $amount, bool $showCode = false): string
    {
        $value = $amount / $this->getDivisor();
        $formatted = number_format($value, $this->getDecimals(), '.', ',');

        return match ($this) {
            // Symbol FIRST: $1,234.56
            self::USD, self::GBP, self::EUR =>
            $this->getSymbol() . $formatted . ($showCode ? ' ' . $this->value : ''),

            // Symbol LAST: 1,234.56 DH or 1,234 ¥
            self::MAD, self::JPY =>
            $formatted . ' ' . $this->getSymbol() . ($showCode ? ' (' . $this->value . ')' : ''),
        };
    }
    /**
     * Enhanced Localized Formatting
     * Handles the Moroccan Dirham Arabic vs Latin script automatically
     */
    public function formatLocalized(int|float $amount, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $value = $amount / $this->getDivisor();

        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);

        // Ensure MAD displays as "MAD" or "DH" instead of "د.م." if desired
        return $formatter->formatCurrency($value, $this->value);
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $type) => [
                $type->value => $type->getLabel(),
            ])
            ->toArray();
    }

    public static function fromSymbol(string $symbol): ?self
    {
        return collect(self::cases())
            ->first(fn(self $type) => $type->getSymbol() === $symbol);
    }

    /**
     * Get all currency codes
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }
}