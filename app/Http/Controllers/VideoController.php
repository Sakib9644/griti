<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Theme;
use App\Models\Category;
use App\Models\Circle;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Videolibrary;

class VideoController extends Controller
{
    /**
     * Display a listing of videos.
     */
    public function index()
    {
        $videos = Video::with('theme', 'category', 'circles')->paginate(10);
        return view('backend.layouts.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        $themes = Theme::all();
        $categories = Category::all(); 
        return view('backend.layouts.videos.create', compact('themes', 'categories'));
    }

    /**
     * Store a newly created video.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'theme_id' => 'nullable|exists:themes,id',
                'type' => 'nullable|in:beginner,intermediate,advance',
                'video' => 'nullable|file|mimes:mp4,mov,avi',
                'image' => 'required|file|mimes:jpeg,jpg,png|max:2048',
                'description' => 'nullable|string',
                'calories' => 'nullable|integer',
                'minutes' => 'nullable|integer',
            ]);

            $video = new Video();
            $video->title = $request->title;
            $video->category_id = $request->category_id;
            $video->theme_id = $request->theme_id;
            $video->type = $request->type;
            $video->description = $request->description;
            $video->calories = $request->calories;
            $video->minutes = $request->minutes;
            $video->video = "demo";




            if ($request->hasFile('image')) {
                $video->image = Helper::fileUpload(
                    $request->file('image'),
                    'video_images',
                    time() . '_' . $request->file('image')->getClientOriginalName()
                );
            }

            $video->save();

            if ($request->has('work_out_video_id')) {

                foreach ($request->work_out_video_id as $workout) {

                    $Videolibrary = new Videolibrary();

                    $Videolibrary->video_id = $video->id;
                    $Videolibrary->work_out_video_id = $workout;

                    $Videolibrary->save();
                }
            };

            return redirect()->route('admin.videos.index')->with('t-success', 'Work-list created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('t-error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified video.
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        $themes = Theme::all();
        $categories = Category::all(); // Added
        return view('backend.layouts.videos.edit', compact('video', 'themes', 'categories'));
    }

    /**
     * Update an existing video.
     */
    public function update(Request $request, $id)
    {
        try {
            $video = Video::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'theme_id' => 'nullable|exists:themes,id',
                'type' => 'nullable|in:beginner,intermediate,advance',
                'video' => 'nullable|file|mimes:mp4,mov,avi',
                'image' => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
                'description' => 'nullable|string',
                'calories' => 'nullable|integer',
                'minutes' => 'nullable|integer',
                'work_out_video_id' => 'nullable|array',
                'work_out_video_id.*' => 'integer|exists:workout_videos,id',
            ]);

            $video->title = $request->title;
            $video->category_id = $request->category_id;
            $video->theme_id = $request->theme_id;
            $video->type = $request->type;
            $video->description = $request->description;
            $video->calories = $request->calories;
            $video->minutes = $request->minutes;


            if ($request->hasFile('video')) {
                if ($video->video && file_exists(public_path($video->video))) {
                    Helper::fileDelete(public_path($video->video));
                }
                $video->video = Helper::fileUpload(
                    $request->file('video'),
                    'videos',
                    time() . '_' . $request->file('video')->getClientOriginalName()
                );
            }

            if ($request->hasFile('image')) {
                if ($video->image && file_exists(public_path($video->image))) {
                    Helper::fileDelete(public_path($video->image));
                }
                $video->image = Helper::fileUpload(
                    $request->file('image'),
                    'video_images',
                    time() . '_' . $request->file('image')->getClientOriginalName()
                );
            }

            $video->save();

            Videolibrary::where('video_id', $video->id)->delete();
            if ($request->has('work_out_video_id')) {

                foreach ($request->work_out_video_id as $workoutId) {
                    $library = new Videolibrary();
                    $library->video_id = $video->id;
                    $library->work_out_video_id = $workoutId;
                    $library->save();
                }
            }

            return redirect()->route('admin.videos.index')->with('t-success', 'Work-list updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('t-error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified video.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);

        if ($video->video && file_exists(public_path($video->video))) {
            Helper::fileDelete(public_path($video->video));
        }

        if ($video->image && file_exists(public_path($video->image))) {
            Helper::fileDelete(public_path($video->image));
        }

        $video->delete();

        return redirect()->route('admin.videos.index')->with('t-success', 'Video deleted successfully!');
    }

    /**
     * Circles CRUD
     */
    public function addCircleForm($videoId)
    {
        $video = Video::findOrFail($videoId);
        return view('backend.layouts.circles.create', compact('video'));
    }

    public function storeCircle(Request $request, $videoId)
    {
        $video = Video::findOrFail($videoId);

        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $video->circles()->create([
            'name' => $request->name,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('admin.videos.index')->with('t-success', 'Circle added successfully!');
    }
    public function assignedVideos($id)
    {
        $video = Videolibrary::where('video_id', $id)->get();
        return view('backend.layouts.videos.assgined_videos', compact('video'));
    }
}
