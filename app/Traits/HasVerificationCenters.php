<?php

namespace App\Traits;

use App\Models\Verification\VerificationCenter;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasVerificationCenters
{
    public function verificationCenters(): MorphMany
    {
        return $this->morphMany(VerificationCenter::class, 'verifiable')->latest('verified_at');
    }

    public function latestVerificationCenter(): MorphOne
    {
        return $this->morphOne(VerificationCenter::class, 'verifiable')->latestOfMany('verified_at');
    }
}
