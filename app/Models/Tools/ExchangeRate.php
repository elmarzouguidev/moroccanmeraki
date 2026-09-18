<?php

namespace App\Models\Tools;

use App\Enums\Utilities\ConversionCurrencyType;
use App\Traits\GetModelByKeyName;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
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

            'from_currency' => ConversionCurrencyType::class,
            'to_currency' => ConversionCurrencyType::class,
            'rate' => 'float',
            'expires_at' => 'datetime',

        ];
    }

    /**
     * Check if rate is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at?->isPast();
    }

    /**
     * Scope for active rates
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

 /**
     * Accessor for Inverse Rate: 1 / rate
     */
    public function getInverseRateAttribute(): float
    {
        return $this->rate > 0 ? (1 / $this->rate) : 0.0;
    }
}