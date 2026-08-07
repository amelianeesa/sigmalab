<?php

namespace App\Enums;

/**
 * SEMENTARA -- nilai enum ini HARUS disamakan persis dengan isi tabel roles yang dibuat Orang 3.
 * Kalau nanti nilainya beda, ganti di sini saja, satu tempat.
 */
enum PeranPengguna: string
{
    case ANALIS = 'Analis';
    case KOORDINATOR_LAB = 'Koordinator Laboratorium';
    case ADMIN_LAB = 'Admin Lab';
    case KABID_INSPEKSI = 'Kabid Inspeksi dan Solusi Perdagangan'; 
    case KABID_DUKUNGAN_BISNIS = 'Kabid Dukungan Bisnis'; 
    case ADMIN_APLIKASI = 'Admin Aplikasi';
    case HR_GA_OFFICER = 'HR & GA';
}
