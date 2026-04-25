<?php

namespace App\Http\Controllers;

use App\Models\MockTest;
use App\Models\TestType;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Mcq;
use App\Models\MockTestQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MockTestController extends Controller
{
    public function index(Request $request)
    {
        $query = MockTest::with(['testType', 'createdBy'])
            ->withCount(['questions', 'attempts']);

        // Filters
        if ($request->filled('test_type_id')) {
            $query->where('test_type_id', $request->test_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_free')) {
            $query->where('is_free', $request->is_free);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $mockTests = $query->latest()->paginate(20);
        $testTypes = TestType::active()->get();

        return view('dashboard.mcqs_system.mock-tests.index', compact('mockTests', 'testTypes'));
    }

    public function create()
    {
        $testTypes = TestType::active()->get();
        $subjects = Subject::active()->get();

        return view('dashboard.mcqs_system.mock-tests.create', compact('testTypes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:mock_tests,title',
            'test_type_id' => 'required|exists:test_types,id',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'total_questions' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'total_time_minutes' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0',
            'test_mode' => 'required|in:practice,timed,exam',
            'shuffle_questions' => 'boolean',
            'show_result_immediately' => 'boolean',
            'allow_retake' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1',
            'is_free' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,published,archived',
            'subject_breakdown' => 'nullable|array',
        ]);

        $mockTestData = [
            'uuid' => Str::uuid(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'test_type_id' => $request->test_type_id,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'total_questions' => $request->total_questions,
            'total_marks' => $request->total_marks,
            'total_time_minutes' => $request->total_time_minutes,
            'passing_marks' => $request->passing_marks,
            'test_mode' => $request->test_mode,
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'show_result_immediately' => $request->boolean('show_result_immediately'),
            'allow_retake' => $request->boolean('allow_retake'),
            'max_attempts' => $request->max_attempts,
            'is_free' => $request->boolean('is_free'),
            'price' => $request->boolean('is_free') ? null : $request->price,
            'status' => $request->status,
            'subject_breakdown' => $request->subject_breakdown ? json_encode($request->subject_breakdown) : null,
            'created_by' => auth()->id(),
        ];

        $mockTest = MockTest::create($mockTestData);

        return redirect()->route('mock-tests.edit', $mockTest)
            ->with('success', 'Mock test created successfully. Now add questions to the test.');
    }

    public function show(MockTest $mockTest)
    {
        $mockTest->load([
            'testType',
            'createdBy',
            'questions' => function ($q) {
                $q->with('mcq.subject', 'mcq.topic')
                    ->orderBy('question_number');
            }
        ]);

        return view('dashboard.mcqs_system.mock-tests.show', compact('mockTest'));
    }

    public function edit(MockTest $mockTest)
    {
        $testTypes = TestType::active()->get();
        $subjects = Subject::active()->get();

        $mockTest->load(['questions.mcq']);

        return view('dashboard.mcqs_system.mock-tests.edit', compact('mockTest', 'testTypes', 'subjects'));
    }

    public function update(Request $request, MockTest $mockTest)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mock_tests')->ignore($mockTest->id),
            ],
            'test_type_id' => 'required|exists:test_types,id',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'total_questions' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'total_time_minutes' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0',
            'test_mode' => 'required|in:practice,timed,exam',
            'shuffle_questions' => 'boolean',
            'show_result_immediately' => 'boolean',
            'allow_retake' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1',
            'is_free' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,published,archived',
            'subject_breakdown' => 'nullable|array',
        ]);

        $mockTest->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'test_type_id' => $request->test_type_id,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'total_questions' => $request->total_questions,
            'total_marks' => $request->total_marks,
            'total_time_minutes' => $request->total_time_minutes,
            'passing_marks' => $request->passing_marks,
            'test_mode' => $request->test_mode,
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'show_result_immediately' => $request->boolean('show_result_immediately'),
            'allow_retake' => $request->boolean('allow_retake'),
            'max_attempts' => $request->max_attempts,
            'is_free' => $request->boolean('is_free'),
            'price' => $request->boolean('is_free') ? null : $request->price,
            'status' => $request->status,
            'subject_breakdown' => $request->subject_breakdown ? json_encode($request->subject_breakdown) : null,
        ]);

        return redirect()->route('mock-tests.edit', $mockTest)
            ->with('success', 'Mock test updated successfully.');
    }

    public function destroy(MockTest $mockTest)
    {
        // Check if test has attempts
        if ($mockTest->attempts()->count() > 0) {
            return redirect()->route('mock-tests.index')
                ->with('error', 'Cannot delete mock test that has user attempts.');
        }

        $mockTest->delete();

        return redirect()->route('mock-tests.index')
            ->with('success', 'Mock test deleted successfully.');
    }

    // Add questions page
    public function addQuestions(MockTest $mockTest)
    {
        $mockTest->load(['testType', 'questions.mcq']);

        $subjects = Subject::active()->get();
        $topics = Topic::active()->get();

        // Get available MCQs (not already in this test)
        $existingMcqIds = $mockTest->questions->pluck('mcq_id')->toArray();

        $availableMcqs = Mcq::with(['subject', 'topic'])
            ->published()
            ->whereNotIn('id', $existingMcqIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.mcqs_system.mock-tests.add-questions', compact(
            'mockTest',
            'subjects',
            'topics',
            'availableMcqs'
        ));
    }

    // Add question to test
    public function addQuestion(Request $request, MockTest $mockTest)
    {
        $request->validate([
            'mcq_id' => 'required|exists:mcqs,id',
            'question_number' => 'nullable|integer|min:1',
            'marks' => 'required|integer|min:1',
            'negative_marks' => 'nullable|integer|min:0',
            'time_limit_seconds' => 'nullable|integer|min:10',
        ]);

        // Check if MCQ is already in test
        if ($mockTest->questions()->where('mcq_id', $request->mcq_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This question is already in the test.'
            ]);
        }

        // Get next question number if not provided
        $questionNumber = $request->question_number ?? ($mockTest->questions()->max('question_number') + 1);

        MockTestQuestion::create([
            'mock_test_id' => $mockTest->id,
            'mcq_id' => $request->mcq_id,
            'question_number' => $questionNumber,
            'marks' => $request->marks,
            'negative_marks' => $request->negative_marks ?? 0,
            'time_limit_seconds' => $request->time_limit_seconds,
        ]);

        // Update test stats
        $mockTest->update([
            'total_questions' => $mockTest->questions()->count(),
            'total_marks' => $mockTest->questions()->sum('marks'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question added to test successfully.'
        ]);
    }

    // Remove question from test
    public function removeQuestion(MockTest $mockTest, Mcq $mcq)
    {
        $mockTest->questions()->where('mcq_id', $mcq->id)->delete();

        // Update test stats
        $mockTest->update([
            'total_questions' => $mockTest->questions()->count(),
            'total_marks' => $mockTest->questions()->sum('marks'),
        ]);

        return response()->json(array_merge([
            'success' => true,
            'message' => 'Question removed from test successfully.',
        ], $this->addQuestionsStatePayload($mockTest)));
    }

    // Update question order/numbers
    public function updateQuestionOrder(Request $request, MockTest $mockTest)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:mock_test_questions,id',
            'questions.*.question_number' => 'required|integer|min:1',
        ]);

        foreach ($request->questions as $question) {
            MockTestQuestion::where('id', $question['id'])
                ->update(['question_number' => $question['question_number']]);
        }

        return response()->json(['success' => true]);
    }

    // Update question details
    public function updateQuestionDetails(Request $request, MockTest $mockTest, MockTestQuestion $question)
    {
        $request->validate([
            'marks' => 'required|integer|min:1',
            'negative_marks' => 'nullable|integer|min:0',
            'time_limit_seconds' => 'nullable|integer|min:10',
        ]);

        $question->update([
            'marks' => $request->marks,
            'negative_marks' => $request->negative_marks ?? $question->negative_marks,
            'time_limit_seconds' => $request->time_limit_seconds,
        ]);

        // Update test total marks
        $mockTest->update([
            'total_marks' => $mockTest->questions()->sum('marks'),
        ]);

        return response()->json(['success' => true]);
    }

    // Bulk add questions
    public function bulkAddQuestions(Request $request, MockTest $mockTest)
    {
        $request->validate([
            'mcq_ids' => 'required|array',
            'mcq_ids.*' => 'exists:mcqs,id',
        ]);

        $existingMcqIds = $mockTest->questions()->pluck('mcq_id')->toArray();
        $newMcqIds = array_diff($request->mcq_ids, $existingMcqIds);

        if (empty($newMcqIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No new questions to add.'
            ]);
        }

        $nextQuestionNumber = $mockTest->questions()->max('question_number') + 1;

        foreach ($newMcqIds as $index => $mcqId) {
            MockTestQuestion::create([
                'mock_test_id' => $mockTest->id,
                'mcq_id' => $mcqId,
                'question_number' => $nextQuestionNumber + $index,
                'marks' => 1, // Default marks
                'negative_marks' => 0,
            ]);
        }

        // Update test stats
        $mockTest->update([
            'total_questions' => $mockTest->questions()->count(),
            'total_marks' => $mockTest->questions()->sum('marks'),
        ]);

        return response()->json(array_merge([
            'success' => true,
            'message' => count($newMcqIds) . ' questions added successfully.',
        ], $this->addQuestionsStatePayload($mockTest)));
    }

    // Get MCQs for selection (AJAX)
    public function getMcqsForSelection(Request $request)
    {
        // Get mock test ID from the current context (you might need to adjust this)
        // If you're calling this from the add-questions page, you might want to pass the mock test ID
        $mockTestId = $request->get('mock_test_id');

        // Get existing MCQ IDs for this test
        if ($mockTestId) {
            $existingMcqIds = MockTestQuestion::where('mock_test_id', $mockTestId)
                ->pluck('mcq_id')
                ->toArray();
        } else {
            $existingMcqIds = [];
        }

        // Build query
        $query = Mcq::with(['subject', 'topic'])
            ->published();

        // Apply filters
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        if ($request->filled('difficulty_level')) {
            $query->where('difficulty_level', $request->difficulty_level);
        }

        if ($request->filled('question_type')) {
            $query->where('question_type', $request->question_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhereHas('subject', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('topic', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $mcqs = $query->orderBy('created_at', 'desc')->paginate(20);

        $mcqs->getCollection()->transform(function (Mcq $mcq) use ($existingMcqIds) {
            $mcq->setAttribute('in_test', in_array($mcq->id, $existingMcqIds, true));
            $opts = $this->mcqOptionsToOrderedList($mcq->getAttribute('options'));
            $mcq->setAttribute('options', $opts);
            $mcq->setAttribute(
                'correct_answers',
                $this->mcqCorrectAnswersToZeroBasedIndices(
                    $mcq->getAttribute('correct_answers'),
                    count($opts)
                )
            );

            return $mcq;
        });

        return response()->json([
            'mcqs' => $mcqs,
            'pagination' => [
                'current_page' => $mcqs->currentPage(),
                'last_page' => $mcqs->lastPage(),
                'total' => $mcqs->total()
            ]
        ]);
    }

    // Bulk actions
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,draft,archive,delete',
            'ids' => 'required|array',
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'publish':
                MockTest::whereIn('id', $ids)->update(['status' => 'published']);
                $message = 'Selected mock tests published successfully.';
                break;

            case 'draft':
                MockTest::whereIn('id', $ids)->update(['status' => 'draft']);
                $message = 'Selected mock tests moved to draft.';
                break;

            case 'archive':
                MockTest::whereIn('id', $ids)->update(['status' => 'archived']);
                $message = 'Selected mock tests archived successfully.';
                break;

            case 'delete':
                // Check for dependencies
                $hasDependencies = MockTest::whereIn('id', $ids)
                    ->whereHas('attempts')
                    ->exists();

                if ($hasDependencies) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete mock tests that have user attempts.'
                    ]);
                }

                MockTest::whereIn('id', $ids)->delete();
                $message = 'Selected mock tests deleted successfully.';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Turn stored options (JSON list or 1-based object {"1": "...", "2": "..."} into a 0..n-1 list for the client.
     */
    private function mcqOptionsToOrderedList($raw): array
    {
        if ($raw === null || $raw === '') {
            return [];
        }
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $raw = is_array($decoded) ? $decoded : [];
        }
        if (!is_array($raw) || $raw === []) {
            return [];
        }
        if (array_is_list($raw)) {
            return array_map(static function ($v) {
                if (is_array($v) || is_object($v)) {
                    return json_encode($v);
                }

                return (string) $v;
            }, $raw);
        }
        $intKeys = [];
        foreach (array_keys($raw) as $k) {
            if (is_int($k)) {
                $intKeys[] = $k;
            } elseif (is_string($k) && ctype_digit($k)) {
                $intKeys[] = (int) $k;
            }
        }
        if ($intKeys === []) {
            $vals = array_values($raw);

            return array_map(static function ($v) {
                if (is_array($v) || is_object($v)) {
                    return json_encode($v);
                }

                return (string) $v;
            }, $vals);
        }
        sort($intKeys);
        $out = [];
        foreach ($intKeys as $k) {
            if (!array_key_exists($k, $raw) && !array_key_exists((string) $k, $raw)) {
                continue;
            }
            $val = $raw[$k] ?? $raw[(string) $k];
            if (is_array($val) || is_object($val)) {
                $out[] = json_encode($val);
            } else {
                $out[] = (string) $val;
            }
        }

        return $out;
    }

    /**
     * correct_answers are usually 1-based option keys (1-4) from the MCQ form; also accept 0-based indices.
     */
    private function mcqCorrectAnswersToZeroBasedIndices($raw, int $optionCount): array
    {
        if ($optionCount < 1) {
            return [];
        }
        if ($raw === null || $raw === '') {
            return [];
        }
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $raw = is_array($decoded) ? $decoded : [];
        }
        if (!is_array($raw)) {
            return [];
        }
        $out = [];
        foreach ($raw as $a) {
            if ($a === null || $a === '') {
                continue;
            }
            $n = (int) $a;
            if ($n >= 1 && $n <= $optionCount) {
                $out[] = $n - 1;
            } elseif ($n >= 0 && $n < $optionCount) {
                $out[] = $n;
            }
        }

        return array_values(array_unique($out, SORT_REGULAR));
    }

    /**
     * Stats + first 10 questions for the add-questions page (AJAX updates without full reload).
     */
    private function addQuestionsStatePayload(MockTest $mockTest): array
    {
        $mockTest->refresh();
        $mockTest->load(['questions.mcq']);

        $ordered = $mockTest->questions->sortBy('question_number');
        $count = $ordered->count();
        $preview = $ordered->take(10)->map(function (MockTestQuestion $q) {
            return [
                'number' => $q->question_number,
                'preview' => Str::limit(strip_tags($q->mcq->question ?? ''), 40),
                'marks' => (int) $q->marks,
            ];
        })->values()->all();

        return [
            'questions_count' => $count,
            'total_questions' => (int) $mockTest->total_questions,
            'marks_sum' => (int) $ordered->sum('marks'),
            'total_marks' => (int) $mockTest->total_marks,
            'current_questions_preview' => $preview,
        ];
    }

    // Add this method to your MockTestController
    public function preview(MockTest $mockTest)
    {
        // Check if test is published or user has access
        if ($mockTest->status !== 'published' && !auth()->user()->is_admin) {
            abort(403, 'This test is not available for preview.');
        }

        $mockTest->load([
            'testType',
            'questions' => function ($q) {
                $q->with('mcq')
                    ->orderBy('question_number');
            }
        ]);

        return view('dashboard.mcqs_system.mock-tests.preview', compact('mockTest'));
    }
}
