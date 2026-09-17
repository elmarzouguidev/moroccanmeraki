<?php


namespace App\States\Transaction;

use App\Enums\Transaction\TransactionStatusEnums;

class Failed extends TransactionState
{

  public static $name = TransactionStatusEnums::FAILED->value;

  public function color(): string
  {
    return 'red';
  }

  public function value(): string
  {
    return self::$name;
  }

      public function label(): string
    {
        return 'Échouée';
    }
}
