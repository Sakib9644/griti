<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrialReminderMail extends Mailable
{
    use Queueable, SerializesModels;
    

    public $userinfo;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->userinfo = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $trialEndDate = $this->userinfo->user_info->created_at->addDays(3)->toFormattedDateString();

        return $this->subject('Your Trial is Ending Soon')
                    ->view('emails.trial_reminder')
                    ->with([
                        'user' => $this->userinfo,
                        'trialEndDate' => $trialEndDate,
                    ]);
    }
}
