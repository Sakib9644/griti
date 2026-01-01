<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\WorkoutVideos;

class MusicController extends Controller
{
    /**
     * Display the list of music for a workout video.
     */
    public function index()
    {
        try {
            $workoutVideo = WorkoutVideos::all();
            // Use paginate instead of all()
            $music = Music::paginate(10); // 10 items per page

            return view('backend.layouts.music.index', compact('music', 'workoutVideo'));
        } catch (\Exception $e) {
            return redirect()->route('admin.music.index')->with('t-error', 'Failed to load music list: ' . $e->getMessage());
        }
    }
    /**
     * Show the form for creating a new music entry.
     */
    public function create()
    {
        try {
            $workoutVideos = WorkoutVideos::all();
            return view('backend.layouts.music.create', compact('workoutVideos'));
        } catch (\Exception $e) {
            return redirect()->route('admin.music.index')->with('t-error', 'Failed to load create form: ' . $e->getMessage());
        }
    }

    /**
     * Store newly uploaded music.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'duration' => 'nullable|string|max:50',
                'music_file' => 'required|file|mimes:mp3,wav,ogg|max:50240',
            ]);

            $music = new Music();
            $music->title = $request->title;
            $music->duration = $request->duration;

            if ($request->hasFile('music_file')) {
                $music->music_file = Helper::fileUpload(
                    $request->file('music_file'),
                    'workout_music',
                    time() . '_' . $request->file('music_file')->getClientOriginalName()
                );
            }

            $music->save();

            return redirect()->route('admin.music.index')->with('t-success', 'Music uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.music.index')
                ->withInput() // preserves old form input
                ->with('t-error', 'Failed to upload music: ' . $e->getMessage());
        }
    }

    /**
     * Show edit page for music.
     */
    public function edit($id)
    {
        try {
            $music = Music::findOrFail($id);
            return view('backend.layouts.music.edit', compact('music'));
        } catch (\Exception $e) {
            return redirect()->route('admin.music.index')->with('t-error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update music.
     */
    public function update(Request $request, $id)
    {
        try {
            $music = Music::findOrFail($id);

            $request->validate([
                'title' => 'nullable|string|max:255',
                'duration' => 'nullable|string|max:50',
                'music_file' => 'nullable|file|mimes:mp3,wav,ogg|max:50240',
            ]);

            $music->title = $request->title;
            $music->duration = $request->duration;

            if ($request->hasFile('music_file')) {
                if ($music->music_file && file_exists(public_path($music->music_file))) {
                    Helper::fileDelete(public_path($music->music_file));
                }

                $music->music_file = Helper::fileUpload(
                    $request->file('music_file'),
                    'workout_music',
                    time() . '_' . $request->file('music_file')->getClientOriginalName()
                );
            }

            $music->save();

            return redirect()->route('admin.music.index')->with('t-success', 'Music updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.music.index')->with('t-error', 'Failed to update music: ' . $e->getMessage());
        }
    }

    /**
     * Delete music file + entry.
     */
    public function destroy($id)
    {
        try {
            $music = Music::findOrFail($id);

            if ($music->music_file && file_exists(public_path($music->music_file))) {
                Helper::fileDelete(public_path($music->music_file));
            }

            $music->delete();

            return redirect()->route('admin.music.index')->with('t-success', 'Music deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.music.index')->with('t-error', 'Failed to delete music: ' . $e->getMessage());
        }
    }

    public function setDefault($id)
{
    try {
        // 1️⃣ Reset previous default
        Music::where('is_default', true)->update(['is_default' => false]);

        // 2️⃣ Set new default
        $music = Music::findOrFail($id);
        $music->is_default = true;
        $music->save();

        return redirect()->route('admin.music.index')
                         ->with('t-success', "Music '{$music->title}' set as default successfully!");
    } catch (\Exception $e) {
        return redirect()->route('admin.music.index')
                         ->with('t-error', 'Failed to set default music: ' . $e->getMessage());
    }
}
}
