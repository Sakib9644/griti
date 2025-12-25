<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'category_id',  // added
        'theme_id',
        'title',
        'video',
        'image',
        'description',
        'type',         // added (training level)
        'calories',     // if you want calories
        'minutes',      // if you want minutes
    ];

    /**
     * A video belongs to a theme.
     */
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
    public function library()
    {
        return $this->hasMany(Videolibrary::class,'video_id');
    }



    /**
     * A video belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A video has many circles.
     */
    public function circles()
    {
        return $this->hasMany(Circle::class);
    }

    /**
     * Get full URL for image.
     */
    public function getImageAttribute($value)
    {
        return $value ? url($value) : null;
    }

    /**
     * Get full URL for video.
     */
    public function getVideoAttribute($value)
    {
        return $value ? url($value) : null;
    }
}
