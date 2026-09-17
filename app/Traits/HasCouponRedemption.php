<?php

namespace App\Traits;

use App\Models\Marketing\CouponRedemption;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasCouponRedemption
{
    public function couponRedemption(): MorphOne
    {
        return $this->morphOne(CouponRedemption::class, 'redeemable')->with('coupon');
    }
}
