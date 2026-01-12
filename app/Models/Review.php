<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'rating',
        'user_id',
    ];

    // Relation to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
