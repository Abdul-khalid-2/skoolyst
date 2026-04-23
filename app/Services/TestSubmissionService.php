<?php

namespace App\Services;

use App\DTOs\TestSubmissionDTO;
use App\Models\Mcq;
use App\Models\Topic;
use App\Models\Subject;
use App\Models\TestType;
use App\Models\UserTestAttempt;
use App\Models\UserMcqAnswer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestSubmissionService
{
    public function storeSubmission(TestSubmissionDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            // Get MCQs based on topic or subject
            $mcqs = $this->getMcqs($dto);
            
            if ($mcqs->isEmpty()) {
                throw new \Exception('No MCQs found for the specified criteria');
            }

            // Process answers and calculate results
            $processedData = $this->processAnswers($mcqs, $dto->answers);
            
            // Get topic and subject models
            $subject = Subject::findOrFail($dto->getSubjectId());
            $topic = $dto->hasTopic() ? Topic::find($dto->getTopicId()) : null;
            $testType = $dto->test_type_id ? TestType::find($dto->test_type_id) : null;

            // Save test attempt if user is logged in
            $attempt = null;
            if (auth()->check()) {
                $attempt = $this->saveTestAttempt($dto, $topic, $subject, $testType, $mcqs, $processedData);
                $this->saveIndividualAnswers($attempt, $processedData['results'], $topic);
            }

            // Prepare and return results
            return $this->prepareResults($dto, $topic, $subject, $mcqs, $processedData, $attempt);
        });
    }

    private function getMcqs(TestSubmissionDTO $dto)
    {
        $query = Mcq::where('status', 'published');
        
        if ($dto->hasTopic()) {
            // Topic-specific test
            $query->where('topic_id', $dto->getTopicId());
        } else {
            // Subject-wide test (no specific topic)
            $query->where('subject_id', $dto->getSubjectId());
        }

        // Filter by test type if provided
        if ($dto->test_type_id) {
            $query->whereHas('testTypes', function ($q) use ($dto) {
                $q->where('test_type_id', $dto->test_type_id);
            });
        }

        return $query->get();
    }

    private function processAnswers($mcqs, array $userAnswers): array
    {
        $results = [];
        $totalMarks = 0;
        $obtainedMarks = 0;
        $negativeMarksObtained = 0;
        $correctCount = 0;
        $wrongCount = 0;

        foreach ($mcqs as $mcq) {
            $userAnswer = $userAnswers[$mcq->id] ?? null;
            $correctAnswers = json_decode($mcq->correct_answers, true);
            $isCorrect = false;

            $totalMarks += $mcq->marks;

            if ($userAnswer !== null && $userAnswer !== '') {
                // Check if answer is correct
                if (is_array($userAnswer)) {
                    sort($userAnswer);
                    sort($correctAnswers);
                    $isCorrect = $userAnswer == $correctAnswers;
                } else {
                    $isCorrect = in_array($userAnswer, $correctAnswers);
                }

                if ($isCorrect) {
                    $correctCount++;
                    $obtainedMarks += $mcq->marks;
                } else {
                    $wrongCount++;
                    $negativeMarks = $mcq->negative_marks ?? 0;
                    $obtainedMarks -= $negativeMarks;
                    $negativeMarksObtained += $negativeMarks;
                }
            }

            $results[] = [
                'mcq' => $mcq,
                'user_answer' => $userAnswer,
                'correct_answers' => $correctAnswers,
                'is_correct' => $isCorrect,
                'marks_obtained' => $isCorrect ? $mcq->marks : -($mcq->negative_marks ?? 0)
            ];
        }

        $attemptedCount = count(array_filter($userAnswers, function($value) {
            return $value !== null && $value !== '';
        }));

        return [
            'results' => $results,
            'total_marks' => $totalMarks,
            'obtained_marks' => max(0, $obtainedMarks),
            'negative_marks_obtained' => $negativeMarksObtained,
            'correct_count' => $correctCount,
            'wrong_count' => $wrongCount,
            'attempted_count' => $attemptedCount,
            'accuracy' => $attemptedCount > 0 ? round(($correctCount / $attemptedCount) * 100, 2) : 0
        ];
    }

    private function saveTestAttempt(TestSubmissionDTO $dto, $topic, $subject, $testType, $mcqs, array $processedData): UserTestAttempt
    {
        $percentage = $processedData['total_marks'] > 0 
            ? ($processedData['obtained_marks'] / $processedData['total_marks']) * 100 
            : 0;

        return UserTestAttempt::create([
            'uuid' => Str::uuid(),
            'user_id' => auth()->id(),
            'topic_id' => $topic?->id,
            'subject_id' => $subject->id,
            'test_type_id' => $testType?->id,
            'test_source' => $dto->hasTopic() ? 'topic_test' : 'subject_test',
            'time_taken_seconds' => $dto->time_taken ?? 0,
            'total_questions' => $mcqs->count(),
            'attempted_questions' => $processedData['attempted_count'],
            'correct_answers' => $processedData['correct_count'],
            'wrong_answers' => $processedData['wrong_count'],
            'skipped_questions' => $mcqs->count() - $processedData['attempted_count'],
            'total_marks' => $processedData['total_marks'],
            'obtained_marks' => $processedData['obtained_marks'],
            'negative_marks_obtained' => $processedData['negative_marks_obtained'],
            'percentage' => round($percentage, 2),
            'accuracy' => $processedData['accuracy'],
            'answers_data' => json_encode($dto->answers),
            'status' => 'completed',
            'completed_at' => now(),
            'submitted_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    private function saveIndividualAnswers(UserTestAttempt $attempt, array $results, $topic): void
    {
        foreach ($results as $result) {
            if ($result['user_answer'] !== null && $result['user_answer'] !== '') {
                UserMcqAnswer::create([
                    'user_id' => auth()->id(),
                    'mcq_id' => $result['mcq']->id,
                    'test_attempt_id' => $attempt->id,
                    'topic_id' => $topic?->id,
                    'selected_answers' => json_encode($result['user_answer']),
                    'is_correct' => $result['is_correct'],
                    'time_taken_seconds' => 0, // You can calculate this if you track per-question time
                    'answered_at' => now()
                ]);
            }
        }
    }

    private function prepareResults(TestSubmissionDTO $dto, $topic, $subject, $mcqs, array $processedData, $attempt): array
    {
        return [
            'topic' => $topic,
            'subject' => $subject,
            'results' => $processedData['results'],
            'total_questions' => $mcqs->count(),
            'attempted' => $processedData['attempted_count'],
            'correct' => $processedData['correct_count'],
            'wrong' => $processedData['wrong_count'],
            'skipped' => $mcqs->count() - $processedData['attempted_count'],
            'total_marks' => $processedData['total_marks'],
            'obtained_marks' => $processedData['obtained_marks'],
            'negative_marks' => $processedData['negative_marks_obtained'],
            'percentage' => $processedData['total_marks'] > 0 
                ? round(($processedData['obtained_marks'] / $processedData['total_marks']) * 100, 2) 
                : 0,
            'accuracy' => $processedData['accuracy'],
            'attempt_uuid' => $attempt?->uuid,
            'test_type_id' => $dto->test_type_id,
            'time_taken' => $dto->time_taken,
            'completed_at' => now()->toDateTimeString(),
        ];
    }
}