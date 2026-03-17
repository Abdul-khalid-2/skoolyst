<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminUserActivityMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $type;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $type)
    {
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $subject = $this->type === 'registered'
            ? 'New user registered on SKOOLYST'
            : 'User logged in to SKOOLYST';

        return $this
            ->subject($subject)
            ->view('emails.admin_user_activity');
    }
}