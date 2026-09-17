<?php

namespace App\Traits;

use App\Models\Security\TwoFactorTrustedDevice;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTrustedTwoFactorDevices
{
    public function trustedTwoFactorDevices(): MorphMany
    {
        return $this->morphMany(TwoFactorTrustedDevice::class, 'authenticatable');
    }
}
