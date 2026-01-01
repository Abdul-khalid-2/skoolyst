<?php

namespace App\Http\Controllers;

use App\Models\TestType;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Mcq;
use App\Models\MockTest;
use App\Models\UserTestAttempt;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestPreparationController extends Controller
{
    // Home page for test preparation
    public function index()
    {
        $testTypes = TestType::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        $popularSubjects = Subject::with('testType')
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        $featuredMockTests = MockTest::with('testType')
            ->where('status', 'published')
            ->where('is_free', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('dashboard.test-preparation.index', compact('testTypes', 'popularSubjects', 'featuredMockTests'));
    }

    // Show test types
    public function testTypes()
    {
        $testTypes = TestType::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return view('dashboard.test-preparation.test-types', compact('testTypes'));
    }

    // Show subjects by test type
    public function subjectsByType($slug)
    {
        $testType = TestType::where('slug', $slug)->firstOrFail();
        $subjects = Subject::where('test_type_id', $testType->id)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return view('test-preparation.subjects', compact('testType', 'subjects'));
    }

    // Show topics by subject
    public function topicsBySubject($typeSlug, $subjectSlug)
    {
        $testType = TestType::where('slug', $typeSlug)->firstOrFail();
        $subject = Subject::where('slug', $subjectSlug)
            ->where('test_type_id', $testType->id)
            ->firstOrFail();

        $topics = Topic::where('subject_id', $subject->id)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        // Get user progress if logged in
        $userProgress = null;
        if (Auth::check()) {
            $userProgress = UserProgress::where('user_id', Auth::id())
                ->where('subject_id', $subject->id)
                ->get()
                ->keyBy('topic_id');
        }

        return view('test-preparation.topics', compact('testType', 'subject', 'topics', 'userProgress'));
    }

    // Show topic content
    public function showTopic($typeSlug, $subjectSlug, $topicSlug)
    {
        $testType = TestType::where('slug', $typeSlug)->firstOrFail();
        $subject = Subject::where('slug', $subjectSlug)
            ->where('test_type_id', $testType->id)
            ->firstOrFail();

        $topic = Topic::where('slug', $topicSlug)
            ->where('subject_id', $subject->id)
            ->firstOrFail();

        // Update user progress
        if (Auth::check()) {
            $progress = UserProgress::firstOrCreate([
                'user_id' => Auth::id(),
                'topic_id' => $topic->id,
                'subject_id' => $subject->id,
                'progress_type' => 'topic_read'
            ], [
                'total_items' => 1,
                'completed_items' => 1,
                'progress_percentage' => 100,
                'completed_at' => now()
            ]);

            $progress->last_accessed_at = now();
            $progress->save();
        }

        return view('test-preparation.topic-content', compact('testType', 'subject', 'topic'));
    }

    // Show MCQs for a topic
    public function showMcqs($typeSlug, $subjectSlug, $topicSlug)
    {
        $testType = TestType::where('slug', $typeSlug)->firstOrFail();
        $subject = Subject::where('slug', $subjectSlug)
            ->where('test_type_id', $testType->id)
            ->firstOrFail();

        $topic = Topic::where('slug', $topicSlug)
            ->where('subject_id', $subject->id)
            ->firstOrFail();

        $mcqs = Mcq::where('topic_id', $topic->id)
            ->where('status', 'published')
            ->orderBy('id')
            ->paginate(10);

        return view('test-preparation.mcqs', compact('testType', 'subject', 'topic', 'mcqs'));
    }

    // Submit MCQ answer
    public function submitMcqAnswer(Request $request, $mcqId)
    {
        $request->validate([
            'selected_answers' => 'required|array',
            'time_taken' => 'nullable|integer'
        ]);

        $mcq = Mcq::findOrFail($mcqId);

        $userAnswer = new UserMcqAnswer();
        $userAnswer->user_id = Auth::id();
        $userAnswer->mcq_id = $mcq->id;
        $userAnswer->topic_id = $mcq->topic_id;
        $userAnswer->selected_answers = $request->selected_answers;
        $userAnswer->time_taken_seconds = $request->time_taken ?? 0;

        // Check if answer is correct
        $correctAnswers = $mcq->correct_answers;
        sort($correctAnswers);
        $selectedAnswers = $request->selected_answers;
        sort($selectedAnswers);

        $userAnswer->is_correct = $correctAnswers == $selectedAnswers;
        $userAnswer->save();

        // Update user progress
        $progress = UserProgress::firstOrCreate([
            'user_id' => Auth::id(),
            'topic_id' => $mcq->topic_id,
            'progress_type' => 'mcq_practice'
        ], [
            'subject_id' => $mcq->subject_id,
            'total_items' => 0,
            'completed_items' => 0
        ]);

        $progress->total_items = Mcq::where('topic_id', $mcq->topic_id)->count();
        $progress->completed_items = UserMcqAnswer::where('user_id', Auth::id())
            ->where('topic_id', $mcq->topic_id)
            ->count();
        $progress->progress_percentage = ($progress->completed_items / $progress->total_items) * 100;
        $progress->last_accessed_at = now();
        $progress->save();

        return response()->json([
            'success' => true,
            'is_correct' => $userAnswer->is_correct,
            'correct_answers' => $mcq->correct_answers,
            'explanation' => $mcq->explanation,
            'progress' => $progress->progress_percentage
        ]);
    }

    // Mock tests listing
    public function mockTests()
    {
        $mockTests = MockTest::with('testType')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('test-preparation.mock-tests', compact('mockTests'));
    }

    // Show mock test details
    public function showMockTest($slug)
    {
        $mockTest = MockTest::with(['testType', 'questions.mcq'])
            ->where('slug', $slug)
            ->firstOrFail();

        $userAttempts = null;
        if (Auth::check()) {
            $userAttempts = UserTestAttempt::where('user_id', Auth::id())
                ->where('mock_test_id', $mockTest->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('test-preparation.mock-test-details', compact('mockTest', 'userAttempts'));
    }

    // Start mock test
    public function startMockTest($id)
    {
        $mockTest = MockTest::findOrFail($id);

        // Check if user can take test
        if ($mockTest->max_attempts) {
            $attemptCount = UserTestAttempt::where('user_id', Auth::id())
                ->where('mock_test_id', $mockTest->id)
                ->count();

            if ($attemptCount >= $mockTest->max_attempts) {
                return redirect()->back()->with('error', 'You have reached the maximum attempts for this test.');
            }
        }

        // Create test attempt
        $attempt = new UserTestAttempt();
        $attempt->uuid = \Str::uuid();
        $attempt->user_id = Auth::id();
        $attempt->mock_test_id = $mockTest->id;
        $attempt->total_questions = $mockTest->total_questions;
        $attempt->started_at = now();
        $attempt->status = 'in_progress';
        $attempt->save();

        return redirect()->route('test-preparation.take-mock-test', $attempt->uuid);
    }

    // Take mock test
    public function takeMockTest($uuid)
    {
        $attempt = UserTestAttempt::with(['mockTest.questions.mcq'])
            ->where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($attempt->status != 'in_progress') {
            return redirect()->route('test-preparation.mock-test-result', $attempt->uuid);
        }

        return view('test-preparation.take-mock-test', compact('attempt'));
    }

    // Submit mock test
    public function submitMockTest(Request $request, $uuid)
    {
        $attempt = UserTestAttempt::with('mockTest.questions.mcq')
            ->where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $answers = $request->answers ?? [];
        $totalCorrect = 0;
        $totalMarks = 0;

        foreach ($attempt->mockTest->questions as $question) {
            $mcq = $question->mcq;
            $selected = $answers[$mcq->id] ?? [];

            $userAnswer = new UserMcqAnswer();
            $userAnswer->user_id = Auth::id();
            $userAnswer->mcq_id = $mcq->id;
            $userAnswer->test_attempt_id = $attempt->id;
            $userAnswer->topic_id = $mcq->topic_id;
            $userAnswer->selected_answers = $selected;

            // Check if answer is correct
            $correctAnswers = $mcq->correct_answers;
            sort($correctAnswers);
            sort($selected);

            $isCorrect = $correctAnswers == $selected;
            $userAnswer->is_correct = $isCorrect;
            $userAnswer->save();

            if ($isCorrect) {
                $totalCorrect++;
                $totalMarks += $question->marks;
            } else {
                $totalMarks -= $question->negative_marks;
            }
        }

        // Update attempt
        $attempt->completed_at = now();
        $attempt->submitted_at = now();
        $attempt->correct_answers = $totalCorrect;
        $attempt->wrong_answers = $attempt->total_questions - $totalCorrect;
        $attempt->total_marks_obtained = $totalMarks;
        $attempt->total_possible_marks = $attempt->mockTest->total_marks;
        $attempt->percentage = ($totalMarks / $attempt->mockTest->total_marks) * 100;
        $attempt->result_status = $attempt->percentage >= $attempt->mockTest->passing_marks ? 'passed' : 'failed';
        $attempt->status = 'completed';
        $attempt->time_taken_seconds = now()->diffInSeconds($attempt->started_at);
        $attempt->save();

        // Update user progress
        $progress = UserProgress::firstOrCreate([
            'user_id' => Auth::id(),
            'subject_id' => $attempt->mockTest->testType->id,
            'progress_type' => 'test_completed'
        ], [
            'total_items' => 0,
            'completed_items' => 0
        ]);

        $progress->completed_items += 1;
        $progress->progress_percentage = 100; // Test completed
        $progress->last_accessed_at = now();
        $progress->save();

        return redirect()->route('test-preparation.mock-test-result', $attempt->uuid);
    }

    // Show mock test result
    public function mockTestResult($uuid)
    {
        $attempt = UserTestAttempt::with(['mockTest.testType', 'userMcqAnswers.mcq'])
            ->where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('test-preparation.mock-test-result', compact('attempt'));
    }

    // User dashboard
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $recentAttempts = UserTestAttempt::with('mockTest')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $subjectProgress = UserProgress::with('subject')
            ->where('user_id', $user->id)
            ->where('progress_type', 'mcq_practice')
            ->orderBy('progress_percentage', 'desc')
            ->limit(6)
            ->get();

        $totalCorrectAnswers = UserMcqAnswer::where('user_id', $user->id)
            ->where('is_correct', true)
            ->count();

        $totalTestsTaken = UserTestAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return view('test-preparation.dashboard', compact(
            'recentAttempts',
            'subjectProgress',
            'totalCorrectAnswers',
            'totalTestsTaken'
        ));
    }

    // Search MCQs
    public function searchMcqs(Request $request)
    {
        $query = Mcq::with(['topic', 'subject', 'testType'])
            ->where('status', 'published');

        if ($request->has('q') && $request->q) {
            $query->where('question', 'like', '%' . $request->q . '%');
        }

        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->has('difficulty') && $request->difficulty) {
            $query->where('difficulty_level', $request->difficulty);
        }

        $mcqs = $query->orderBy('id', 'desc')->paginate(20);
        $subjects = Subject::where('status', 'active')->get();

        return view('test-preparation.search-mcqs', compact('mcqs', 'subjects'));
    }
}
