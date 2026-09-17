<?php

namespace App\Traits;

use App\Models\Business\BusinessAccount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasBusinessAccount
{
    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }
}
