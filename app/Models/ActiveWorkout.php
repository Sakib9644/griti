<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveWorkout extends Model
{
    //

    protected $guarded = [];


    public function workout_list()
    {
        return $this->belongsTo(Video::class, 'videos_id');
    }
}
