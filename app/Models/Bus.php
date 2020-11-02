<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $casts = [
        'no_bus' => 'int',
        'role'=>'int',
        'schoobus_id'=>'int',
        'state'=>'int',

    ];
    protected $guarded = [];

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function school()
    {
        return $this->belongsTo('App\Models\School', 'schoobus_id');
    }

    public function transpotor()
    {
        return $this->belongsTo('App\Models\transportor','transport_id')->with('students');


    }


}
