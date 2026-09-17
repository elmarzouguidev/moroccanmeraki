<?php


namespace App\States\Transaction;

use App\Enums\Transaction\TransactionStatusEnums;

class Pending extends TransactionState
{

    public static $name = TransactionStatusEnums::PENDING->value;

    public function color(): string
    {
        return 'blue';
    }

    public function value(): string
    {
        return self::$name;
    }

    public function label(): string
    {
        return 'En attente';
    }
}
