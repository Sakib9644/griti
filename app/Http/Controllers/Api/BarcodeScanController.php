<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BarcodeScanController extends Controller
{
    //

  public function getProduct($barcode)
{
    $response = Http::get("https://world.openfoodfacts.net/api/v2/product/{$barcode}");

    if (!$response->successful()) {
        return response()->json([
            'status' => false,
            'message' => 'Product not found or API error'
        ], 404);
    }

    $data = $response->json();
    $product = $data['product'] ?? null;

    if (!$product) {
        return response()->json([
            'status' => false,
            'message' => 'Product not found'
        ], 404);
    }

    // Gather nutriments with safe defaults
    $nutriments = $product['nutriments'] ?? [];
    $result = [
        'name' => $product['product_name'] ?? 'Unknown',
        'brands' => $product['brands'] ?? 'Unknown',
        'image' => $data['product']['selected_images']['front']['display']['ar'] ?? $data['product']['image_front_small_url'],
        'nutriments' => [
            'energy_kcal' => $nutriments['energy-kcal'] ?? 0,
            'fat' => $nutriments['fat'] ?? 0,
            'saturated_fat' => $nutriments['saturated-fat'] ?? 0,
            'carbohydrates' => $nutriments['carbohydrates'] ?? 0,
            'sugars' => $nutriments['sugars'] ?? 0,
            'fiber' => $nutriments['fiber'] ?? 0,
            'proteins' => $nutriments['proteins'] ?? 0,
            'salt' => $nutriments['salt'] ?? 0,
        ],
    ];

    return response()->json([
        'status' => true,
        'message' => "Food Info Retrive Successfully",
        'data' => $result,
    ]);
}

}
