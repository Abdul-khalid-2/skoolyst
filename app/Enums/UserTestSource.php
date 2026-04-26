<?php

namespace App\Enums;

enum UserTestSource: string
{
    case TopicTest = 'topic_test';
    case SubjectTest = 'subject_test';
    case MockTest = 'mock_test';
    case Practice = 'practice';
}
