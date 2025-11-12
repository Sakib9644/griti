<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'theme_id',
        'title',
        'video',
        'image',
        'description',
    ];

    /**
     * A video belongs to a theme.
     */
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * A video has many circles.
     */
    public function circles()
    {
        return $this->hasMany(Circle::class);
    }
}
