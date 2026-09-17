<?php

namespace App\Enums\Marketing;

use App\Models\Etablisement\Etablisement;
use App\Models\Marketing\Promotion;
use App\Models\Platform\Activity\Activity;
use App\Models\Platform\Event\Event;
use App\Models\Platform\Food\Food;

enum CouponTargetType: string
{
    case PROMOTION = Promotion::class;
    case ACTIVITY = Activity::class;
    case FOOD = Food::class;
    case ETABLISSEMENT = Etablisement::class;
    case EVENT = Event::class;

    public function label(): string
    {
        return match ($this) {
            self::PROMOTION => 'Promotion',
            self::ACTIVITY => 'Activité',
            self::FOOD => 'Adresse gourmande',
            self::ETABLISSEMENT => 'Établissement',
            self::EVENT => 'Événement',
        };
    }

    public function modelClass(): string
    {
        return $this->value;
    }

    public function displayColumn(): string
    {
        return $this === self::PROMOTION ? 'title' : 'name';
    }
}
