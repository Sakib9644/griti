<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Theme;
use App\Models\Circle;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class VideoController extends Controller
{
    /**
     * Display a listing of videos.
     */
    public function index()
    {
        $videos = Video::with('theme', 'circles')->get();
        return view('backend.layouts.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        $themes = Theme::all();
        return view('backend.layouts.videos.create', compact('themes'));
    }

    /**
     * Store a newly created video.
     */
   // Store a newly created video
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'theme_id' => 'required|exists:themes,id',
        'video' => 'required',
        'image' => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
        'description' => 'nullable|string',
        'calories' => 'nullable|integer',
        'minutes' => 'nullable|integer',
    ]);

    $video = new Video();
    $video->title = $request->title;
    $video->theme_id = $request->theme_id;
    $video->description = $request->description;
    $video->calories = $request->calories;
    $video->minutes = $request->minutes;

    if ($request->hasFile('video')) {
        $video->video = Helper::fileUpload(
            $request->file('video'),
            'videos',
            time() . '_' . $request->file('video')->getClientOriginalName()
        );
    }

    if ($request->hasFile('image')) {
        $video->image = Helper::fileUpload(
            $request->file('image'),
            'video_images',
            time() . '_' . $request->file('image')->getClientOriginalName()
        );
    }

    $video->save();

    return redirect()->route('admin.videos.index')->with('t-success', 'Video created successfully!');
}

// Update an existing video
public function update(Request $request, $id)
{
    $video = Video::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'theme_id' => 'required|exists:themes,id',
        'video' => 'nullable|file|mimes:mp4,mov,avi',
        'image' => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
        'description' => 'nullable|string',
        'calories' => 'nullable|integer',
        'minutes' => 'nullable|integer',
    ]);

    $video->title = $request->title;
    $video->theme_id = $request->theme_id;
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

    return redirect()->route('admin.videos.index')->with('t-success', 'Video updated successfully!');
}

    /**
     * Show the form for editing the specified video.
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        $themes = Theme::all();
        return view('backend.layouts.videos.edit', compact('video', 'themes'));
    }

    /**
     * Update the specified video.
     */
    

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





}
