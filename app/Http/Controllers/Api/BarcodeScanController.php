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
        $energy = $nutriments['energy-kcal'] ?? null;
        $fat = $nutriments['fat'] ?? null;
        $saturatedFat = $nutriments['saturated-fat'] ?? null;
        $carbs = $nutriments['carbohydrates'] ?? null;
        $sugars = $nutriments['sugars'] ?? null;
        $fiber = $nutriments['fiber'] ?? null;
        $protein = $nutriments['proteins'] ?? null;
        $salt = $nutriments['salt'] ?? null;
        $calcium = $nutriments['calcium'] ?? null;

        // Gather basic info
        $result = [
            'name' => $product['product_name'] ?? 'Unknown',
            'brands' => $product['brands'] ?? 'Unknown',
            'ingredients' => $product['ingredients'] ?? 'Not listed',
            'nutrition_grade' => $product['nutrition_grade'] ?? 'unknown',
            'nova_group' => $product['nova-group'] ?? null,
            'nutriments' => [
                'energy_kcal' => $energy,
                'fat' => $fat,
                'saturated_fat' => $saturatedFat,
                'carbohydrates' => $carbs,
                'sugars' => $sugars,
                'fiber' => $fiber,
                'proteins' => $protein,
                'salt' => $salt,
                'calcium' => $calcium,
            ],
        ];



        return response()->json([
            'status' => true,
            'product' => $result,
        ]);
    }
}
