<?php

namespace App\Http\Controllers;

use App\Models\Nutration;
use App\Models\Recipe;
use Illuminate\Http\Request;

class NutrationController extends Controller
{
    //

    public function store(Request $request)
    {
        $request->validate([
            'goals_for' => 'required|string',
            'dietary_preferences' => 'nullable|string',
            'intolerances' => 'nullable|string',
            'activity_level' => 'nullable|string',
            'dont_like' => 'nullable|string',
        ]);

        $user = auth('api')->id();

        // Correct use of firstOrNew
        $nutration = Nutration::firstOrNew(['user_id' => $user]);

        // Fill fields
        $nutration->goals_for = $request->goals_for;
        $nutration->dietary_preferences = $request->dietary_preferences;
        $nutration->intolerances = $request->intolerances;
        $nutration->activity_level = $request->activity_level;
        $nutration->dont_like = $request->dont_like;

        $nutration->save();


        return response()->json([
            'success' => true,
            'message' => 'Nutrition stored successfully',
            'data' => $nutration,
        ]);
    }

    public function mynutration()
    {
        $user = auth('api')->user(); // get User model, not just ID


        return response()->json([
            'success' => true,
            'message' => 'Nutrition retrive successfully',
            'data' => $user->nutration()->select('id','goals_for','dietary_preferences','intolerances','activity_level','dont_like')->get(),
        ]);
    }
    public function recipes()
    {
        $recipes = Recipe::where('user_id',auth('api')->id())->get(); // get User model, not just ID


        return response()->json([
            'success' => true,
            'message' => 'Recipes retrive successfully',
            'data' => $recipes->map(function ($r){
                return [
                    'meal' =>$r->meal,
                    'description' =>$r->description,
                    'protein_g' =>$r->protein_g,
                    'time_min' =>$r->time_min,
                    'calories' =>$r->calories,
                    'image_url' =>$r->image_url ? url($r->image_url) : null,
                ];
            }),
        ]);
    }
}
