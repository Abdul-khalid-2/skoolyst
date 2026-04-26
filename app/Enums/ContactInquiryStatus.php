<?php

namespace App\Enums;

enum ContactInquiryStatus: string
{
    case Inbox = 'new';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Closed = 'closed';
}
