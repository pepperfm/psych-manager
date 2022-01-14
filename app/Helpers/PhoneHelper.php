<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class PhoneHelper
{
    public static function clear($phone): array|string|null
    {
        return preg_replace('/\D/', '', trim($phone));
    }

    public static function deleteCountryCode($phone): string
    {
        $phone = self::clear($phone);
        if (Str::startsWith($phone, '7') || Str::startsWith($phone, '8')) {
            return Str::substr($phone, 1);
        }

        return $phone;
    }
}
