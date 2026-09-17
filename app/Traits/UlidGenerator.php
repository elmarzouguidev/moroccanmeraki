<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait UlidGenerator
{
    public function scopeUlid(Builder $query, $ulid): Builder
    {
        return $query->where($this->getUlidName(), $ulid);
    }

    public function getUlidName(): string
    {
        return property_exists($this, 'ulidName') ? $this->ulidName : 'ulid';
    }

    public static function bootUlidGenerator(): void
    {
        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), $model->getUlidName())) {
                $model->{$model->getUlidName()} = Str::ulid()->toBase32();
            }
        });
    }
}
