<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Circle;
use App\Models\Theme;
use App\Models\Video;
use Symfony\Component\HttpFoundation\Request;

class categoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Category::where('status', 'active');

        if (!empty($search)) {
            $query->where('name', 'like', "%$search%");
        }

        $data = $query->get();

        $workout = $data->map(function ($cat) {
            return [
                'id'       => $cat->id,
                'name'     => $cat->name,
                'image'    => $cat->image,
                // Sum all videos from all themes
                'work_out' => $cat->Theme?->sum(function ($t) {

                    return $t->videos->count();
                }),

            ];
        });

        return Helper::jsonResponse(true, 'Category Retrieved Successfully', 200,  $workout);
    }


    public function themes()
    {

        $data = Theme::select('id', 'name', 'image', 'type', 'category_id')->get();
        return Helper::jsonResponse(true, 'Themes Retrive Successfully', 200, $data);
    }
    public function category_wise_themes(Request $request)
    {
        // Get query parameters
        $categoryId = $request->query('category_id'); // ?category_id=12
        $type       = $request->query('type');        // ?type=beginner
        $name       = $request->query('name');        // ?name=Yoga

        $query = Theme::query();

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($type)) {
            // Partial match, case-insensitive for type
            $query->whereRaw('LOWER(type) LIKE ?', ['%' . strtolower($type) . '%']);
        }

        if (!empty($name)) {
            // Partial match, case-insensitive for name
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%']);
        }

        // Execute the query, select only needed fields
        $data = $query->select('id', 'name', 'image', 'type', 'category_id')->get();

        return Helper::jsonResponse(true, 'Themes Retrieved Successfully', 200, $data);
    }

    public function themes_wise_video($id)
    {
        // Get all videos for the theme
        $videos = Video::select('id', 'minutes', 'calories', 'image', 'title', 'theme_id')
            ->where('theme_id', $id)
            ->get();

        // Get theme details
        $theme = Theme::find($id);

        if (!$theme) {
            return response()->json([
                'status'  => false,
                'message' => 'Theme not found',
            ], 404);
        }

        // Prepare summary (outside data array)




        // Map videos to clean array (optional)
        $data = $videos->map(function ($v) {
            return [
                'id'       => $v->id,
                'title'    => $v->title,
                'minutes'  => $v->minutes,
                'calories' => $v->calories,
                'image'    => $v->image,
                'theme_id' => $v->theme_id,
            ];
        });

        // Return response
        return response()->json([
            'status'  => true,
            'message' => 'Theme videos retrieved successfully',

            'theme_title'    => $theme->name,
            'theme_image'    => $theme->image,
            'total_workouts' => $videos->count(),
            'data'    => $data,     // videos array remains unchanged
        ]);
    }

    public function circels($id)
    {

        $data = Circle::select('id', 'video_id', 'image', 'description', 'title', 'video_id')->where('video_id', $id)->first();
        return Helper::jsonResponse(true, 'circels Retrive Successfully', 200, $data);
    }
}
