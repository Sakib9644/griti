<?php

namespace App\Traits;

use App\Models\CMS;
use Illuminate\Support\Facades\Cache;


trait CMSData
{
    public static function all()
    {
        return Cache::rememberForever('cms', function () {
            return CMS::where('status', 'active')->get();
        });
    }
}
