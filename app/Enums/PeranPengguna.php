<?php

namespace App\Enums;


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
