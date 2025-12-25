<?php

namespace App\Http\Controllers;

use App\Models\ApiHit;
use App\Models\ChatHistory;
use App\Services\FoodHealthCheckerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FoodHealthController extends Controller
{
    protected FoodHealthCheckerService $foodHealthChecker;

    public function __construct(FoodHealthCheckerService $foodHealthChecker)
    {
        $this->foodHealthChecker = $foodHealthChecker;
    }

    /**
     * Check if a food product is healthy
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkHealth(Request $request)
{

    set_time_limit(300); // 5 minutes

    try {
        DB::beginTransaction();

        // Validate input
$request->validate([
    'name' => 'required|string',
    'brands' => 'nullable|string',
    'energy_kcal' => 'required|string',
    'fat' => 'required|string',
    'saturated_fat' => 'nullable|string',
    'carbohydrates' => 'nullable|string',
    'sugars' => 'nullable|string',
    'fiber' => 'nullable|string',
    'proteins' => 'nullable|string',
    'salt' => 'nullable|string',
]);


        $productData = $request->all();

        // Call Food Health Checker service
        $analysisResponse = $this->foodHealthChecker->checkFoodHealth(
            auth('api')->id(),
            $productData
        );

        if (!$analysisResponse['success']) {
            $errorMessage = $analysisResponse['error'] ?? ($analysisResponse['message'] ?? 'Unknown error');

            Log::error('Food Health Check failed', [
                'product' => $productData,
                'api_error' => $errorMessage,
                'raw_response' => $analysisResponse
            ]);

            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Food health analysis failed',
                'details' => $errorMessage
            ], 500);
        }

        $data = $analysisResponse['response'];

        DB::commit();

        // Return only necessary fields
        return response()->json([
            'success' => true,
            'message' => 'Food health check completed',
            'data' => [
                'product_name' => $data['product_name'] ?? $productData['name'],
                'image' => $request->image ?? 'no_image',
                'brand' => $data['brand'] ?? $productData['brands'] ?? '',
                'score' => $data['score'] ?? 0,
                'is_healthy' => $data['is_healthy'] ?? 0,
                'verdict' => $data['verdict'] ?? '',
                'reason' => $data['reason'] ?? '',
                'details' => $data['details'] ?? '',
                'alternatives' => $data['alternatives'] ?? []
            ]
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'error' => 'Validation error',
            'details' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('FoodHealthController@checkHealth Exception', [
            'product' => $request->input('product') ?? null,
            'exception_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'error' => 'An exception occurred while processing your request.',
            'details' => $e->getMessage(),
        ], 500);
    }
}

}
