<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\ActiveWorkout;
use App\Models\Category;
use App\Models\Circle;
use App\Models\Theme;
use App\Models\Video;
use App\Models\Videolibrary;
use App\Models\WorkoutVideos;
use Illuminate\Container\Attributes\DB;
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
                'work_out' => $cat->Workoutlist->count()


            ];
        });

        return Helper::jsonResponse(true, 'Category Retrieved Successfully', 200,  $workout);
    }



    public function categoryWiseWorkouts($categoryId)
    {
        $videos = Video::select('id', 'title', 'image', 'calories', 'minutes','type')->where('category_id', $categoryId)->get();

        return response()->json([
            'success' => true,
            'message' => 'Workouts retrieved by category successfully',
            'category_name' => Category::find($categoryId)->name,
            'cover_image' => Category::find($categoryId)->image,
            'total_workouts' => $videos->count(),
            'data' => $videos
        ]);
    }

    // 2. Theme-wise workouts
    public function themeWiseWorkouts($themeId)
    {
        $videos = Video::select('id', 'title', 'image', 'calories', 'minutes','type')->where('theme_id', $themeId)->get();

        return response()->json([
            'success' => true,
            'message' => 'Workouts retrieved by theme successfully',
            'category_name' => Theme::find($themeId)->name ?? null,
            'cover_image' => Theme::find($themeId)->image ?? null,
            'total_workouts' => $videos->count(),
            'data' => $videos
        ]);
    }

    public function trainingLevelWiseWorkouts(Request $request)
    {
        $levelId = $request->query('type');

        if (!$levelId) {
            return response()->json([
                'success' => false,
                'message' => 'Training level type is required',
                'data' => []
            ]);
        }

        $videos = Video::select('id', 'title', 'image', 'calories', 'minutes','type')
            ->where('type', $levelId)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Workouts retrieved by training level successfully',
            'category_name' => $levelId ,
            'cover_image' => Null,
            'total_workouts' => $videos->count(),
            'data' => $videos
        ]);
    }


public function workoutWiseVideos($workoutId)
{
    // Fetch videos for a specific workout
    $videos = Videolibrary::where('video_id', $workoutId)->get();

    // Map videos to include workoutVideo and music
    $videoData = $videos->flatMap(function ($v) {
        return $v->workoutVideo()
                 ->select('id', 'title', 'thumbnail', 'seconds', 'descriptions', 'videos')
                 ->get();
    });

    // Fetch total calories, minutes, list_id from the Video model
    $videoSummary = Video::find($workoutId);

    return response()->json([
        'success'   => true,
        'message'   => 'Videos retrieved by workout successfully',
        'total_cal' => $videoSummary->calories ?? null,
        'minutes'   => $videoSummary->minutes ?? null,
        'list_id'   => $videoSummary->id ?? null,
        'data'      => $videoData,
    ]);
}

    public function active_workouts(Request $request)
    {
        $workoutId = $request->list_id;

        // Check if the workout exists
        $video = Video::find($workoutId);
        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'No Workout Videos found',
            ], 404);
        }

        // Check if user already saved this workout
        $existing = ActiveWorkout::where('user_id', auth('api')->id())
            ->where('videos_id', $workoutId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already saved this workout',
            ]);
        }

        // Save the workout
        ActiveWorkout::create([
            'user_id'   => auth('api')->id(),
            'videos_id' => $workoutId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Workout Saved Successfully',
        ]);
    }

    public function work_out_list()
    {
        $activeworkouts = auth('api')->user()->activeworkouts;

        return response()->json([
            'success' => true,
            'message' => 'My Active Workouts Retrieved Successfully',
            'active_workouts' => $activeworkouts->map(function ($workout) {
                return [
                    'id' => $workout->workout_list->id,
                    'title' => $workout->workout_list->title,
                    'image' => $workout->workout_list->image,
                    'calories' => $workout->workout_list->calories,
                    'minutes' => $workout->workout_list->minutes,
                ];
            })
        ]);
    }
}
