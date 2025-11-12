<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    // Fillable fields (optional but recommended)
    protected $fillable = [
        'name',
        'category_id',
        'type',
        'image',
    ];

    /**
     * Get the category this theme belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
