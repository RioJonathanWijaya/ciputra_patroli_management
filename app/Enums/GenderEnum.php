<?php

namespace App\Enums;

enum GenderEnum: int
{
    case LAKI_LAKI = 0;
    case PEREMPUAN = 1;

    public function getLabel(): string
    {
        return match($this) {
            self::LAKI_LAKI => 'Laki-Laki',
            self::PEREMPUAN => 'Perempuan',
            default => 'Unknown',
        };
    }

    public static function getLabelByValue(int $value): string
    {
        return match($value) {
            self::LAKI_LAKI->value => 'Laki-Laki',
            self::PEREMPUAN->value => 'Perempuan',
            default => 'Unknown',
        };
    }
} 