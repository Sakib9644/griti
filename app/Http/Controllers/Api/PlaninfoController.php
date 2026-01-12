<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlaninfoController extends Controller
{
    //

    public function index()
    {
        try {
            $user = Plan::select('id', 'name', 'stripe_product_id', 'stripe_price_id', 'price', 'interval')->get();
            return response()->json([
                'status'     => true,
                'message'    => 'All Plans',
                'code'       => 200,
                'data'       => $user,
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back();
        }
    }
    public function index2()
    {
        try {
            $plans = Plan::select('id', 'name', 'stripe_product_id', 'stripe_price_id', 'price', 'interval')
                ->where('name', '!=', 'Web')
                ->get()
                ->map(function ($plan) {
                    $plan->interval = strtoupper($plan->interval) . 'LY'; // convert to uppercase and add LY
                    return $plan;
                });

            return response()->json([
                'status'  => true,
                'message' => 'All Plans',
                'code'    => 200,
                'data'    => $plans,
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'code'    => 500,
            ], 500);
        }
    }
}
