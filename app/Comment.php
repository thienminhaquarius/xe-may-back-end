<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = [
        'title',
        'content',
        'bike_id',
        'user_id',
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
