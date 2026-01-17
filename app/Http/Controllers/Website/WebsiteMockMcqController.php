<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\MockTest;
use App\Models\TestType;
use App\Models\UserMcqAnswer;
use App\Models\UserTestAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WebsiteMockMcqController extends Controller
{
    // Mock Tests listing page
    public function mockTests(Request $request)
    {
        $mockTests = MockTest::with(['testType', 'questions'])
            ->where('status', 'published')
            ->when($request->filled('test_type'), function ($query) use ($request) {
                $query->whereHas('testType', function ($q) use ($request) {
                    $q->where('slug', $request->test_type);
                });
            })
            ->when($request->filled('mode'), function ($query) use ($request) {
                $query->where('test_mode', $request->mode);
            })
            ->when($request->filled('is_free'), function ($query) use ($request) {
                $query->where('is_free', $request->is_free);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $testTypes = TestType::where('status', 'active')
            ->withCount(['mockTests' => function ($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('sort_order')
            ->get();

        // Get user's test statistics if logged in
        $userStats = null;
        if (Auth::check()) {
            $userStats = [
                'total_attempts' => UserTestAttempt::where('user_id', Auth::id())->count(),
                'total_passed' => UserTestAttempt::where('user_id', Auth::id())
                    ->where('result_status', 'passed')
                    ->count(),
                'average_score' => UserTestAttempt::where('user_id', Auth::id())
                    ->where('status', 'completed')
                    ->avg('percentage') ?? 0,
            ];
        }

        return view('website.mcqs_system.mock-tests.index', compact(
            'mockTests',
            'testTypes',
            'userStats'
        ));
    }

    // Mock Test detail page
    public function mockTestDetail(MockTest $mockTest)
    {
        if ($mockTest->status !== 'published') {
            abort(404);
        }

        $mockTest->load(['testType', 'questions.mcq.subject', 'questions.mcq.topic']);

        // Decode options for each question to show preview
        foreach ($mockTest->questions as $question) {
            if (is_string($question->mcq->options)) {
                $question->mcq->options = json_decode($question->mcq->options, true);
            }
        }

        // Get user's previous attempts
        $previousAttempts = null;
        $hasActiveAttempt = false;
        $activeAttempt = null;

        if (Auth::check()) {
            $previousAttempts = UserTestAttempt::where('user_id', Auth::id())
                ->where('mock_test_id', $mockTest->id)
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Check for active attempt
            $activeAttempt = UserTestAttempt::where('user_id', Auth::id())
                ->where('mock_test_id', $mockTest->id)
                ->where('status', 'in_progress')
                ->first();

            $hasActiveAttempt = $activeAttempt !== null;
        }

        // Calculate time stats
        $timeStats = [
            'hours' => floor($mockTest->total_time_minutes / 60),
            'minutes' => $mockTest->total_time_minutes % 60,
        ];

        return view('website.mcqs_system.mock-tests.detail', compact(
            'mockTest',
            'previousAttempts',
            'hasActiveAttempt',
            'activeAttempt',
            'timeStats'
        ));
    }

    // Start mock test
    public function startMockTest(MockTest $mockTest, Request $request)
    {
        if ($mockTest->status !== 'published') {
            abort(404);
        }

        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to start the test.');
        }

        // Check if test is free or user has access
        if (!$mockTest->is_free) {
            // Here you can add logic to check if user has purchased/accessed the test
            // For now, we'll allow if logged in
        }

        // Check max attempts
        if ($mockTest->max_attempts) {
            $attemptCount = UserTestAttempt::where('user_id', Auth::id())
                ->where('mock_test_id', $mockTest->id)
                ->count();

            if ($attemptCount >= $mockTest->max_attempts) {
                return back()->with('error', 'You have reached the maximum attempts for this test.');
            }
        }

        // Check if user has an active attempt
        $activeAttempt = UserTestAttempt::where('user_id', Auth::id())
            ->where('mock_test_id', $mockTest->id)
            ->where('status', 'in_progress')
            ->first();

        if ($activeAttempt) {
            return redirect()->route('website.mcqs.take-test', $activeAttempt->uuid);
        }

        // Create new test attempt
        $attempt = UserTestAttempt::create([
            'uuid' => Str::uuid(),
            'user_id' => Auth::id(),
            'mock_test_id' => $mockTest->id,
            'started_at' => now(),
            'total_questions' => $mockTest->total_questions,
            'total_possible_marks' => $mockTest->total_marks,
            'remaining_time_seconds' => $mockTest->total_time_minutes * 60,
            'status' => 'in_progress'
        ]);

        return redirect()->route('website.mcqs.take-test', $attempt->uuid);
    }

    // Take test page
    public function takeTest(UserTestAttempt $attempt)
    {
        if (Auth::id() !== $attempt->user_id) {
            abort(403);
        }

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('website.mcqs.test-result', $attempt->uuid);
        }

        $attempt->load(['mockTest.testType', 'mockTest.questions.mcq']);

        // Get questions with options
        $questions = $attempt->mockTest->questions->map(function ($question) {
            $mcq = $question->mcq;

            // Decode options
            if (is_string($mcq->options)) {
                $mcq->options = json_decode($mcq->options, true);
            }

            // Hide correct answers
            unset($mcq->correct_answers);

            return [
                'id' => $question->id,
                'mcq_id' => $mcq->id,
                'question_number' => $question->question_number,
                'question' => $mcq->question,
                'options' => $mcq->options,
                'question_type' => $mcq->question_type,
                'marks' => $question->marks,
                'negative_marks' => $question->negative_marks,
                'time_limit_seconds' => $question->time_limit_seconds,
                'explanation' => $mcq->explanation,
                'hint' => $mcq->hint,
            ];
        });

        // Get saved answers if any
        $savedAnswers = UserMcqAnswer::where('test_attempt_id', $attempt->id)
            ->get()
            ->keyBy('mcq_id');

        // Calculate remaining time
        $elapsedSeconds = now()->diffInSeconds($attempt->started_at);
        $remainingSeconds = max(0, ($attempt->mockTest->total_time_minutes * 60) - $elapsedSeconds);

        return view('website.mcqs_system.mock-tests.take-test', compact(
            'attempt',
            'questions',
            'savedAnswers',
            'remainingSeconds'
        ));
    }

    // Save answer (AJAX)
    public function saveAnswer(Request $request, UserTestAttempt $attempt)
    {
        if (Auth::id() !== $attempt->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'mcq_id' => 'required|exists:mcqs,id',
            'selected_answers' => 'required|array',
            'selected_answers.*' => 'string',
            'time_taken' => 'nullable|integer|min:0'
        ]);

        // Save or update answer
        UserMcqAnswer::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'mcq_id' => $request->mcq_id,
                'test_attempt_id' => $attempt->id
            ],
            [
                'selected_answers' => $request->selected_answers,
                'time_taken_seconds' => $request->time_taken ?? 0,
                'answered_at' => now()
            ]
        );

        // Update attempt's answered count
        $attemptedCount = UserMcqAnswer::where('test_attempt_id', $attempt->id)->count();
        $attempt->update(['attempted_questions' => $attemptedCount]);

        return response()->json(['success' => true]);
    }

    // Submit test
    public function submitTest(Request $request, UserTestAttempt $attempt)
    {
        if (Auth::id() !== $attempt->user_id) {
            abort(403);
        }

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('website.mcqs.test-result', $attempt->uuid);
        }

        // Get all answers for this attempt
        $userAnswers = UserMcqAnswer::where('test_attempt_id', $attempt->id)
            ->with('mcq')
            ->get();

        $totalMarks = 0;
        $correctAnswers = 0;
        $wrongAnswers = 0;
        $answersData = [];
        $questionAnalysis = [];

        foreach ($userAnswers as $userAnswer) {
            $mcq = $userAnswer->mcq;

            // Get correct answers
            if (is_string($mcq->correct_answers)) {
                $correctAnswersList = json_decode($mcq->correct_answers, true);
            } else {
                $correctAnswersList = (array) $mcq->correct_answers;
            }

            // Get selected answers
            $selectedAnswers = $userAnswer->selected_answers;
            if (is_string($selectedAnswers)) {
                $selectedAnswers = json_decode($selectedAnswers, true);
            }

            // Check if answers are correct
            if ($mcq->question_type === 'single') {
                $selected = isset($selectedAnswers[0]) ? trim($selectedAnswers[0]) : '';
                $correct = isset($correctAnswersList[0]) ? trim($correctAnswersList[0]) : '';
                $isCorrect = ($selected === $correct);
            } else {
                $selectedAnswers = array_map('trim', (array)$selectedAnswers);
                $correctAnswersList = array_map('trim', (array)$correctAnswersList);

                sort($selectedAnswers);
                sort($correctAnswersList);

                $isCorrect = ($selectedAnswers === $correctAnswersList);
            }

            // Calculate marks
            $marks = $isCorrect ? $userAnswer->mcq->marks : ($userAnswer->mcq->negative_marks * -1);
            $totalMarks += $marks;

            if ($isCorrect) {
                $correctAnswers++;
            } else {
                $wrongAnswers++;
            }

            // Update user answer with correctness
            $userAnswer->update([
                'is_correct' => $isCorrect,
                'time_taken_seconds' => $userAnswer->time_taken_seconds
            ]);

            // Store in answers data
            $answersData[] = [
                'mcq_id' => $mcq->id,
                'selected_answers' => $selectedAnswers,
                'correct_answers' => $correctAnswersList,
                'is_correct' => $isCorrect,
                'marks' => $marks
            ];

            // Question analysis
            $questionAnalysis[] = [
                'mcq_id' => $mcq->id,
                'difficulty' => $mcq->difficulty_level,
                'topic' => $mcq->topic?->title,
                'subject' => $mcq->subject?->name,
                'is_correct' => $isCorrect,
                'time_taken' => $userAnswer->time_taken_seconds
            ];
        }

        $percentage = ($totalMarks / $attempt->total_possible_marks) * 100;
        $resultStatus = $percentage >= $attempt->mockTest->passing_marks ? 'passed' : 'failed';

        // Calculate time taken
        $timeTakenSeconds = now()->diffInSeconds($attempt->started_at);

        // Update attempt
        $attempt->update([
            'completed_at' => now(),
            'submitted_at' => now(),
            'attempted_questions' => $userAnswers->count(),
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'skipped_questions' => $attempt->total_questions - $userAnswers->count(),
            'total_marks_obtained' => $totalMarks,
            'percentage' => round($percentage, 2),
            'result_status' => $resultStatus,
            'time_taken_seconds' => $timeTakenSeconds,
            'remaining_time_seconds' => max(0, ($attempt->mockTest->total_time_minutes * 60) - $timeTakenSeconds),
            'answers_data' => $answersData,
            'question_analysis' => $questionAnalysis,
            'status' => 'completed'
        ]);

        return redirect()->route('website.mcqs.test-result', $attempt->uuid);
    }


    // Test result page
    public function testResult(UserTestAttempt $attempt)
    {
        if (Auth::id() !== $attempt->user_id) {
            abort(403);
        }

        $attempt->load(['mockTest.testType', 'mockTest.questions.mcq', 'user']);

        // Get detailed answers
        $userAnswers = UserMcqAnswer::where('test_attempt_id', $attempt->id)
            ->with('mcq')
            ->get();

        // Prepare detailed results
        $detailedResults = [];
        foreach ($userAnswers as $answer) {
            $mcq = $answer->mcq;

            if (is_string($mcq->options)) {
                $mcq->options = json_decode($mcq->options, true);
            }

            if (is_string($mcq->correct_answers)) {
                $mcq->correct_answers = json_decode($mcq->correct_answers, true);
            }

            $selectedAnswers = $answer->selected_answers;
            if (is_string($selectedAnswers)) {
                $selectedAnswers = json_decode($selectedAnswers, true);
            }

            $detailedResults[] = [
                'question' => $mcq->question,
                'options' => $mcq->options,
                'selected_answers' => $selectedAnswers,
                'correct_answers' => $mcq->correct_answers,
                'is_correct' => $answer->is_correct,
                'explanation' => $mcq->explanation,
                'marks' => $answer->mcq->marks,
                'time_taken' => $answer->time_taken_seconds,
                'subject' => $mcq->subject?->name,
                'topic' => $mcq->topic?->title,
            ];
        }

        // Calculate statistics
        $statistics = [
            'total_questions' => $attempt->total_questions,
            'attempted' => $attempt->attempted_questions,
            'skipped' => $attempt->skipped_questions,
            'correct' => $attempt->correct_answers,
            'wrong' => $attempt->wrong_answers,
            'accuracy' => $attempt->attempted_questions > 0 ?
                round(($attempt->correct_answers / $attempt->attempted_questions) * 100, 2) : 0,
            'time_taken' => $this->formatTime($attempt->time_taken_seconds),
            'average_time_per_question' => $attempt->attempted_questions > 0 ?
                round($attempt->time_taken_seconds / $attempt->attempted_questions, 2) : 0,
        ];

        // Subject-wise performance
        $subjectPerformance = [];
        foreach ($userAnswers as $answer) {
            $subjectName = $answer->mcq->subject?->name;
            if (!$subjectName) continue;

            if (!isset($subjectPerformance[$subjectName])) {
                $subjectPerformance[$subjectName] = [
                    'total' => 0,
                    'correct' => 0,
                    'marks_obtained' => 0,
                    'total_marks' => 0,
                ];
            }

            $subjectPerformance[$subjectName]['total']++;
            $subjectPerformance[$subjectName]['total_marks'] += $answer->mcq->marks;

            if ($answer->is_correct) {
                $subjectPerformance[$subjectName]['correct']++;
                $subjectPerformance[$subjectName]['marks_obtained'] += $answer->mcq->marks;
            }
        }

        return view('website.mcqs_system.mcqs.test-result', compact(
            'attempt',
            'detailedResults',
            'statistics',
            'subjectPerformance'
        ));
    }

    // Helper method to format time
    private function formatTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        $time = '';
        if ($hours > 0) {
            $time .= $hours . 'h ';
        }
        if ($minutes > 0 || $hours > 0) {
            $time .= $minutes . 'm ';
        }
        $time .= $seconds . 's';

        return trim($time);
    }
}
