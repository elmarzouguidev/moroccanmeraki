<?php

namespace App\Models\Security;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public function getLabelAttribute(): string
    {
        $key = "permissions.names.{$this->name}";
        $translated = __($key);

        if ($translated !== $key) {
            return $translated;
        }

        return Str::headline(str_replace('-', ' ', $this->name));
    }

    public function getDescriptionAttribute(): string
    {
        $key = "permissions.descriptions.{$this->name}";
        $translated = __($key);

        if ($translated !== $key) {
            return $translated;
        }

        return $this->label;
    }
}
