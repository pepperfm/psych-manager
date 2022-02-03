<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

class PhoneHelper
{
    public static function clear($phone): array|string|null
    {
        $phone = self::deleteCountryCode($phone);

        return preg_replace('/\D/', '', trim($phone));
    }

    #[Pure] public static function deleteCountryCode($phone): string
    {
        if (Str::startsWith($phone, '7') || Str::startsWith($phone, '8')) {
            return Str::substr($phone, 1);
        }

        return $phone;
    }
}
