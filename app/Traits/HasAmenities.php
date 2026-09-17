<?php

namespace App\Traits;

use App\Models\Utilities\Amenity;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasAmenities
{
    /**
     * Get the amenities assigned to the model.
     */
    public function amenities(): MorphToMany
    {
        return $this->morphToMany(Amenity::class, 'amenitable');
    }
}
