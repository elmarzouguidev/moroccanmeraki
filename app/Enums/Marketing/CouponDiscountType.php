<?php

namespace App\Enums\Marketing;

enum CouponDiscountType: string
{
    case PERCENTAGE = 'percentage';

    case FIXED = 'fixed';

    public function label(): string
    {
        return match ($this) {
            self::PERCENTAGE => 'Pourcentage',
            self::FIXED => 'Montant fixe',
        };
    }
}
