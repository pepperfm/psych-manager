<?php

namespace App\Enums;

enum ConnectionTypeEnum: int
{
    case PHONE = 1;
    case EMAIL = 2;
    case VK = 3;
    case VIBER = 4;
    case WHATSAPP = 5;
    case TELEGRAM = 6;

    public function label(): string
    {
        return match($this) {
            self::PHONE => 'Телефон',
            self::EMAIL => 'Email',
            self::VK => 'Vk',
            self::VIBER => 'Viber',
            self::WHATSAPP => 'Whatsapp',
            self::TELEGRAM => 'Telegram'
        };
    }
}
