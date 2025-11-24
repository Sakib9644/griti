<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutVideos extends Model
{
    // If your table is not the plural of the model name
    protected $table = 'workout_videos';

    // Fillable fields
    protected $fillable = ['title', 'description', 'category_id', 'user_id', 'video_path'];

    // Relationship: Each video belongs to a category
   public function video()
{
    return $this->belongsTo(Video::class, 'category_id');
    // adjust class & foreign key based on your setup
}
    // Relationship: Each video belongs to a user (uploader)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
