<?php

namespace App\Enums\Tools;

enum ReactionType: string
{
    case LIKE = 'like';
    case LOVE = 'love';
    case HELPFUL = 'helpful';
}
