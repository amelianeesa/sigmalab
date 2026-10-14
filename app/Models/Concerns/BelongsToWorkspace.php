<?php

namespace App\Models\Concerns;

use App\Support\Workspace;
use Illuminate\Database\Eloquent\Builder;

/**
 * Otomatis membatasi query ke workspace aktif dan mengisi kolom workspace saat create.
 * Tidak aktif di console/queue/halaman publik (current() = null), jadi command terjadwal
 * seperti CekKalibrasiKadaluwarsa tetap memproses semua data.
 */
trait BelongsToWorkspace
{
    protected static function bootBelongsToWorkspace(): void
    {
        static::addGlobalScope('workspace', function (Builder $query) {
            $ws = Workspace::current();
            if ($ws) {
                $query->where($query->getModel()->getTable() . '.workspace', $ws);
            }
        });

        static::creating(function ($model) {
            if (empty($model->workspace)) {
                $model->workspace = Workspace::current() ?? Workspace::LAB;
            }
        });
    }
}
