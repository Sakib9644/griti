<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class ThemeController extends Controller
{
    /**
     * Display a listing of themes.
     */
    public function index()
    {
        $themes = Theme::with('category')->get();
        return view('backend.layouts.themes.index', compact('themes'));
    }

    /**
     * Show the form for creating a new theme.
     */
    public function create()
    {
        $categories = Category::all();
        return view('backend.layouts.themes.create', compact('categories'));
    }

    /**
     * Store a newly created theme in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'nullable|string|max:255',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $theme = new Theme();
        $theme->name = $request->name;
        $theme->category_id = $request->category_id ?? Category::first()->id;
        $theme->type = $request->type ?? 'advance';
        $theme->status = 1;

        if ($request->hasFile('image')) {
            $theme->image = Helper::fileUpload(
                $request->file('image'),
                'themes',
                time() . '_' . $request->file('image')->getClientOriginalName()
            );
        }

        $theme->save();

        return redirect()->route('admin.theme.index')->with('t-success', 'Theme created successfully!');
    }

    /**
     * Show the form for editing the specified theme.
     */
    public function edit($id)
    {
        $theme = Theme::findOrFail($id);
        $categories = Category::all();
        return view('backend.layouts.themes.edit', compact('theme', 'categories'));
    }

    /**
     * Update the specified theme in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'nullable|string|max:255',
            'image'       => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        try {
            $theme = Theme::findOrFail($id);
            $theme->name = $request->name;
            $theme->category_id = $request->category_id ?? Category::first()->id;
            $theme->type = $request->type ??  $theme->type;
            $theme->status = $theme->status;

            if ($request->hasFile('image')) {
                if ($theme->image && file_exists(public_path($theme->image))) {
                    Helper::fileDelete(public_path($theme->image));
                }

                $theme->image = Helper::fileUpload(
                    $request->file('image'),
                    'themes',
                    time() . '_' . $request->file('image')->getClientOriginalName()
                );
            }

            $theme->save();

            return redirect()->route('admin.theme.index')->with('t-success', 'Theme updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Theme update failed: ' . $e->getMessage());

            return redirect()->back()->with('t-error', 'Something went wrong while updating the theme.'. $e->getMessage());
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);
        $theme->status = $request->status; 
        $theme->save();

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified theme from storage.
     */
    public function destroy($id)
    {
        $theme = Theme::findOrFail($id);

        if ($theme->image && file_exists(public_path($theme->image))) {
            Helper::fileDelete(public_path($theme->image));
        }

        $theme->delete();

        return redirect()->route('admin.theme.index')->with('t-success', 'Theme deleted successfully!');
    }
}
