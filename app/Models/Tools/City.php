<?php

namespace App\Models\Tools;

use App\Traits\GetModelByKeyName;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //

    use UuidGenerator;
    use GetModelByKeyName;



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_valid' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }
}
