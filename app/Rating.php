<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    //
    protected $fillable = [
        'value',
        'user_id',
        'bike_id',
    ];

    public function bike()
    {
        return $this->belongsTo('App\Bike');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
