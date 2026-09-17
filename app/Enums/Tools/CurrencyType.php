<?php

namespace App\Enums\Tools;

enum CurrencyType: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case MAD = 'MAD';

    public function getName(): string
    {
        return match ($this) {

            self::USD => 'Dollar',
            self::EUR => 'Euro',
            self::MAD => 'MAD',
        };
    }

    public function getSymbole(): string
    {
        return match ($this) {

            self::USD => '$',
            self::EUR => '€',
            self::MAD => 'DH',
        };
    }

    public function getCode(): string
    {
        return match ($this) {

            self::USD => 'usd',
            self::EUR => 'eur',
            self::MAD => 'mad',
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [
                $type->value => $type->getName(),
            ])
            ->toArray();
    }
}
