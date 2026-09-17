<?php

namespace App\Traits;

use App\Models\Marketing\Coupon;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasCoupons
{
    /**
     * Get the coupons assigned to the model.
     */
    public function coupons(): MorphToMany
    {
        return $this->morphToMany(Coupon::class, 'couponable');
    }
}
