<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{

    protected $guarded = [];
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

 public function arrival() {
        return $this->belongsTo('App\Models\Arrival','arrival_id');
    }
}
