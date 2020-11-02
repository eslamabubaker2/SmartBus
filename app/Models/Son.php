<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Arrival;


class Son extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'Is_agree' => 'int',
        'parent_id'=>'int',
        'school_id'=>'int',
        'transport_id'=>'int',
        'gender'=>'int',

    ];
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i');
    }


    public function school()
{
        return $this->belongsTo('App\Models\School','school_id');
    }
    public function parents()
    {

        return $this->belongsTo('App\User','parent_id');
    }

    public function transportor()
    {

        return $this->belongsTo('App\Models\transportor','transport_id');
    }

    public function arrival()
    {
        return $this->hasMany('App\Models\Arrival','son_id')->orderBy('id', 'desc');

    }

    public function arrivallast()
    {
        return $this->hasMany('App\Models\Arrival','son_id')->orderBy('id', 'desc')->latest()->take(2);

    }

}
