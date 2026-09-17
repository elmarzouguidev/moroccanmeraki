<?php

namespace App\Enums\Marketing;

use App\Models\Etablisement\Etablisement;
use App\Models\Platform\Activity\Activity;
use App\Models\Platform\Event\Event;
use App\Models\Platform\Food\Food;

enum PromotionTargetType: string
{
    case FOOD = Food::class;

    case ACTIVITY = Activity::class;

    case EVENT = Event::class;

    case ETABLISSEMENT = Etablisement::class;

    public function label(): string
    {
        return match ($this) {
            self::FOOD => 'Adresse gourmande',
            self::ACTIVITY => 'Activité',
            self::EVENT => 'Événement',
            self::ETABLISSEMENT => 'Établissement',
        };
    }

    /** @return class-string */
    public function modelClass(): string
    {
        return $this->value;
    }
}
