<?php

namespace App\Models\Concerns;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Str;

trait LogsStandardActivity
{
    public function getActivitylogOptions(): LogOptions
    {
        $modelName = class_basename($this);
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(fn($eventName) => "Data {$modelName} telah di-{$eventName}")
            ->useLogName(Str::snake($modelName));
    }
}
