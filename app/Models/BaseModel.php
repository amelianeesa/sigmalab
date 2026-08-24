<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public $incrementing = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->primaryKey = $this->getTable() . '_id';
    }
}
