<?php

namespace App\Models\Security;

use App\Traits\GetModelByKeyName;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TwoFactorTrustedDevice extends Model
{
    use GetModelByKeyName;
    use SoftDeletes;
    use UuidGenerator;

    /** @var array<int, string> */
    protected $fillable = [
        'authenticatable_type',
        'authenticatable_id',
        'token_hash',
        'expires_at',
        'last_used_at',
        'user_agent',
        'ip_address',
    ];

    /** @var array<int, string> */
    protected $hidden = [
        'token_hash',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'last_used_at' => 'datetime',
        ];
    }

    public function authenticatable(): MorphTo
    {
        return $this->morphTo();
    }
}
