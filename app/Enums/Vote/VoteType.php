<?php

namespace App\Enums\Vote;

enum VoteType: string
{
    case UP = 'up';
    case DOWN = 'down';

    public static function getValues(): array
    {
        return array_column(VoteType::cases(), 'value');
    }
}
