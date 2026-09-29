<?php

namespace App\Mail;

use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminSchoolActivityMail extends Mailable
{
    use Queueable, SerializesModels;

    public School $school;

    /**
     * Activity type: 'registered' (public self-signup), 'created' (admin
     * dashboard), or 'updated' (admin dashboard edit).
     */
    public string $type;

    public function __construct(School $school, string $type)
    {
        $this->school = $school;
        $this->type = in_array($type, ['registered', 'created', 'updated'], true) ? $type : 'created';
    }

    public function build(): self
    {
        $subject = match ($this->type) {
            'registered' => 'New school self-registered on SKOOLYST',
            'updated' => 'School profile updated on SKOOLYST',
            default => 'New school created on SKOOLYST',
        };

        return $this
            ->subject($subject)
            ->view('emails.admin_school_activity');
    }
}
