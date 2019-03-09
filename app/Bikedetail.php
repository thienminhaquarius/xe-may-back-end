<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bikedetail extends Model
{
    protected $fillable = [
        'info',
        'bike_id',
    ];

    public function bike()
    {
        return $this->belongsTo('App\Bike');
    }

    public function imagedetails()
    {
        return $this->hasMany('App\Imagedetail');
    }
}
