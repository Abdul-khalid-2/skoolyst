<?php

namespace App\Mail;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SchoolContactInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Inquiry to describe in the email.
     */
    public ContactInquiry $inquiry;

    /**
     * Recipient type: 'school' (notification to the school owner)
     * or 'user' (confirmation to the person who submitted the form).
     */
    public string $recipientType;

    public function __construct(ContactInquiry $inquiry, string $recipientType = 'school')
    {
        $this->inquiry = $inquiry;
        $this->recipientType = in_array($recipientType, ['school', 'user'], true)
            ? $recipientType
            : 'school';
    }

    public function build(): self
    {
        $schoolName = $this->inquiry->school->name ?? 'Skoolyst';

        if ($this->recipientType === 'user') {
            return $this
                ->subject("We received your inquiry to {$schoolName}")
                ->replyTo($this->inquiry->school->email ?? config('mail.from.address'), $schoolName)
                ->view('emails.school_contact_inquiry_user');
        }

        return $this
            ->subject("New contact inquiry — {$this->inquiry->full_subject}")
            ->replyTo($this->inquiry->email, $this->inquiry->name)
            ->view('emails.school_contact_inquiry_school');
    }
}
