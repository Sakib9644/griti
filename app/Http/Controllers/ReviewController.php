<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\CReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // List all reviews
    public function index()
    {
        $reviews = CReview::latest()->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    // Show create form
    public function create()
    {
        return view('reviews.create');
    }

    // Store new review
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rating' => 'required|integer|between:1,5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = Helper::fileUpload(
                $request->file('image'),
                'reviews',
                time() . '_' . getFileName($request->file('image'))
            );
        }

        CReview::create([
            'title' => $request->title,
            'description' => $request->description,
            'rating' => $request->rating,
            'image' => $imagePath,
        ]);

        return redirect()->route('reviews.index')->with('t-success', 'Review created successfully!');
    }

    // Show edit form
    public function edit(CReview $review)
    {
        return view('reviews.edit', compact('review'));
    }

    // Update review
    public function update(Request $request, CReview $review)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rating' => 'required|integer|between:1,5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($review->image && file_exists(public_path($review->image))) {
                Helper::fileDelete(public_path($review->image));
            }
            $review->image = Helper::fileUpload(
                $request->file('image'),
                'reviews',
                time() . '_' . getFileName($request->file('image'))
            );
        }

        $review->update([
            'title' => $request->title,
            'description' => $request->description,
            'rating' => $request->rating,
            'image' => $review->image,
        ]);

        return redirect()->route('reviews.index')->with('t-success', 'Review updated successfully!');
    }

    // Delete review
    public function destroy(CReview $review)
    {
        // Delete image if exists
        if ($review->image && file_exists(public_path($review->image))) {
            Helper::fileDelete(public_path($review->image));
        }

        $review->delete();

        return redirect()->route('reviews.index')->with('t-success', 'Review deleted successfully!');
    }
}
