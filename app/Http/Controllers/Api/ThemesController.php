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

        $query = Theme::query();

        if ($request->query('search')) {

            $query->where('name', 'Like', "%" .$request->query('search')."%");
        }

        $theme = $query->get();


        return response()->json([
            'success' => true,
            'message' => 'Theme retrive Sucessfully',
            'data' => $theme->map(function ($t) {

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
