<?php

namespace App\Http\Controllers;

use App\Models\WorkoutVideo;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\WorkoutVideos;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTables;

class WorkoutVideosController extends Controller
{
    /**
     * Display a listing of workout videos.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {


         $data = WorkoutVideos::select('id', 'title', 'thumbnail', 'videos', 'seconds', 'descriptions')
                    ->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('thumbnail', function ($row) {
                    if ($row->thumbnail) {
                        return '<img src="' . asset($row->thumbnail) . '" width="60">';
                    }
                    return '<span class="text-muted">No Image</span>';
                })

                ->editColumn('videos', function ($row) {
                    if ($row->videos) {
                        return '<video width="200" controls>
                                <source src="' . asset($row->videos) . '" type="video/mp4">
                            </video>';
                    }
                    return '<span class="text-muted">No Video</span>';
                })

                ->editColumn('descriptions', function ($row) {
                    return $row->descriptions;
                })

                ->addColumn('actions', function ($row) {
                    $edit = route('admin.workout_videos.edit', $row->id);
                    $delete = route('admin.workout_videos.destroy', $row->id);

                    return '
                    <a href="' . $edit . '" class="btn btn-sm btn-info mb-1">Edit</a>
                    <form action="' . $delete . '" method="POST" style="display:inline-block;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm(\'Are you sure you want to delete this workout video?\')">
                            Delete
                        </button>
                    </form>
                ';
                })

                ->rawColumns(['thumbnail', 'videos', 'descriptions', 'actions']) // allow HTML
                ->make(true);
        }

        return view('backend.layouts.workout_videos.index');
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
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|file|mimes:jpeg,jpg,png',
            'videos' => 'required|file|mimes:mp4,mov,avi',
            'seconds' => 'required|integer',
            'descriptions' => 'required|string',
        ]);

        $workout = new WorkoutVideos();
        $workout->video_id = $request->video_id ?? Video::first()->id;
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
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|file|mimes:jpeg,jpg,png',
            'videos' => 'nullable|file|mimes:mp4,mov,avi',
            'seconds' => 'required|integer',
            'descriptions' => 'nullable|string',
        ]);

        $workout->video_id = $request->video_id ??  Video::first()->id;
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
