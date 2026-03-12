<?php

namespace App\Http\Controllers\Website;

use App\DTOs\TestSubmissionDTO;
use App\Http\Controllers\Controller;
use App\Models\Mcq;
use App\Models\TestType;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\MockTest;
use App\Models\UserTestAttempt;
use App\Models\UserMcqAnswer;
use App\Services\TestSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class WebsiteMcqController extends Controller
{

    protected $testSubmissionService;

    public function __construct(TestSubmissionService $testSubmissionService)
    {
        $this->testSubmissionService = $testSubmissionService;
    }
    // Main MCQs index page
    public function index(Request $request)
    {
        $testTypes = TestType::withCount(['subjects', 'mcqs'])
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        // Get all active subjects with their test types and counts
        $subjects = Subject::with(['testTypes'])
            ->withCount(['mcqs', 'topics'])
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        $recentMcqs = Mcq::with(['subject', 'topic'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('website.mcqs_system.mcqs.index', compact('testTypes', 'subjects', 'recentMcqs'));
    }

    // Test Type page (e.g., Entry Test, Job Test)
    public function testType(TestType $testType, Request $request)
    {
        // Get subjects associated with this test type through pivot
        $subjects = $testType->subjects()
            ->where('status', 'active')
            ->withCount(['mcqs' => function($query) use ($testType) {
                $query->whereHas('testTypes', function($q) use ($testType) {
                    $q->where('test_types.id', $testType->id);
                })->where('status', 'published');
            }])
            ->orderByPivot('sort_order')
            ->get();

        // Load topics for each subject with MCQs count for this test type
        foreach ($subjects as $subject) {
            $subject->topics = Topic::where('subject_id', $subject->id)
                ->where('status', 'active')
                ->withCount(['mcqs' => function($query) use ($testType, $subject) {
                    $query->where('subject_id', $subject->id)
                          ->whereHas('testTypes', function($q) use ($testType) {
                              $q->where('test_types.id', $testType->id);
                          })
                          ->where('status', 'published');
                }])
                ->having('mcqs_count', '>', 0)
                ->orderBy('sort_order')
                ->get();
        }

        // Get total MCQs count for this test type
        $totalMcqs = Mcq::whereHas('testTypes', function($query) use ($testType) {
            $query->where('test_types.id', $testType->id);
        })->where('status', 'published')->count();

        return view('website.mcqs_system.mcqs.test-type', compact(
            'testType', 'subjects', 'totalMcqs'
        ));
    }

    // Subject page - Shows test types and topics
    public function subject(Subject $subject, Request $request)
    {
        // Get test types associated with this subject
        $testTypes = $subject->testTypes()
            ->where('status', 'active')
            ->withCount(['mcqs' => function($query) use ($subject) {
                $query->where('subject_id', $subject->id);
            }])
            ->orderByPivot('sort_order')
            ->get();

        // Get topics for this subject
        $topics = Topic::where('subject_id', $subject->id)
            ->where('status', 'active')
            ->withCount(['mcqs' => function($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('sort_order')
            ->get();

        // Get overall subject statistics
        $difficultyStats = Mcq::where('subject_id', $subject->id)
            ->where('status', 'published')
            ->selectRaw('difficulty_level, COUNT(*) as count')
            ->groupBy('difficulty_level')
            ->get()
            ->keyBy('difficulty_level');

        // Get recent MCQs for preview (from any test type)
        $recentMcqs = Mcq::where('subject_id', $subject->id)
            ->where('status', 'published')
            ->with(['topic', 'testTypes'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Process options for preview
        $recentMcqs->transform(function ($mcq) {
            $mcq->options = is_string($mcq->options) ? 
                json_decode($mcq->options, true) : $mcq->options;
            return $mcq;
        });

        return view('website.mcqs_system.mcqs.subject', compact(
            'subject',
            'testTypes',
            'topics',
            'difficultyStats',
            'recentMcqs'
        ));
    }



    // Test Type + Subject page - Shows MCQs filtered by test type and subject
    public function subjectByTestType(TestType $testType, Subject $subject, Request $request)
    {
        // Verify subject belongs to this test type
        if (!$subject->testTypes->contains($testType->id)) {
            abort(404);
        }

        // Get MCQs filtered by both subject and test type
        $mcqs = Mcq::where('subject_id', $subject->id)
            ->whereHas('testTypes', function($query) use ($testType) {
                $query->where('test_types.id', $testType->id);
            })
            ->where('status', 'published')
            ->with(['topic', 'testTypes'])
            ->when($request->filled('difficulty'), function($query) use ($request) {
                $query->where('difficulty_level', $request->difficulty);
            })
            ->when($request->filled('topic'), function($query) use ($request) {
                $query->where('topic_id', $request->topic);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('website.mcqs_system.mcqs.partials.test-mcqs-content', [
                'mcqs' => $mcqs,
                'testType' => $testType,
                'subject' => $subject
            ])->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'mcqs' => [
                    'current_page' => $mcqs->currentPage(),
                    'data' => $mcqs->items(),
                    'last_page' => $mcqs->lastPage(),
                    'per_page' => $mcqs->perPage(),
                    'total' => $mcqs->total()
                ]
            ]);
        }

        // Get topics for this subject (for non-AJAX requests)
        $topics = Topic::where('subject_id', $subject->id)
            ->where('status', 'active')
            ->withCount(['mcqs' => function($query) use ($testType, $subject) {
                $query->where('subject_id', $subject->id)
                    ->whereHas('testTypes', function($q) use ($testType) {
                        $q->where('test_types.id', $testType->id);
                    })
                    ->where('status', 'published');
            }])
            ->orderBy('sort_order')
            ->get();

        $difficultyStats = Mcq::where('subject_id', $subject->id)
            ->whereHas('testTypes', function($query) use ($testType) {
                $query->where('test_types.id', $testType->id);
            })
            ->where('status', 'published')
            ->selectRaw('difficulty_level, COUNT(*) as count')
            ->groupBy('difficulty_level')
            ->get()
            ->keyBy('difficulty_level');

        return view('website.mcqs_system.mcqs.subject-by-test-type', compact(
            'testType', 'subject', 'mcqs', 'topics', 'difficultyStats'
        ));
    }
    // Topic page - Shows all MCQs for that topic
    public function topic(Subject $subject, Topic $topic, Request $request)
    {
        if ($topic->subject_id !== $subject->id) {
            abort(404);
        }

        // Get MCQs for this topic (from any test type)
        $mcqs = Mcq::where('topic_id', $topic->id)
            ->where('status', 'published')
            ->with(['subject', 'topic', 'testTypes'])
            ->when($request->filled('difficulty'), function($query) use ($request) {
                $query->where('difficulty_level', $request->difficulty);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $relatedTopics = Topic::where('subject_id', $subject->id)
            ->where('id', '!=', $topic->id)
            ->where('status', 'active')
            ->withCount('mcqs')
            ->orderBy('sort_order')
            ->limit(5)
            ->get();

        // Get test types for this topic's MCQs
        $testTypes = TestType::whereHas('mcqs', function($query) use ($topic) {
            $query->where('topic_id', $topic->id)
                  ->where('status', 'published');
        })->get();

        return view('website.mcqs_system.mcqs.topic', compact(
            'subject', 'topic', 'mcqs', 'relatedTopics', 'testTypes'
        ));
    }

    // Practice individual MCQ
    public function practice(Mcq $mcq)
    {
        if ($mcq->status !== 'published') {
            abort(404);
        }

        // Ensure options and correct_answers are arrays
        $mcq->options = is_string($mcq->options) ? 
            json_decode($mcq->options, true) : $mcq->options;
        $mcq->correct_answers = is_string($mcq->correct_answers) ? 
            json_decode($mcq->correct_answers, true) : $mcq->correct_answers;

        // Load relationships
        $mcq->load(['subject', 'topic', 'testTypes']);

        // Get similar questions from the same topic and subject
        $similarMcqs = Mcq::where('subject_id', $mcq->subject_id)
            ->where('topic_id', $mcq->topic_id)
            ->where('id', '!=', $mcq->id)
            ->where('status', 'published')
            ->with(['subject', 'topic'])
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
        
        $correctAnswers = is_string($mcq->correct_answers) ? 
            json_decode($mcq->correct_answers, true) : $mcq->correct_answers;

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


    public function submitTest(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'answers' => 'required|array',
            'topic_id' => 'nullable|exists:topics,id',
            'test_type_id' => 'nullable|exists:test_types,id',
            'time_taken' => 'nullable|integer'
        ]);

        try {
            $dto = TestSubmissionDTO::fromRequest($request->all());
            $results = $this->testSubmissionService->storeSubmission($dto);

            session()->flash('test_results', $results);

            if ($dto->hasTopic()) {
                $topic = Topic::find($dto->getTopicId());
                return redirect()->route('website.mcqs.test-results', ['topic' => $topic->slug]);
            }

            $subject = Subject::find($dto->getSubjectId());
            return redirect()->route('website.mcqs.subject-results', ['subject' => $subject->slug]);

        } catch (\Exception $e) {
            // Log::error('Test submission failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to submit test. Please try again.');
        }
    }

    // For backward compatibility
    public function submitTopicTest(Request $request)
    {
        return $this->submitTest($request);
    }
    
    public function topicTestResults(Request $request, Topic $topic)
    {
        $testResults = session('test_results');
        
        if (!$testResults || $testResults['topic']->id !== $topic->id) {
            return redirect()->route('website.mcqs.topic', [
                'subject' => $topic->subject->slug,
                'topic' => $topic->slug
            ])->with('error', 'No test results found. Please take the test first.');
        }
        
        $subject = $topic->subject;
        
        return view('website.mcqs_system.mcqs.test-results', compact('topic', 'subject', 'testResults'));
    }

    // app/Http/Controllers/Website/WebsiteMcqController.php
    public function subjectTestResults(Request $request, Subject $subject)
    {
        $testResults = session('test_results');
        
        if (!$testResults || ($testResults['subject']->id ?? null) !== $subject->id) {
            return redirect()->route('website.mcqs.subject', $subject->slug)
                ->with('error', 'No test results found. Please take the test first.');
        }
        
        // Get topic if it exists in results (for mixed subject-topic tests)
        $topic = $testResults['topic'] ?? null;
        
        return view('website.mcqs_system.mcqs.test-subject-results', compact('subject', 'topic', 'testResults'));
    }

}