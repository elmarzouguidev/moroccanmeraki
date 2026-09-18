<?php

namespace App\Models\Tools;

use App\Models\Etablisement\Etablisement;
use App\Models\Platform\Food\Food;
use App\Traits\GetModelByKeyName;
use App\Traits\HasSlug;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amenity extends Model
{
    use GetModelByKeyName;
    use HasSlug;
    use SoftDeletes;
    use UuidGenerator;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'is_active',
        'is_valid',
    ];

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
        ];
    }
}
