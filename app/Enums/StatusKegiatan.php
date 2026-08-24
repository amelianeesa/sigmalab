<?php

namespace App\Enums;

enum StatusKegiatan: string
{
    case DRAFT = 'draft';
    case BERJALAN = 'berjalan';
    case SELESAI = 'selesai';
}
