<?php


namespace App\States\Transaction;

use App\Enums\Transaction\TransactionStatusEnums;

class Cancelled extends TransactionState
{

  public static $name = TransactionStatusEnums::CANCELLED->value;

  public function color(): string
  {
    return 'bg-emerald-100 text-emerald-600';
  }

  public function value(): string
  {
    return self::$name;
  }

      public function label(): string
    {
        return 'Annulée';
    }
}
