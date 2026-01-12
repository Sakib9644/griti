<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    //

    protected $guarded = [];

     public function workoutlist()
    {
        return $this->belongsTo(WorkoutVideos::class, 'workout_videos_id');
    }

       public function getMusicFileAttribute($value) // notice the capital F
{
        return $value ? url($value) : null;
}
}
