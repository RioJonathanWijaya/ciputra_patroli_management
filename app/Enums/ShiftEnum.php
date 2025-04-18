<?php

namespace App\Enums;

enum ShiftEnum: int
{
    case PAGI = 0;
    case MALAM = 1;

    public function getLabel(): string
    {
        return match($this) {
            self::PAGI => 'Pagi',
            self::MALAM => 'Malam',
            default => 'Unknown',
        };
    }

    public static function getLabelByValue(int $value): string
    {
        return match($value) {
            self::PAGI->value => 'Pagi',
            self::MALAM->value => 'Malam',
            default => 'Unknown',
        };
    }
} 