<?php
namespace App\Traits;

use Spatie\ResponseCache\Facades\ResponseCache;

trait ClearsResponseCache
{
    public static function bootClearsResponseCache(): void
    {
        self::created(fn () => ResponseCache::clear());
        self::updated(fn () => ResponseCache::clear());
        self::deleted(fn () => ResponseCache::clear());
    }
}