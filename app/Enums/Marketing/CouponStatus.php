<?php

namespace App\Enums\Marketing;

enum CouponStatus: string
{
    case DRAFT = 'draft';

    case ACTIVE = 'active';

    case PAUSED = 'paused';

    case EXPIRED = 'expired';

    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::ACTIVE => 'Actif',
            self::PAUSED => 'En pause',
            self::EXPIRED => 'Expiré',
            self::ARCHIVED => 'Archivé',
        };
    }
}
