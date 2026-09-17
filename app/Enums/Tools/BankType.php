<?php

namespace App\Enums\Tools;

enum BankType: string
{
    case RIB = 'RIB';
    case CARD = 'CARD';

    public function getColor(): string
    {
        return match ($this) {
            BankType::RIB => 'bg-secondary',
            BankType::CARD => 'bg-success',
        };
    }

    public function getName(): string
    {
        return match ($this) {

            self::RIB => 'RIB',
            self::CARD => 'CARD',
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
