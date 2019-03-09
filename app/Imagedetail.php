<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imagedetail extends Model
{
    protected $fillable = [
        'src',
        'bikedetail_id',
    ];

    public function bikedetail()
    {
        return $this->belongsTo("App\Bikedetail");
    }
}
