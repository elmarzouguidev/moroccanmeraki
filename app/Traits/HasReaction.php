<?php

namespace App\Traits;

use App\Models\Tools\Reaction;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReaction
{
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
}
