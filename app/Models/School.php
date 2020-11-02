<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $casts = [
        'city_id' => 'int',
        'latitude'=>'int',
        'longitude'=>'int',
        'no_students'=>'int',
        'no_buses'=>'int',
        'director_id'=>'int',

    ];
    protected $guarded = [];
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function Director() {
        return $this->belongsTo('App\User','director_id');
    }
    public function students()
    {
        return $this->hasMany('App\Models\Son','school_id');

    }
    public function buses()
    {
        return $this->hasMany('App\User','schoobus_id');

    }
    public function arrival()
    {
        return $this->hasMany('App\Models\Arrival','school_id')->orderBy('id', 'desc');

    }
}
