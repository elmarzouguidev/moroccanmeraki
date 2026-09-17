<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Builder as ScoutBuilder;
use Laravel\Scout\Searchable;

trait SearchablePublicListing
{
    use Searchable;

    /** @return array{name: string, excerpt: string} */
    #[SearchUsingFullText(['name', 'excerpt'])]
    public function toSearchableArray(): array
    {
        return ['name' => (string) $this->name, 'excerpt' => strip_tags((string) $this->excerpt)];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->is_active && $this->is_valid && $this->deleted_at === null && filled($this->slug);
    }

    public function scopePubliclySearchable(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('is_valid', true)
            ->whereNull('deleted_at')
            ->whereNotNull('slug')
            ->where('slug', '!=', '');
    }

    public function newScoutQuery(ScoutBuilder $builder): Builder
    {
        return $this->newQuery()->publiclySearchable();
    }
}
