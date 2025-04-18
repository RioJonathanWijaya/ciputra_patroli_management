<?php

namespace App\Enums;

enum JabatanEnum: int
{
    case SATPAM = 0;
    case KEPALA_SATPAM = 1;

    public function getLabel(): string
    {
        return match($this) {
            self::SATPAM => 'Satpam',
            self::KEPALA_SATPAM => 'Kepala Satpam',
            default => 'Unknown',
        };
    }

    public static function getLabelByValue(int $value): string
    {
        return match($value) {
            self::SATPAM->value => 'Satpam',
            self::KEPALA_SATPAM->value => 'Kepala Satpam',
            default => 'Unknown',
        };
    }
} 