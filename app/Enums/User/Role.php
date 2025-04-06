<?php

namespace App\Enums\User;

enum Role: string
{
    case USER = 'user';
    case ADMIN = 'admin';

    public static function getValues(): array
    {
        return array_column(Role::cases(), 'value');
    }
}
