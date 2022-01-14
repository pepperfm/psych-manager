<?php

namespace App\Enums;

class Enum
{
    /**
     * @param array $cases
     * @param bool $expanded
     * @param string $idField
     * @param string $nameField
     *
     * @return array
     */
    public static function makeList(array $cases, bool $expanded = true, string $idField = 'id', string $nameField = 'name'): array
    {
        if ($expanded)  {
            $array = [];
            foreach ($cases as $case) {
                $array[] = [$idField => $case->value, $nameField => $case->label()];
            }

            return $array;
        }

        return $cases;
    }
}
