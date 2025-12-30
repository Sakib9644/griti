<?php

namespace App\Services;

use App\Models\User;
use App\Models\Recipe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OpenAiChatService
{
    protected string $apiKey;
    protected string $chatEndpoint;
    protected string $imageEndpoint;

    public function __construct()
    {
        $this->apiKey        = env('OPENAI_API_KEY');
        $this->chatEndpoint  = 'https://api.openai.com/v1/chat/completions';
        $this->imageEndpoint = 'https://api.openai.com/v1/images/generations';
    }

    /**
     * Get AI response using nutrition data only, return structured recipes with generated images
     */
    public function getNutritionRecipes(int $userId, string $prompt): array
    {
        $user = User::find($userId);
        if (!$user) {
            return $this->errorResponse("User not found", "Invalid user ID");
        }

        $nutrition = $user->nutration()
            ->select('id', 'goals_for', 'dietary_preferences', 'intolerances', 'activity_level', 'dont_like')
            ->first();

        $context = null;
        if ($nutrition) {
            $context = [
                'goals_for'           => $nutrition->goals_for,
                'dietary_preferences' => $nutrition->dietary_preferences,
                'intolerances'        => $nutrition->intolerances,
                'activity_level'      => $nutrition->activity_level,
                'dont_like'           => $nutrition->dont_like,
            ];
        }

        $response = $this->processRequest($prompt, ['nutrition' => $context]);

        // If JSON response, generate images for each recipe
        if ($response['success'] && $response['response_type'] === 'json') {
            foreach ($response['response'] as &$recipe) {
                if (!empty($recipe['meal'])) {
                    $imageUrl = $this->generateImage($recipe['meal']);
                    if ($imageUrl) {
                        // Download image locally
                        $imageContents = file_get_contents($imageUrl);

                        $filename = uniqid() . '.png';
                        $path = public_path('recipes/' . $filename);

                        // Ensure directory exists
                        if (!file_exists(public_path('recipes'))) {
                            mkdir(public_path('recipes'), 0777, true);
                        }

                        // Save the file in public/recipes
                        file_put_contents($path, $imageContents);

                        // Generate public URL
                        $recipe['image_url'] = url('recipes/' . $filename);
                        // Optional: save to DB
                        Recipe::create([
                            'user_id'    => $userId,
                            'meal'       => $recipe['meal'],
                            'description' => $recipe['description'] ?? '',
                            'ingredients' => json_encode($recipe['ingredients'] ?? []),
                            'steps'      => json_encode($recipe['steps'] ?? []),
                            'time_min'   => $recipe['time_min'] ?? 0,
                            'calories'   => $recipe['calories'] ?? 0,
                            'protein_g'  => $recipe['protein_g'] ?? 0,
                            'image_url'  => $recipe['image_url'] ?? null,
                        ]);
                    }
                }
            }
            unset($recipe);
        }

        return $response;
    }

    protected function processRequest(string $prompt, array $context = []): array
    {
        try {
            $messages = $this->buildMessages($prompt, $context);
            $response = $this->callOpenAi($messages);
            return $this->handleApiResponse($response);
        } catch (\Exception $e) {
            Log::error('OpenAiChatService error: ' . $e->getMessage());
            return $this->errorResponse($e->getMessage(), 'AI request failed');
        }
    }

    protected function buildMessages(string $prompt, array $context = []): array
{
    $systemPrompt = <<<SYSTEM
You are an AI Nutrition Coach designed for a fitness app targeting Italian women.

LANGUAGE RULES:
- ALL user-facing output must be in Italian.
- Use natural, conversational, simple Italian.
- Avoid complex medical or scientific jargon.

PERSONALITY:
- Friendly, positive, supportive, and practical.
- Encouraging but not exaggerated.
- Never use judgment or guilt.

SAFETY RULES:
- Do NOT provide medical diagnoses.
- Do NOT provide clinical/therapeutic advice.
- Do NOT suggest supplements, drugs, or treatments.
- Do NOT promote extreme dieting or rapid weight loss.
- Do NOT encourage disordered eating.
- If asked medical questions, answer:
  "Non posso dare consigli medici, ma posso aiutarti con suggerimenti generali di alimentazione sana."

CULTURE:
- Follow Italian dietary culture and Mediterranean balance.
- Promote realistic habits.

NUTRITION LOGIC:
- Prefer lean protein, vegetables, fiber, whole grains, healthy fats.
- Limit fried foods, added sugars, heavy sauces.
- Every meal must include protein + carbs + healthy fats.
- Avoid restrictive diets or unrealistic fat-loss claims.
- Respect allergies, intolerances, preferences, and goals.
- If user provides ingredients, include them.

RECIPE GENERATION REQUIREMENTS:
For each recipe, you MUST provide:
1. Recipe name
2. Description (in Italian)
3. Ingredients list
4. Step-by-step instructions
5. Estimated time
6. Calories per portion
7. Protein (if possible)
8. Image URL
9. Macronutrients if possible

IMAGE RULES (MANDATORY):
- Dish must be on a CLEAN WHITE PLATE.
- ENTIRE plate fully visible (edges + rim).
- Top-down 90° camera angle.
- NOT zoomed in.
- NOT cropped.
- NOT inside a bowl.
- Must look like a complete meal.

OUTPUT FORMAT (MANDATORY):
You must ALWAYS return valid JSON in this exact structure:

[
  {
    "meal": "Recipe name",
    "description": "Short description",
    "ingredients": ["..."],
    "steps": ["..."],
    "time_min": 15,
    "calories": 320,
    "protein_g": 22,
    "image_url": ""
  }
]

STRICT RULES:
- Base ALL suggestions strictly on the user's nutrition data.
- Never output explanations, internal reasoning, or system text.
- ONLY output the JSON structure above.
SYSTEM;

    $messages = [
        ['role' => 'system', 'content' => $systemPrompt],
    ];

    // Add nutrition context
    $contextText = "User Nutrition Data:\n";
    if (!empty($context['nutrition'])) {
        $n = $context['nutrition'];
        $contextText .= "- Goals: {$n['goals_for']}\n";
        $contextText .= "- Dietary Preferences: {$n['dietary_preferences']}\n";
        $contextText .= "- Intolerances: {$n['intolerances']}\n";
        $contextText .= "- Activity Level: {$n['activity_level']}\n";
        $contextText .= "- Foods Not Liked: {$n['dont_like']}\n";
    } else {
        $contextText .= "- No nutrition data available.\n";
    }

    $messages[] = ['role' => 'system', 'content' => $contextText];
    $messages[] = ['role' => 'user', 'content' => $prompt];

    return $messages;
}


    protected function callOpenAi(array $messages): array
    {

        $payload = [
            'model' => 'gpt-4-turbo',
            'messages'    => $messages,
            'temperature' => 0.7,
            'max_tokens'  => 1500,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->timeout(220)->post($this->chatEndpoint, $payload);

        if (!$response->successful()) {
            $error = $response->json()['error']['message'] ?? $response->body();
            throw new \Exception("OpenAI API Error: " . $error);
        }

        return $response->json();
    }

    protected function generateImage(string $prompt): ?string
    {
        try {
            $imagePrompt = "A photorealistic image of {$prompt} served on a clean white plate, entire dish fully visible from a top-down 90-degree overhead camera angle, the full plate including edges and rim clearly visible, not in a bowl, not cropped or zoomed in, complete meal.";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->imageEndpoint, [
                'prompt' => $imagePrompt,
                'prompt' => $imagePrompt,
                'n' => 1,
                'size' => '512x512',
            ]);

            if (!$response->successful()) {
                Log::error('OpenAI Image API Error: ' . $response->body());
                return null;
            }

            $data = $response->json();
            return $data['data'][0]['url'] ?? null;
        } catch (\Exception $e) {
            Log::error('Image generation failed: ' . $e->getMessage());
            return null;
        }
    }

    protected function handleApiResponse(array $response): array
    {
        $content = $response['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            Log::error('OpenAI API empty content: ' . json_encode($response));
            return $this->errorResponse('No content in response', 'Invalid AI response structure');
        }

        // Clean the content: trim and remove common markdown code blocks (e.g., ```json ... ```)
        $cleanContent = trim($content);
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/s', $cleanContent, $matches)) {
            $cleanContent = trim($matches[1]);
        }

        // First, try to parse the entire cleaned content as JSON
        $json = json_decode($cleanContent, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            return [
                'success' => true,
                'response_type' => 'json',
                'response' => $json,
                'raw' => $content,
            ];
        }

        // If not, try to extract JSON array if embedded
        if (preg_match('/\[[\s\S]*\]/s', $cleanContent, $matches)) {
            $potentialJson = trim($matches[0]);
            $json = json_decode($potentialJson, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                return [
                    'success' => true,
                    'response_type' => 'json',
                    'response' => $json,
                    'raw' => $content,
                ];
            }
        }

        // If not JSON, try to parse as structured recipe text
        $parsedRecipe = $this->parseRecipeText($content);
        if ($parsedRecipe) {
            return [
                'success' => true,
                'response_type' => 'json',
                'response' => [$parsedRecipe],
                'raw' => $content,
            ];
        }

        // If still not structured, treat as plain text
        return [
            'success' => true,
            'response' => $cleanContent,
            'response_type' => 'text',
            'raw' => $content,
        ];
    }

    protected function parseRecipeText(string $content): ?array
    {
        // Remove markdown images and other noise
        $content = preg_replace('/!\[.*?\]\(.*?\)/', '', $content);

        // Find meal name: typically after "recommend a delicious" and "**Name**"
        if (preg_match('/recommend a delicious.*?recipe:\s*\*\*(.+?)\*\*/s', $content, $matches)) {
            $meal = trim($matches[1]);
        } else {
            return null;
        }

        // Description: after recipe name until Ingredients
        if (preg_match('/\*\*' . preg_quote($meal, '/') . '\*\*\s*\n\n(.*?)(?=\n\nIngredients:)/s', $content, $matches)) {
            $description = trim($matches[1]);
        } else {
            $description = '';
        }

        // Ingredients: after "Ingredients:\n" until "Steps:"
        $ingredients = [];
        if (preg_match('/Ingredients:\s*\n(.*?)(?=\n\nSteps:)/s', $content, $matches)) {
            $ingText = trim($matches[1]);
            if (preg_match_all('/-\s*(.+?)(?=\n-|\n\n|$)/m', $ingText, $ingMatches)) {
                $ingredients = array_map('trim', $ingMatches[1]);
            }
        }

        // Steps: after "Steps:\n" until "Estimated time:"
        $steps = [];
        if (preg_match('/Steps:\s*\n(.*?)(?=\n\nEstimated time:)/s', $content, $matches)) {
            $stepsText = trim($matches[1]);
            if (preg_match_all('/\d+\.\s*(.+?)(?=\n\d+|\n\n|$)/m', $stepsText, $stepMatches)) {
                $steps = array_map('trim', $stepMatches[1]);
            }
        }

        // Estimated time
        $time_min = 0;
        if (preg_match('/Estimated time:\s*(\d+)\s*minutes?/', $content, $matches)) {
            $time_min = (int) $matches[1];
        }

        // Calories
        $calories = 0;
        if (preg_match('/Calories:\s*(\d+)/', $content, $matches)) {
            $calories = (int) $matches[1];
        }

        // Protein
        $protein_g = 0;
        if (preg_match('/Protein:\s*(\d+)g/', $content, $matches)) {
            $protein_g = (int) $matches[1];
        }

        return [
            'meal' => $meal,
            'description' => $description,
            'ingredients' => $ingredients,
            'steps' => $steps,
            'time_min' => $time_min,
            'calories' => $calories,
            'protein_g' => $protein_g,
            'image_url' => '',
        ];
    }

    protected function errorResponse(string $error, string $message): array
    {
        return [
            'success' => false,
            'response_type' => 'error',
            'message' => $message,
            'data' => null,
            'error' => $error,
        ];
    }
}


