<?php

namespace App\Traits;

use App\Models\Utilities\Address;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait hasAddresses
{
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
