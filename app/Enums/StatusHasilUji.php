<?php

namespace App\Enums;

enum StatusHasilUji: string
{
    case PENDING = 'pending';
    case INLIER = 'inlier';
    case OUTLIER = 'outlier';
    case GAGAL_DUPLO = 'gagal_duplo';
}
