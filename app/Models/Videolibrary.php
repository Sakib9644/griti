<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Videolibrary extends Model
{
    //

    protected $guarded = [];

      public function workoutVideo()
    {
        return $this->belongsTo(WorkoutVideos::class, 'work_out_video_id');
        // adjust class & foreign key based on your setup
    }
}
