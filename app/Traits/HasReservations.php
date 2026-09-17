<?php

namespace App\Traits;

use App\Models\Tools\Reservation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReservations
{
    public function reservations(): MorphMany
    {
        return $this->morphMany(Reservation::class, 'reservable');
    }
}
