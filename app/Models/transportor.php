<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transportor extends Model
{
    protected $guarded = [];
    protected $casts = [
        'driver_id' => 'int',
        'schoobus_id'=>'int',

    ];
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function driver()//الباص له سائق واحد
    {
        return $this->belongsTo('App\User','driver_id');
    }



    public function students()
    {
        return  $this->hasMany('App\Models\Son','transport_id');


    }

    public function Bus()
    {
        return $this->hasOne('App\User','no_bus');

    }



}
