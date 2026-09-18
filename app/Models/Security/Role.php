<?php

namespace App\Models\Security;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function getLabelAttribute(): string
    {
        $key = "roles.names.{$this->name}";
        $translated = __($key);

        if ($translated !== $key) {
            return $translated;
        }

        return Str::headline(str_replace(['-', '_'], ' ', $this->name));
    }

    public function getDescriptionAttribute(): string
    {
        $key = "roles.descriptions.{$this->name}";
        $translated = __($key);

        if ($translated !== $key) {
            return $translated;
        }

        return $this->label;
    }
}
