<?php

namespace App\Jobs;

use App\Models\Recipe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RecicepestoreJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $imageUrl;
    protected array $recipe;
    protected int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $imageUrl, array $recipe, int $userId)
    {
        $this->imageUrl = $imageUrl;
        $this->recipe = $recipe;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if (!$this->imageUrl) {
                return;
            }

            /* ---------- CREATE DIRECTORY ---------- */
            $dir = public_path('recipes');
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            /* ---------- DOWNLOAD IMAGE ---------- */
            $imageContents = file_get_contents($this->imageUrl);
            if (!$imageContents) {
                Log::error('Image download failed');
                return;
            }

            $filename = uniqid() . '.png';
            file_put_contents($dir . '/' . $filename, $imageContents);

            $imagePath = url('recipes/' . $filename);

            /* ---------- SAVE RECIPE ---------- */
            Recipe::create([
                'user_id'     => $this->userId,
                'meal'        => $this->recipe['meal'] ?? '',
                'description' => $this->recipe['description'] ?? '',
                'ingredients' => $this->recipe['ingredients'] ?? [],
                'steps'       => $this->recipe['steps'] ?? [],
                'time_min'    => $this->recipe['time_min'] ?? 0,
                'calories'    => $this->recipe['calories'] ?? 0,
                'protein_g'   => $this->recipe['protein_g'] ?? 0,
                'image_url'   => $imagePath,
            ]);
        } catch (\Throwable $e) {
            Log::error('RecipeStoreJob failed: ' . $e->getMessage());
        }
    }
}
