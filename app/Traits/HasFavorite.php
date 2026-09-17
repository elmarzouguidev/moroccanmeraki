<?php

namespace App\Traits;

use App\Models\Tools\Favorite;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasFavorite
{
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
}
