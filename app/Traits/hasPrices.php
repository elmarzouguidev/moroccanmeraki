<?php

namespace App\Traits;

use App\Enums\Tools\CurrencyType;
use App\Models\Utilities\Price;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait hasPrices
{
    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    /**
     * @param  array<int, array<string, mixed>>  $prices
     */
    public function syncPrices(array $prices): void
    {
        $this->prices()->delete();

        foreach ($prices as $price) {
            $this->prices()->create([
                'label' => $price['label'],
                'amount' => $price['amount'],
                'discount_amount' => $price['discount_amount'] ?? 0,
                'currency' => $price['currency'] ?? CurrencyType::MAD->value,
                'is_active' => true,
                'is_valid' => true,
            ]);
        }
    }
}
