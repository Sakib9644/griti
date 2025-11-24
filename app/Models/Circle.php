<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Circle extends Model
{
    protected $fillable = [
        'video_id',
        'name',
        'title',
        'description',
        'order',
        'image'
    ];

    /**
     * Get the video that this circle belongs to.
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
    

    public function getImageAttribute($value){


        return $value ? url($value) : null;


    }
}
