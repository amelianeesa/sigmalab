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
    case PEGAWAI = 'Non-Lab';

    public static function pengelolaSdm(): array
    {
        return [
            self::HR_OFFICER->value,
            self::KOORDINATOR_LAB->value,
            self::ADMIN_APLIKASI->value,
        ];
    }

    /** Role yang otomatis bisa Lab & Non-Lab (workspace ALL). Role lain wajib memilih satu. */
    public static function lintasWorkspace(): array
    {
        return [
            self::HR_OFFICER->value,
            self::GA_OFFICER->value,
            self::ADMIN_APLIKASI->value,
            self::KABID_INSPEKSI->value,
            self::KABID_DUKUNGAN_BISNIS->value,
            self::KOORDINATOR_LAB->value, // menyetujui peminjaman Non-Lab, jadi perlu bisa pindah workspace
        ];
    }

    /** Role yang menyetujui, menyerahkan, dan menerima pengembalian alat (workspace Non-Lab). */
    public static function pengelolaPeminjaman(): array
    {
        return [
            self::KOORDINATOR_LAB->value,
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