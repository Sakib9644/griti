<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    //

    protected $guarded = [];

    public function user(){

        return $this->belongsTo(User::class);
    }
    public function getAgeAttribute($value){

      return Carbon::parse($value)->age;

    }
}
