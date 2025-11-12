<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class CircleController extends Controller
{
    /**
     * Display a listing of circles.
     */
    public function index()
    {
        $circles = Circle::with('video')->get();
        return view('backend.layouts.circles.index', compact('circles'));
    }

    /**
     * Show form to create a new circle.
     */
    public function create()
    {
        $videos = Video::all();
        return view('backend.layouts.circles.create', compact('videos'));
    }

    /**
     * Store a newly created circle.
     */
    public function store(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
        ]);

        $data = $request->only(['video_id', 'name', 'title', 'description', 'order']);

        if ($request->hasFile('image')) {
            $data['image'] = Helper::fileUpload(
                $request->file('image'),
                'circle_images',
                time() . '_' . $request->file('image')->getClientOriginalName()
            );
        }

        Circle::create($data);

        return redirect()->route('admin.circles.index')->with('t-success', 'Circle created successfully!');
    }

    /**
     * Show form to edit a circle.
     */
    public function edit($id)
    {
        $circle = Circle::findOrFail($id);
        $videos = Video::all();
        return view('backend.layouts.circles.edit', compact('circle', 'videos'));
    }

    /**
     * Update the specified circle.
     */
    public function update(Request $request, $id)
    {
        $circle = Circle::findOrFail($id);

        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
        ]);

        $data = $request->only(['video_id', 'name', 'title', 'description', 'order']);

        if ($request->hasFile('image')) {
            if ($circle->image && file_exists(public_path($circle->image))) {
                Helper::fileDelete(public_path($circle->image));
            }
            $data['image'] = Helper::fileUpload(
                $request->file('image'),
                'circle_images',
                time() . '_' . $request->file('image')->getClientOriginalName()
            );
        }

        $circle->update($data);

        return redirect()->route('admin.circles.index')->with('t-success', 'Circle updated successfully!');
    }

    /**
     * Remove the specified circle.
     */
    public function destroy($id)
    {
        $circle = Circle::findOrFail($id);

        if ($circle->image && file_exists(public_path($circle->image))) {
            Helper::fileDelete(public_path($circle->image));
        }

        $circle->delete();

        return redirect()->route('admin.circles.index')->with('t-success', 'Circle deleted successfully!');
    }
}
