<?php

namespace App\Enums\Tools;

enum PaymentMethod: string
{
    case STRIPE = 'STRIPE';
    case PAYPAL = 'PAYPAL';
    case CMI = 'CMI';

    public function getName(): string
    {
        return match ($this) {

            self::STRIPE => 'Stripe',
            self::PAYPAL => 'Paypal',
            self::CMI => 'CMI',
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
