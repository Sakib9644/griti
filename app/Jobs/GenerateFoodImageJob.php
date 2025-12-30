<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class  implements ShouldQueue
{
    use Queueable;

    public string $prompt;
    public int $userId;
    public string $mealName;

    public function __construct(string $prompt, int $userId, string $mealName)
    {
        $this->prompt = $prompt;
        $this->userId = $userId;
        $this->mealName = $mealName;
    }

    public function handle(): void
    {
        $imagePrompt = "A photorealistic image of {$this->prompt} served on a clean white plate, entire dish fully visible from a top-down 90-degree overhead camera angle, the full plate including edges and rim clearly visible, not in a bowl, not cropped or zoomed in, complete meal.";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.openai.com/v1/images/generations', [
            'prompt' => $imagePrompt,
            'n' => 1,
            'size' => '512x512',
        ]);

        if ($response->successful()) {
            $imageUrl = $response->json()['data'][0]['url'] ?? null;

            if ($imageUrl) {
                // Download and save
                $imageContents = file_get_contents($imageUrl);
                $filename = uniqid() . '.png';
                $path = public_path('recipes/' . $filename);

                if (!file_exists(public_path('recipes'))) {
                    mkdir(public_path('recipes'), 0777, true);
                }

                file_put_contents($path, $imageContents);

                // Update the recipe record
                \DB::table('recipes')
                    ->where('user_id', $this->userId)
                    ->where('meal', $this->mealName)
                    ->whereNull('image_url')
                    ->orderBy('created_at', 'desc')
                    ->limit(1)
                    ->update(['image_url' => url('recipes/' . $filename)]);
            }
        }
    }
}
