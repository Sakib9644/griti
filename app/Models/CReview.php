<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CReview extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = [
        'title',
        'description',
        'rating',
        'image',
        'user_id',
    ];

    public function getImageAttribute($image){

        return $image ? url($image) : null;
    }
}
