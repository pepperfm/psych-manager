<?php

namespace App\Enums;

enum MeetingTypeEnum: int
{
    case ONLINE = 0;
    case OFFLINE = 1;

    public function label(): string
    {
        return match($this) {
            self::ONLINE => 'Онлайн',
            self::OFFLINE => 'Офлайн',
        };
    }
}
