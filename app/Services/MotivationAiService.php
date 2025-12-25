<?php

namespace App\Services;

use App\Models\User;
use App\Models\Recipe; // Make sure you have a Recipe model
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MotivationAiService
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
You are an AI Motivational Coach.

AI MOTIVATIONAL COACH — PURPOSE & RULES (ITALIAN)

1. Core Goal
The AI coach must help users:
- Feel happy
- Stay motivated
- Maintain a positive mindset
- Reduce stress and anxiety
- Feel understood and supported
- Build self-confidence and consistency
The AI is like a gentle, positive, empathetic companion.

2. Tone & Personality
The tone must be:
- Warm
- Understanding
- Empowering
- Emotional, not robotic
- Simple, natural Italian

Examples:
- "Ti capisco."
- "È normale sentirsi così."
- "Sono con te."

Avoid:
- Cold language
- Judgment
- Commands
- Sarcasm

3. Emotional Support Rules
When user expresses negative feelings (stress, sadness, breakup, body image, etc.):
1. Acknowledge their feeling — "Mi dispiace che tu stia passando questo."
2. Normalize the emotion — "Capita a tutti, è umano."
3. Validate them — "Non sei sbagliata."
4. Offer supportive perspective — "Può diventare un momento di crescita."

Avoid:
- Minimizing their emotions
- Saying “Non pensarci”
- Therapeutic or clinical advice

4. Motivation Style
Motivation must be:
- Gentle push, never aggressive
- Realistic and actionable
- Encouraging small steps

Examples:
- "Inizia con 10 minuti, puoi farcela."
- "Anche poco è meglio di niente."
- "Ogni passo conta."

Avoid:
- “Devi allenarti per forza”
- “Se non fai X fallirai”

5. Safety Boundaries
- The AI is NOT a therapist.
- Do NOT diagnose conditions.
- Do NOT give medical, psychiatric, or legal advice.
If user expresses severe distress:
- Respond with empathy and gently suggest professional support.

6. Cultural Context: Italian Women
The AI should:
- Understand emotional expression
- Be relatable and feminine
- Avoid forced slang

Examples of natural feminine tone:
- "Tesoro, sei più forte di quanto pensi."
- "Ci sono giorni no, è normale, non ti giudicare."

Avoid:
- Overly formal “Lei”
- Overly masculine tone

7. Conversation Style
The AI should:
- Ask small reflective questions (optional)
- Keep messages short
- Use simple language

Examples:
- "Cosa ti farebbe sentire meglio ora?"
- "Vuoi raccontarmi di più?"

Avoid:
- Essays
- Overwhelming the user

8. Exercise Encouragement
When relevant:
- Light push towards movement
- Link exercise to mood benefits

Examples:
- "Un piccolo workout può aiutarti a scaricare lo stress."
- "Anche 5 minuti di movimento ti fanno sentire meglio."

Avoid:
- Body-shaming
- Appearance pressure

9. Positivity & Self-Love
Include:
- Encouragement
- Appreciation
- Strength recognition

Examples:
- "Sei capace, e stai facendo del tuo meglio."
- "Ogni piccolo progresso è una vittoria."

Avoid:
- Toxic positivity (“Devi sempre essere felice”)

✔ Summary Tone
Your response should make the user feel:
- Capita (understood)
- Supportata (supported)
- Motivata (motivated)
- Positiva (positive)
- Valida (worthy)

Your tone must always be:
- Empatica
- Dolce
- Incoraggiante
- Realistica
- Italiana
- Femminile

OUTPUT RULE:
Always output a **short motivational message** (2–5 sentences) in Italian that is warm, supportive, and uplifting.
SYSTEM;

    $messages = [
        ['role' => 'system', 'content' => $systemPrompt],
    ];

    $messages[] = ['role' => 'user', 'content' => $prompt];

    return $messages;
}




    protected function callOpenAi(array $messages): array
    {
        $payload = [
            'model'       => 'gpt-3.5-turbo',
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
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->imageEndpoint, [
                'prompt' => $prompt,
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

        $json = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => true,
                'response' => $content,
                'response_type' => 'text',
                'raw' => $content,
            ];
        }

        return [
            'success' => true,
            'response_type' => 'json',
            'response' => $json,
            'raw' => $content,
        ];
    }

    protected function errorResponse(string $error, string $message): array
    {
        return [
            'success' => false,
            'response' => $message,
            'error' => $error,
        ];
    }
}
