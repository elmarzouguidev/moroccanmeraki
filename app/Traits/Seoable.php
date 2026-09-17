<?php

namespace App\Traits;

use App\Models\CMS\SEO;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

trait Seoable
{
    public function seo(): MorphOne
    {
        return $this->morphOne(SEO::class, 'seoable');
    }
}
