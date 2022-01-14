<?php

namespace App\Enums;

enum RoleEnum: int
{
    case CLIENT = 0;
    case DOCTOR = 1;
    case ULTRA_ADMIN = 10;

    public function label(): string
    {
        return match($this) {
            self::CLIENT => 'Клиент',
            self::DOCTOR => 'Специалист',
            self::ULTRA_ADMIN => 'Ультра-админ',
        };
    }
}
