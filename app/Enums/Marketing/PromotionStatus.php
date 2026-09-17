<?php

namespace App\Enums\Marketing;

enum PromotionStatus: string
{
    case DRAFT = 'draft';

    case SCHEDULED = 'scheduled';

    case PUBLISHED = 'published';

    case PAUSED = 'paused';

    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::SCHEDULED => 'Planifiée',
            self::PUBLISHED => 'Publiée',
            self::PAUSED => 'En pause',
            self::ARCHIVED => 'Archivée',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status): array => [$status->value => $status->label()])
            ->all();
    }
}
