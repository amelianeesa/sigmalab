<?php

namespace App\Enums;

/**
 * SEMENTARA -- nilai enum ini HARUS disamakan persis dengan isi tabel roles yang dibuat Orang 3.
 * Kalau nanti nilainya beda, ganti di sini saja, satu tempat.
 */
enum PeranPengguna: string
{
    case KOORDINATOR_LAB = 'Koordinator Laboratorium';
    case ANALIS = 'Analis Lab';
    case KABID_INSPEKSI = 'Kabid Inspeksi dan Solusi Perdagangan'; 
    case KABID_DUKUNGAN_BISNIS = 'Kabid Dukungan Bisnis'; 
    case HR_GA_OFFICER = 'HR & GA';
    case ADMIN_APLIKASI = 'Admin Aplikasi';
}
