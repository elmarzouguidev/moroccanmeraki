<?php

namespace App\Traits;

use App\Models\Tools\Team;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTeam
{
    public function teams(): MorphMany
    {
        return $this
            ->morphMany(Team::class, 'teamable')
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
