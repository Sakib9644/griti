<?php

namespace App\Console\Commands;

use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Subscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $today = Carbon::today(); // today’s date

        // Get users whose trial reminder should be sent today
        $users = UserInfo::where('payment_status', 'trial')
            ->get();

        foreach ($users as $user) {
            // Add 2 days to created_at (1 day before trial ends)
            $reminderDate = $user->created_at->copy()->addDays(2);

            if ($today->isSameDay($reminderDate)) {
                $this->info("Sending trial reminder to user ID: {$user->user}");
                Mail::to($user->user->email)->send(new \App\Mail\TrialReminderMail($user->user));
            }
        }

        $this->info('Trial reminder check completed.');
    }
}
