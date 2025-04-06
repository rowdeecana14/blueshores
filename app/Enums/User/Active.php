<?php

namespace App\Enums\User;

enum Active: int
{
    case YES = 1;
    case NO = 0;

    public static function getValues(): array
    {
        return array_column(Active::cases(), 'value');
    }
}
