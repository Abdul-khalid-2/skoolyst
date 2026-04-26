<?php

namespace App\Enums;

enum MockTestMode: string
{
    case Practice = 'practice';
    case Timed = 'timed';
    case Exam = 'exam';
}
