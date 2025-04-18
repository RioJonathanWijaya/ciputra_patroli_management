<?php

namespace App\Enums;

enum MaritalEnum: int
{
    case MENIKAH = 0;
    case TIDAK_MENIKAH = 1;

    public function getLabel(): string
    {
        return match($this) {
            self::MENIKAH => 'Menikah',
            self::TIDAK_MENIKAH => 'Tidak Menikah',
            default => 'Unknown',
        };
    }

    public static function getLabelByValue(int $value): string
    {
        return match($value) {
            self::MENIKAH->value => 'Menikah',
            self::TIDAK_MENIKAH->value => 'Tidak Menikah',
            default => 'Unknown',
        };
    }
} 