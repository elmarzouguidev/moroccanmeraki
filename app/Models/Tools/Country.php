<?php

namespace App\Models\Tools;

use App\Traits\GetModelByKeyName;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;
    use UuidGenerator;
    use GetModelByKeyName;

    protected $fillable = [
        'code',
        'name',
        'slug',
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

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function banks(): HasMany
    {
        return $this->hasMany(BankCompany::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
