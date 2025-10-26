<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CReview;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    //

    public function index()
    {
        try {
            $reviews = CReview::select('id', 'title', 'description', 'image', 'rating')->get();

            return response()->json([
                'status'  => true,
                'message' => 'All Reviews',
                'code'    => 200,
                'data'    => $reviews,
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'code'    => 500,
                'data'    => null,
            ], 500);
        }
    }
}
