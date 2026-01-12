<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FoodHealthCheckerService
{


    protected string $apiKey;
    protected string $chatEndpoint;
    protected string $imageEndpoint;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->chatEndpoint = 'https://api.openai.com/v1/chat/completions';
        $this->imageEndpoint = 'https://api.openai.com/v1/images/generations';
    }

    /**
     * Analyze food product and return health verdict with alternatives
     */
    public function checkFoodHealth(int $userId, array $productData): array
    {


        // Process the food analysis
        $response = $this->analyzeFoodProduct($productData);

        // If successful and has alternatives, generate images for them
        if ($response['success'] && $response['response_type'] === 'json' && !empty($response['response']['alternatives'])) {
            foreach ($response['response']['alternatives'] as &$alternative) {
                if (!empty($alternative['name'])) {
                    // Generate food image
                    $imageUrl = $this->generateFoodImage($alternative['name']);
                    if ($imageUrl) {
                        $alternative['image_url'] = $this->downloadAndSaveImage($imageUrl, 'alternatives');
                    }
                }
            }
            unset($alternative);
        }

        return $response;
    }

    protected function analyzeFoodProduct(array $productData): array
    {
        try {
            $messages = $this->buildAnalysisMessages($productData);
            $response = $this->callOpenAi($messages);
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            Log::error('FoodHealthChecker error: ' . $e->getMessage());
            return $this->errorResponse($e->getMessage(), 'Food analysis failed');
        }
    }

    protected function buildAnalysisMessages(array $productData): array
    {
        $systemPrompt = <<<SYSTEM
You are a Food Health Analyzer for a fitness app targeting Italian women.

LANGUAGE: ALL responses must be in Italian.

YOUR TASK:
Analyze the provided food product and determine if it's healthy or not based on:
1. **Nutritional values**: calories, proteins, fats, saturated fats, sugars, fiber, salt
2. **Ingredients quality**: whole foods vs processed, additives (E-numbers), artificial ingredients
3. **User's goals and preferences**: weight loss, muscle gain, dietary restrictions

HEALTH SCORING RULES:
Score from 0-100 based on:
- **High fiber (>4g)**: +10 points
- **High protein (>10g)**: +10 points
- **Low sugar (<5g per 100g)**: +15 points
- **Low saturated fat (<3g)**: +10 points
- **Low salt (<1g)**: +10 points
- **Whole grain/natural ingredients**: +15 points
- **No/few additives (E-numbers)**: +10 points
- **Good calorie balance (200-300 per serving)**: +10 points

NEGATIVE POINTS:
- **High sugar (>10g)**: -20 points
- **High saturated fat (>5g)**: -15 points
- **Many additives (>3 E-numbers)**: -15 points
- **Ultra-processed (NOVA 4)**: -10 points
- **High salt (>1.5g)**: -10 points

VERDICT:
- Score **60-100**: "Yes, is healthy!" (thumbs up)
- Score **0-59**: "No, is not healthy!" (thumbs down)

OUTPUT FORMAT (MANDATORY JSON):
{
  "product_name": "Product name",
  "brand": "Brand name",
  "score": 83,
  "is_healthy": true,
  "verdict": "Yes, is healthy!" or "No, is not healthy! this will be in italian",
  "reason": "Brief explanation in Italian (why healthy/unhealthy, 1-2 sentences)",
  "details": "It contains refined sugars, saturated fats, additives" (for unhealthy) or positive aspects (for healthy),
  "alternatives": [
    {
      "name": "Oat flakes",
      "category": "Low Calorie",
      "image_url": "",
      "why_better": "Lower calories and more fiber"
    },
    {
      "name": "Pearl spelt",
      "category": "Low Calorie",
      "image_url": "",
      "why_better": "More protein and whole grain"
    }
  ]
}

ALTERNATIVES GUIDELINES:
- Suggest 2-3 healthier food products (NOT recipes, but actual products like "Oat flakes", "Greek yogurt", "Whole wheat pasta")
- Must be realistic alternatives that serve similar purpose
- Should have better nutritional profile
- Add category tag like "Low Calorie", "High Protein", "High Fiber", etc.
- Consider user's dietary preferences and restrictions

CRITICAL RULES:
- ONLY return valid JSON, no explanations outside JSON
- All text in Italian
- Be honest and scientific in scoring
- Base score strictly on nutritional data
SYSTEM;

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Add user context
        if (!empty($context)) {
            $contextText = "User Profile:\n";
            $contextText .= "- Goals: {$context['goals_for']}\n";
            $contextText .= "- Dietary Preferences: {$context['dietary_preferences']}\n";
            $contextText .= "- Intolerances: {$context['intolerances']}\n";
            $contextText .= "- Activity Level: {$context['activity_level']}\n";
            $contextText .= "- Foods Not Liked: {$context['dont_like']}\n";
            $messages[] = ['role' => 'system', 'content' => $contextText];
        }

        // Add product data
        $productJson = json_encode($productData, JSON_PRETTY_PRINT);
        $messages[] = ['role' => 'user', 'content' => "Analyze this product:\n\n" . $productJson];

        return $messages;
    }

    protected function callOpenAi(array $messages): array
    {
        $payload = [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'temperature' => 0.5,
            'max_tokens' => 1500,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(220)->post($this->chatEndpoint, $payload);

        if (!$response->successful()) {
            $error = $response->json()['error']['message'] ?? $response->body();
            throw new \Exception("OpenAI API Error: " . $error);
        }

        return $response->json();
    }

   protected function generateFoodImage(string $foodName): ?string
{
    try {
        $imagePrompt = "A professional product photography of {$foodName}, package visible, clean white background, realistic, high quality";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(60)->post($this->imageEndpoint, [
            'prompt' => $imagePrompt,
            'n' => 1,
            'size' => '256x256', // Smaller image size
        ]);

        if (!$response->successful()) {
            Log::error('Image generation failed: ' . $response->body());
            return null;
        }

        $data = $response->json();
        return $data['data'][0]['url'] ?? null;

    } catch (\Exception $e) {
        Log::error('Image generation error: ' . $e->getMessage());
        return null;
    }
}


    protected function downloadAndSaveImage(string $imageUrl, string $folder = 'alternatives'): ?string
    {
        try {
            $imageContents = file_get_contents($imageUrl);
            $filename = uniqid() . '.png';
            $directory = public_path($folder);

            // Ensure directory exists
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $path = $directory . '/' . $filename;
            file_put_contents($path, $imageContents);

            return url($folder . '/' . $filename);
        } catch (\Exception $e) {
            Log::error('Image download failed: ' . $e->getMessage());
            return null;
        }
    }

    protected function handleApiResponse(array $response): array
    {
        $content = $response['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            return $this->errorResponse('Empty response', 'No content returned from AI');
        }

        // Clean the content
        $cleanContent = trim($content);

        // Remove markdown code blocks
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/s', $cleanContent, $matches)) {
            $cleanContent = trim($matches[1]);
        }

        // Try to parse as JSON
        $json = json_decode($cleanContent, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            return [
                'success' => true,
                'response_type' => 'json',
                'response' => $json,
            ];
        }

        // Try to extract JSON object
        if (preg_match('/\{[\s\S]*\}/s', $cleanContent, $matches)) {
            $json = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                return [
                    'success' => true,
                    'response_type' => 'json',
                    'response' => $json,
                ];
            }
        }

        return $this->errorResponse('Invalid JSON response', 'Could not parse AI response');
    }

    protected function errorResponse(string $error, string $message): array
    {
        return [
            'success' => false,
            'response_type' => 'error',
            'message' => $message,
            'error' => $error,
        ];
    }
}
