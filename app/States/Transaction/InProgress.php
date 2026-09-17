<?php


namespace App\States\Transaction;

use App\Enums\Transaction\TransactionStatusEnums;

class InProgress extends TransactionState
{

    public static $name = TransactionStatusEnums::IN_PROGRESS->value;

    public function color(): string
    {
        return 'orange';
    }
    public function value(): string
    {
        return self::$name;
    }

    public function label(): string
    {
        return 'En cours';
    }
}
