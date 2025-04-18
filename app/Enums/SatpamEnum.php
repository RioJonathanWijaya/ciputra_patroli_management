<?php

namespace App\Enums;

enum SatpamEnum: int
{
    // Shift values
    case SHIFT_PAGI = 0;
    case SHIFT_MALAM = 1;

    // Jabatan values
    case JABATAN_SATPAM = 10;
    case JABATAN_KEPALA_SATPAM = 11;

    public function getShiftLabel(): string
    {
        return match($this) {
            self::SHIFT_PAGI => 'Shift Pagi',
            self::SHIFT_MALAM => 'Shift Malam',
            default => 'Unknown',
        };
    }

    public function getJabatanLabel(): string
    {
        return match($this) {
            self::JABATAN_SATPAM => 'Satpam',
            self::JABATAN_KEPALA_SATPAM => 'Kepala Satpam',
            default => 'Unknown',
        };
    }

    public static function getShiftLabelByValue(int $value): string
    {
        return match($value) {
            self::SHIFT_PAGI->value => 'Shift Pagi',
            self::SHIFT_MALAM->value => 'Shift Malam',
            default => 'Unknown',
        };
    }

    public static function getJabatanLabelByValue(int $value): string
    {
        return match($value) {
            self::JABATAN_SATPAM->value => 'Satpam',
            self::JABATAN_KEPALA_SATPAM->value => 'Kepala Satpam',
            default => 'Unknown',
        };
    }
} 