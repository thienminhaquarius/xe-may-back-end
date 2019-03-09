<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bike extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'thumbnailImage',
        'user_id',
    ];

    /**
     * Relationship.
     *
     * @var string
     */

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function ratings()
    {
        return $this->hasMany('App\Rating');
    }

    public function bikedetail()
    {
        return $this->hasOne('App\Bikedetail');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
