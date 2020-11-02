<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrival extends Model
{
    protected $casts = [
        'Sure_go' => 'int',
        'Sure_return'=>'int',
        'cancel_arrive'=>'int',
        'transport_id'=>'int',
        'son_id' => 'int',
        'son_id'=>'int',


    ];
    protected $guarded = [];

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function student()
    {

        return $this->belongsTo('App\Models\Son','son_id');
    }
    public function rating()
    {

        return $this->hasOne('App\Models\Rating','arrival_id');
    }
    public function school()
    {
            return $this->belongsTo('App\Models\School','school_id');
        }


    public function Students()
    {

        return $this->belongsTo('App\Models\Son','son_id');
    }

}
