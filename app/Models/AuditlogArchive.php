<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditlogArchive extends Model
{
    protected $table = 'auditlog_archives';

    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'array',
        'attribute_changes' => 'array',
    ];
    public function causer()
    {
        return $this->morphTo();
    }
    public function subject()
    {
        return $this->morphTo();
    }
}