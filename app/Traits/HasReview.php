<?php

namespace App\Traits;

use App\Models\Tools\Review;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReview
{
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
