<?php

namespace App\States\Transaction;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

/**
 * @extends State<\App\Models\Command\Transaction>
 */
abstract class TransactionState extends State
{
    abstract public function color(): string;

    abstract public function value(): string;

    abstract public function label(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransitions([
                [Pending::class, InProgress::class],
                [Pending::class, Failed::class],
                [Pending::class, Cancelled::class],
                [InProgress::class, Successful::class],
                [InProgress::class, Failed::class, InProgressToFailed::class],
                [InProgress::class, Cancelled::class],
                [Failed::class, Cancelled::class],
            ]);
    }
}
