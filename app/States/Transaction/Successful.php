<?php


namespace App\States\Transaction;

use App\Enums\Transaction\TransactionStatusEnums;

class Successful extends TransactionState
{

    public static $name = TransactionStatusEnums::SUCCESSFUL->value;

    public function color(): string
    {
        return 'bg-emerald-100 text-emerald-800';
    }

    public function value(): string
    {
        return self::$name;
    }

    public function label(): string
    {
        return 'Réussie';
    }
}
