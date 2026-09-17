<?php

namespace App\Traits;

use App\Models\Tools\Price;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait hasChildrens
{
    public function childrens()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->with('childrens');
    }

    public function subcategory()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parents()
    {
        return $this->hasMany(self::class, 'id', 'parent_id');
    }

    public static function tree()
    {
        $allCategories = self::without(['childrens'])
            ->select(['id', 'parent_id', 'slug', 'name'])
            ->get();

        $rootCategories = $allCategories->whereNull('parent_id');

        self::formatTree($rootCategories, $allCategories);

        return $rootCategories;
    }

    private static function formatTree($categories, $allCategories)
    {

        foreach ($categories as $category) {

            $category->nestedChilds = $allCategories->where('parent_id', $category->id)->values();

            if ($category->nestedChilds->isNotEmpty()) {
                self::formatTree($category->nestedChilds, $allCategories);
            }
        }
    }

    public function scopeShowInMenu($query)
    {
        // return Cache::remember($this->cacheKey() . ':categoriesMenu', $this->timeToLive(), function () use ($query) {

        // });
        return $query->whereIsActive(true)
            // ->with(['translations'])
            ->without(['childrens'])
            ->get();
    }
}
