<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nutration extends Model
{
    //

    protected $guarded = [];

    public function user(){

        return $this->belongsTo(Nutration::class);
    }
}
