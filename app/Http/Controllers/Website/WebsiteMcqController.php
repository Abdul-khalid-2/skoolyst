<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Mcq;
use App\Models\TestType;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\MockTest;
use App\Models\UserTestAttempt;
use App\Models\UserMcqAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WebsiteMcqController extends Controller
{
    // Main MCQs index page
    public function index(Request $request)
    {
        $testTypes = TestType::withCount(['subjects', 'mcqs'])
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        $popularSubjects = Subject::withCount(['mcqs', 'topics'])
            ->where('status', 'active')
            ->orderBy('mcqs_count', 'desc')
            ->limit(8)
            ->get();

        $recentMcqs = Mcq::with(['subject', 'topic'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('website.mcqs_system.mcqs.index', compact('testTypes', 'popularSubjects', 'recentMcqs'));
    }

    // Test Type page (e.g., Entry Test, Job Test)
    public function testType(TestType $testType, Request $request)
    {
        $subjects = Subject::where('test_type_id', $testType->id)
            ->where('status', 'active')
            ->withCount(['mcqs', 'topics'])
            ->orderBy('sort_order')
            ->get();

        $topics = Topic::whereIn('subject_id', $subjects->pluck('id'))
            ->withCount('mcqs')
            ->orderBy('sort_order')
            ->get();

        $featuredMcqs = Mcq::whereHas('subject', function($query) use ($testType) {
                $query->where('test_type_id', $testType->id);
            })
            ->where('status', 'published')
            ->where('is_premium', false)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('website.mcqs_system.mcqs.test-type', compact('testType', 'subjects', 'topics', 'featuredMcqs'));
    }

    // Subject page
    public function subject(TestType $testType, Subject $subject, Request $request)
    {
        $topics = Topic::where('subject_id', $subject->id)
            ->withCount('mcqs')
            ->orderBy('sort_order')
            ->get();

        $mcqs = Mcq::where('subject_id', $subject->id)
            ->where('status', 'published')
            ->with('topic')
            ->when($request->filled('difficulty'), function($query) use ($request) {
                $query->where('difficulty_level', $request->difficulty);
            })
            ->when($request->filled('topic'), function($query) use ($request) {
                $query->where('topic_id', $request->topic);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Ensure options are arrays for each MCQ
        $mcqs->transform(function ($mcq) {
            // Decode options if it's a string
            if (is_string($mcq->options)) {
                $mcq->options = json_decode($mcq->options, true);
            }
            
            // Ensure it's always an array
            if (!is_array($mcq->options)) {
                $mcq->options = [];
            }
            
            return $mcq;
        });

        $difficultyStats = Mcq::where('subject_id', $subject->id)
            ->where('status', 'published')
            ->selectRaw('difficulty_level, COUNT(*) as count')
            ->groupBy('difficulty_level')
            ->get()
            ->keyBy('difficulty_level');

        return view('website.mcqs_system.mcqs.subject', compact('testType', 'subject', 'topics', 'mcqs', 'difficultyStats'));
    }

    // Topic page
    public function topic(TestType $testType, Subject $subject, Topic $topic)
    {
        $mcqs = Mcq::where('topic_id', $topic->id)
            ->where('status', 'published')
            ->with(['subject', 'topic'])
            ->orderBy('difficulty_level')
            ->paginate(15);

        $relatedTopics = Topic::where('subject_id', $subject->id)
            ->where('id', '!=', $topic->id)
            ->withCount('mcqs')
            ->orderBy('sort_order')
            ->limit(5)
            ->get();

        return view('website.mcqs_system.mcqs.topic', compact('testType', 'subject', 'topic', 'mcqs', 'relatedTopics'));
    }

    // Practice individual MCQ
    public function practice(Mcq $mcq)
    {
        if ($mcq->status !== 'published') {
            abort(404);
        }

        // Ensure options and correct_answers are arrays
        if (is_string($mcq->options)) {
            $mcq->options = json_decode($mcq->options, true);
        }
        
        if (is_string($mcq->correct_answers)) {
            $mcq->correct_answers = json_decode($mcq->correct_answers, true);
        }

        // Get similar questions
        $similarMcqs = Mcq::where('subject_id', $mcq->subject_id)
            ->where('topic_id', $mcq->topic_id)
            ->where('id', '!=', $mcq->id)
            ->where('status', 'published')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('website.mcqs_system.mcqs.practice', compact('mcq', 'similarMcqs'));
    }

    public function checkAnswer(Mcq $mcq, Request $request)
    {
        $request->validate([
            'selected_answers' => 'required|array',
            'selected_answers.*' => 'string'
        ]);

        // Ensure selected answers are properly formatted
        $selectedAnswers = (array) $request->selected_answers;
        
        // Ensure correct_answers is an array (handle both string and array)
        if (is_string($mcq->correct_answers)) {
            $correctAnswers = json_decode($mcq->correct_answers, true);
            // If decoding fails, try to handle it as a single answer
            if (json_last_error() !== JSON_ERROR_NONE) {
                $correctAnswers = [$mcq->correct_answers];
            }
        } else {
            $correctAnswers = (array) $mcq->correct_answers;
        }
        
      

        // Check if answers are correct
        // For single choice questions, we need exact match
        // For multiple choice, we need to check all answers
        if ($mcq->question_type === 'single') {
            // For single choice, compare the first selected answer with first correct answer
            $selected = isset($selectedAnswers[0]) ? trim($selectedAnswers[0]) : '';
            $correct = isset($correctAnswers[0]) ? trim($correctAnswers[0]) : '';
            $isCorrect = ($selected === $correct);
        } else {
            // For multiple choice, check if all correct answers are selected and no extra ones
            $selectedAnswers = array_map('trim', $selectedAnswers);
            $correctAnswers = array_map('trim', $correctAnswers);
            
            sort($selectedAnswers);
            sort($correctAnswers);
            
            $isCorrect = ($selectedAnswers === $correctAnswers);
        }

        // Save user's answer if logged in
        if (Auth::check()) {
            try {
                UserMcqAnswer::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'mcq_id' => $mcq->id,
                        'test_attempt_id' => null
                    ],
                    [
                        'selected_answers' => $selectedAnswers,
                        'is_correct' => $isCorrect,
                        'time_taken_seconds' => $request->time_taken ?? 0,
                        'topic_id' => $mcq->topic_id
                    ]
                );
            } catch (\Exception $e) {
                \Log::error('Failed to save user answer:', [
                    'error' => $e->getMessage(),
                    'user_id' => Auth::id(),
                    'mcq_id' => $mcq->id
                ]);
            }
        }

        
        return response()->json([
            'correct' => $isCorrect,
            'correct_answers' => $correctAnswers,
            'explanation' => $mcq->explanation,
            'hint' => $mcq->hint
        ]);
    }

    // Mock Tests listing
    public function mockTests(Request $request)
    {
        $mockTests = MockTest::with(['testType', 'questions'])
            ->where('status', 'published')
            ->when($request->filled('test_type'), function($query) use ($request) {
                $query->whereHas('testType', function($q) use ($request) {
                    $q->where('slug', $request->test_type);
                });
            })
            ->when($request->filled('mode'), function($query) use ($request) {
                $query->where('test_mode', $request->mode);
            })
            ->when($request->filled('is_free'), function($query) use ($request) {
                $query->where('is_free', $request->is_free);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $testTypes = TestType::where('status', 'active')
            ->withCount(['mockTests' => function($query) {
                $query->where('status', 'published');
            }])
            ->get();

        return view('website.mcqs_system.mcqs.mock-tests', compact('mockTests', 'testTypes'));
    }

    // Mock Test detail
    public function mockTestDetail(MockTest $mockTest)
    {
        if ($mockTest->status !== 'published') {
            abort(404);
        }

        $mockTest->load(['testType', 'questions.mcq.subject', 'questions.mcq.topic']);

        // Get user's previous attempts
        $previousAttempts = null;
        if (Auth::check()) {
            $previousAttempts = UserTestAttempt::where('user_id', Auth::id())
                ->where('mock_test_id', $mockTest->id)
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('website.mcqs_system.mcqs.mock-test-detail', compact('mockTest', 'previousAttempts'));
    }

    // Start mock test
    public function startMockTest(MockTest $mockTest)
    {
        if ($mockTest->status !== 'published') {
            abort(404);
        }

        // Check if user is logged in for premium tests
        if ($mockTest->is_free === false && !Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this premium test.');
        }

        // Check max attempts
        if ($mockTest->max_attempts && Auth::check()) {
            $attemptCount = UserTestAttempt::where('user_id', Auth::id())
                ->where('mock_test_id', $mockTest->id)
                ->count();
            
            if ($attemptCount >= $mockTest->max_attempts) {
                return back()->with('error', 'You have reached the maximum attempts for this test.');
            }
        }

        // Create test attempt
        $attempt = UserTestAttempt::create([
            'user_id' => Auth::id(),
            'mock_test_id' => $mockTest->id,
            'started_at' => now(),
            'total_questions' => $mockTest->total_questions,
            'total_possible_marks' => $mockTest->total_marks,
            'status' => 'in_progress'
        ]);

        return redirect()->route('website.mcqs.mock-test-detail', $mockTest->slug);
    }

    // Submit mock test
    public function submitMockTest(MockTest $mockTest, Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.mcq_id' => 'required|exists:mcqs,id',
            'answers.*.selected_answers' => 'required|array'
        ]);

        $attempt = UserTestAttempt::where('user_id', Auth::id())
            ->where('mock_test_id', $mockTest->id)
            ->where('status', 'in_progress')
            ->latest()
            ->firstOrFail();

        $totalMarks = 0;
        $correctAnswers = 0;
        $wrongAnswers = 0;
        $answersData = [];
        $questionAnalysis = [];

        foreach ($request->answers as $answer) {
            $mcq = Mcq::find($answer['mcq_id']);
            $selectedAnswers = $answer['selected_answers'];
            $correctAnswersList = $mcq->correct_answers;
            
            // Check if answers are correct
            $isCorrect = empty(array_diff($correctAnswersList, $selectedAnswers)) && 
                         empty(array_diff($selectedAnswers, $correctAnswersList));
            
            $marks = $isCorrect ? $mcq->marks : ($mcq->negative_marks * -1);
            $totalMarks += $marks;

            if ($isCorrect) {
                $correctAnswers++;
            } else {
                $wrongAnswers++;
            }

            // Save user answer
            UserMcqAnswer::create([
                'user_id' => Auth::id(),
                'mcq_id' => $mcq->id,
                'test_attempt_id' => $attempt->id,
                'topic_id' => $mcq->topic_id,
                'selected_answers' => $selectedAnswers,
                'is_correct' => $isCorrect,
                'time_taken_seconds' => $answer['time_taken'] ?? 0
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
                'is_correct' => $isCorrect
            ];
        }

        $percentage = ($totalMarks / $mockTest->total_marks) * 100;
        $resultStatus = $percentage >= $mockTest->passing_marks ? 'passed' : 'failed';

        // Update attempt
        $attempt->update([
            'completed_at' => now(),
            'submitted_at' => now(),
            'attempted_questions' => count($request->answers),
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'skipped_questions' => $mockTest->total_questions - count($request->answers),
            'total_marks_obtained' => $totalMarks,
            'percentage' => $percentage,
            'result_status' => $resultStatus,
            'time_taken_seconds' => Carbon::parse($attempt->started_at)->diffInSeconds(now()),
            'answers_data' => $answersData,
            'question_analysis' => $questionAnalysis,
            'status' => 'completed'
        ]);

        return redirect()->route('website.mcqs.test-result', $attempt->uuid);
    }

    // Test result
    public function testResult(UserTestAttempt $attempt)
    {
        if (Auth::id() !== $attempt->user_id) {
            abort(403);
        }

        $attempt->load(['mockTest.testType', 'mockTest.questions.mcq']);

        return view('website.mcqs_system.mcqs.test-result', compact('attempt'));
    }
}