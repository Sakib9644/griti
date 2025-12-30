<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemesController extends Controller
{
    //

   public function themes(Request $request)
{
    $query = Theme::query()->where('status', 1); // Only active themes

    if ($request->query('search')) {
        $query->where('name', 'like', "%" . $request->query('search') . "%");
    }

    $themes = $query->get();

    return response()->json([
        'success' => true,
        'message' => 'Themes retrieved successfully',
        'data' => $themes->map(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->name,
                'image' => $t->image ? url($t->image) : null,
                'work_out' => $t->videos->count(),
            ];
        })
    ]);
}

}
