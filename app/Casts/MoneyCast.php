<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value (from database to model).
     * Converts cents to dollars (divide by 100)
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?float
    {
        if ($value === null) {
            return null;
        }

        return $value / 100;
    }

    /**
     * Get the raw cent value for Stripe API
     */
    public function getCentsValue(Model $model, string $key): ?int
    {
        return $model->getRawOriginal($key);
    }

    /**
     * Prepare the given value for storage (from model to database).
     * Converts dollars to cents (multiply by 100)
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        if ($value === null) {
            return null;
        }

        //return (int) round($value * 100);
        return (int) ($value * 100);
    }
}
