<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\MotivationalQuote;
use App\Mail\DailyMotivationMail;
use App\Models\MotivationalQuoute;
use Illuminate\Support\Facades\Mail;

class MotivationQuote extends Command
{
    protected $signature = 'app:motivation-quote';

    protected $description = 'Send daily motivational quote to all users';

  public function handle()
{
    // Get a random active quote
    $quoteModel = MotivationalQuoute::where('status', 'active')
        ->inRandomOrder()
        ->first();

    if (!$quoteModel) {
        $this->error('No active motivational quote found.');
        return;
    }

    $payload = [
        'title' => "Today's Motivation for You",
        'body'  => $quoteModel->quote,
        'icon'  => config('settings.logo'),
    ];

    // Eager load firebase tokens
    $users = User::whereNotNull('email')
        ->with('firebaseTokens')
        ->get();

    foreach ($users as $user) {
        if ($user->firebaseTokens->isEmpty()) {
            continue;
        }

        foreach ($user->firebaseTokens as $firebaseToken) {
            try {
                Helper::sendNotifyMobile($firebaseToken->token, $payload);
            } catch (\Exception $e) {
                \Log::error('Firebase notification failed', [
                    'user_id' => $user->id,
                    'token'   => $firebaseToken->token,
                    'error'   => $e->getMessage(),
                ]);
            }
        }
    }

    $this->info('Daily motivational quote sent successfully.');
}

}
