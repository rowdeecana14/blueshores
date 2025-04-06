<?php

namespace App\Enums\User;

enum Status: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case DISAPPROVED = 'disapproved';
    case DEACTIVATED = 'deactivated';

    public static function getValues(): array
    {
        return array_column(Status::cases(), 'value');
    }
}
