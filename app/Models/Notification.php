<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
