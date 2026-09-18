<?php

namespace App\Enums\Utilities;

enum CurrencyType: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case MAD = 'MAD';

    public function getLabel(): string
    {
        return match ($this) {
            self::USD => 'US Dollar',
            self::EUR => 'Euro',
            self::MAD => 'Moroccan Dirham',
        };
    }

    public function getSymbol(): string
    {
        return match ($this) {
            self::USD => '$',
            self::EUR => '€',
            self::MAD => 'DH',
        };
    }

    public function getCode(): string
    {
        return $this->value; // Since value is already the code
    }

    public function format(int|float $amount): string
    {
        return match ($this) {
            self::USD => '$' . number_format($amount, 2),
            self::EUR => '€' . number_format($amount, 2),
            self::MAD => number_format($amount, 2) . ' DH',
        };
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
}