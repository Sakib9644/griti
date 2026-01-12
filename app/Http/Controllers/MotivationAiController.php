<?php

namespace App\Http\Controllers;

use App\Models\ApiHit;
use App\Models\ChatHistory;
use App\Services\MotivationAiService;
use App\Services\OpenAiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MotivationAiController extends Controller
{
    protected MotivationAiService $openAiChatService;

    public function __construct(MotivationAiService $openAiChatService)
    {
        $this->openAiChatService = $openAiChatService;
    }

    public function openAiChat(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth('api')->user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Validate input
            $request->validate([
                'prompt' => 'required|string|max:2000',
                'image' => 'nullable|mimetypes:image/*|max:10240',
            ]);

            $prompt = $request->input('prompt');
            $imagePath = null;

            // Handle optional image upload
            if ($request->hasFile('image')) {
                $publicPath = public_path('images/chat');
                if (!File::exists($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }

                $imageName = 'chat_' . time() . '_' . $user->id . '_' . rand(1000, 9999) . '.' . $request->image->extension();
                $request->image->move($publicPath, $imageName);

                $imagePath = 'images/chat/' . $imageName;
            }

            // Call AI service
            $chatResponse = $this->openAiChatService->getNutritionRecipes($user->id, $prompt);

            // Check for API failure
            if (!$chatResponse['success']) {
                $errorMessage = $chatResponse['error'] ?? ($chatResponse['response'] ?? 'Unknown error');

                Log::error('OpenAI API failed', [
                    'user_id' => $user->id,
                    'prompt' => $prompt,
                    'image_path' => $imagePath,
                    'api_error' => $errorMessage,
                    'raw_response' => $chatResponse
                ]);

                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'error' => 'OpenAI API request failed',
                    'details' => $errorMessage
                ], 500);
            }

            // Optional: Save chat history
            // ChatHistory::create([
            //     'user_id' => $user->id,
            //     'prompt' => $prompt,
            //     'response' => $chatResponse['raw'] ?? $chatResponse['response'],
            //     'response_type' => $chatResponse['response_type'] ?? 'text',
            //     'image_path' => $imagePath,
            // ]);

            DB::commit();

            // Return structured response
            if (($chatResponse['response_type'] ?? 'text') === 'json') {
                return response()->json([
                    'success' => true,
                    'prompt' => $prompt,
                    'data' => $chatResponse['response'],
                    'response_type' => 'json',
                ], 200);
            } else {
                return response()->json([
                    'success' => true,
                    'prompt' => $prompt,
                    'message' => $chatResponse['response'],
                    'response_type' => 'text',
                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OpenAiChatController@openAiChat Exception', [
                'user_id' => $user->id ?? null,
                'prompt' => $request->input('prompt') ?? null,
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
