<?php

namespace App\Enums;

enum UserProgressType: string
{
    case TopicRead = 'topic_read';
    case McqPractice = 'mcq_practice';
    case TestCompleted = 'test_completed';
}
