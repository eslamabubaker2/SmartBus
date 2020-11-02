<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $casts = [
        'id' => 'int',

    ];

    protected $guarded = [];
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
