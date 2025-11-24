<?php

namespace App\Http\Controllers;

use App\Models\WorkoutVideo;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\WorkoutVideos;

class WorkoutVideosController extends Controller
{
    /**
     * Display a listing of workout videos.
     */
    public function index()
    {
        $workoutVideos = WorkoutVideos::with('video')->get();
        return view('backend.layouts.workout_videos.index', compact('workoutVideos'));
    }

    /**
     * Show the form for creating a new workout video.
     */
    public function create()
    {
        $videos = Video::all();
        return view('backend.layouts.workout_videos.create', compact('videos'));
    }

    /**
     * Store a newly created workout video.
     */
    public function store(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|file|mimes:jpeg,jpg,png|max:2048',
            'videos' => 'required|file|mimes:mp4,mov,avi',
            'seconds' => 'required|integer',
            'descriptions' => 'nullable|string',
        ]);

        $workout = new WorkoutVideos();
        $workout->video_id = $request->video_id;
        $workout->title = $request->title;
        $workout->seconds = $request->seconds;
        $workout->descriptions = $request->descriptions;

        if ($request->hasFile('thumbnail')) {
            $workout->thumbnail = Helper::fileUpload(
                $request->file('thumbnail'),
                'workout_thumbnails',
                time() . '_' . $request->file('thumbnail')->getClientOriginalName()
            );
        }

        if ($request->hasFile('videos')) {
            $workout->videos = Helper::fileUpload(
                $request->file('videos'),
                'workout_videos',
                time() . '_' . $request->file('videos')->getClientOriginalName()
            );
        }

        $workout->save();

        return redirect()->route('admin.workout_videos.index')->with('t-success', 'Workout Video created successfully!');
    }

    /**
     * Show the form for editing a workout video.
     */
    public function edit($id)
    {
        $workoutVideo = WorkoutVideos::findOrFail($id);
        $videos = Video::all();
        return view('backend.layouts.workout_videos.edit', compact('workoutVideo', 'videos'));
    }

    /**
     * Update a workout video.
     */
    public function update(Request $request, $id)
    {
        $workout = WorkoutVideos::findOrFail($id);

        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
            'videos' => 'nullable|file|mimes:mp4,mov,avi',
            'seconds' => 'required|integer',
            'descriptions' => 'nullable|string',
        ]);

        $workout->video_id = $request->video_id;
        $workout->title = $request->title;
        $workout->seconds = $request->seconds;
        $workout->descriptions = $request->descriptions;

        if ($request->hasFile('thumbnail')) {
            if ($workout->thumbnail && file_exists(public_path($workout->thumbnail))) {
                Helper::fileDelete(public_path($workout->thumbnail));
            }
            $workout->thumbnail = Helper::fileUpload(
                $request->file('thumbnail'),
                'workout_thumbnails',
                time() . '_' . $request->file('thumbnail')->getClientOriginalName()
            );
        }

        if ($request->hasFile('videos')) {
            if ($workout->videos && file_exists(public_path($workout->videos))) {
                Helper::fileDelete(public_path($workout->videos));
            }
            $workout->videos = Helper::fileUpload(
                $request->file('videos'),
                'workout_videos',
                time() . '_' . $request->file('videos')->getClientOriginalName()
            );
        }

        $workout->save();

        return redirect()->route('admin.workout_videos.index')->with('t-success', 'Workout Video updated successfully!');
    }

    /**
     * Delete a workout video.
     */
    public function destroy($id)
    {
        $workout = WorkoutVideos::findOrFail($id);

        if ($workout->thumbnail && file_exists(public_path($workout->thumbnail))) {
            Helper::fileDelete(public_path($workout->thumbnail));
        }

        if ($workout->videos && file_exists(public_path($workout->videos))) {
            Helper::fileDelete(public_path($workout->videos));
        }

        $workout->delete();

        return redirect()->route('admin.workout_videos.index')->with('t-success', 'Workout Video deleted successfully!');
    }
}
