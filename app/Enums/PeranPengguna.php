<?php

namespace App\Enums;

enum PeranPengguna: string
{
    case KOORDINATOR_LAB = 'Koordinator Laboratorium';
    case ANALIS = 'Analis Lab';
    case HR_OFFICER = 'HR';
    case GA_OFFICER = 'GA';
    case KABID_INSPEKSI = 'Kabid Inspeksi dan Solusi Perdagangan';
    case KABID_DUKUNGAN_BISNIS = 'Kabid Dukungan Bisnis';
    case ADMIN_APLIKASI = 'Admin Aplikasi';

    public static function pengelolaSdm(): array
    {
        return [
            self::HR_OFFICER->value,
            self::KOORDINATOR_LAB->value,
            self::ADMIN_APLIKASI->value,
        ];
    }

    public static function pembuatAkun(): array
    {
        return [
            self::HR_OFFICER->value,
            self::ADMIN_APLIKASI->value,
        ];
    }
}