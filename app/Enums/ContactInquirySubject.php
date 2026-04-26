<?php

namespace App\Enums;

enum ContactInquirySubject: string
{
    case Admission = 'admission';
    case Tour = 'tour';
    case General = 'general';
    case Feedback = 'feedback';
    case Other = 'other';
}
