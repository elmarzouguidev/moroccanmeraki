<?php

namespace App\Models\Tools;

use App\Casts\MoneyCast;
use App\Enums\Tools\CurrencyType;
use App\Enums\Utilities\ConversionCurrencyType;
use App\Traits\GetModelByKeyName;
use App\Traits\HasSlug;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Price extends Model
{
    use HasFactory;
    use UuidGenerator;
    use GetModelByKeyName;
    use HasSlug;


    protected $slugName = 'label';
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

            'amount' => MoneyCast::class,
            'options' => AsArrayObject::class,
            'currency' => CurrencyType::class,
            //'currency' => ConversionCurrencyType::class, // used with conversion Rate 
        ];
    }


    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }
}
