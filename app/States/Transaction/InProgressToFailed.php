<?php

namespace App\States\Transaction;

use Spatie\ModelStates\Transition;
use App\Models\Command\Transaction as TransactionModel;

class InProgressToFailed extends Transition
{
    private TransactionModel $transaction;

    private string $message;

    public function __construct(TransactionModel $transaction, string|null $message = null)
    {
        $this->transaction = $transaction;

        $this->message = $message;
    }

    public function handle(): TransactionModel
    {
        $this->transaction->status = new Failed($this->transaction);
        $this->transaction->failed_at = now();
        $this->transaction->error_message = $this->message;

        $this->transaction->save();

        return $this->transaction;
    }

    public function canTransition(): bool
    {
        return $this->transaction->status->equals(InProgress::class);
    }
}
