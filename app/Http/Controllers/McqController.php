<?php

namespace App\Http\Controllers;
use App\Models\Mcq;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class McqController extends Controller
{
    public function index(Request $request)
    {
        $query = Mcq::with(['subject', 'topic', 'testTypes', 'createdBy'])
            ->withCount('mockTests');
        
        // Filters
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }
        
        if ($request->filled('test_type_id')) {
            $query->whereHas('testTypes', function ($q) use ($request) {
                $q->where('test_type_id', $request->test_type_id);
            });
        }
        
        if ($request->filled('difficulty_level')) {
            $query->where('difficulty_level', $request->difficulty_level);
        }
        
        if ($request->filled('question_type')) {
            $query->where('question_type', $request->question_type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('is_premium')) {
            $query->where('is_premium', $request->is_premium);
        }
        
        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question', 'LIKE', "%{$search}%")
                  ->orWhere('explanation', 'LIKE', "%{$search}%")
                  ->orWhere('tags', 'LIKE', "%{$search}%");
            });
        }
        
        $mcqs = $query->latest()->paginate(20);
        $subjects = Subject::active()->get();
        $topics = Topic::active()->get();
        $testTypes = TestType::active()->get();
        
        return view('dashboard.mcqs_system.mcqs.index', compact('mcqs', 'subjects', 'topics', 'testTypes'));
    }

    public function create(Request $request)
    {
        $subjects = Subject::active()->get();
        $topics = Topic::active()->get();
        $testTypes = TestType::active()->get();
        
        $selectedSubject = $request->get('subject_id');
        $selectedTopic = $request->get('topic_id');
        
        // If topic_id is provided but subject_id is not, find the subject from the topic
        if ($selectedTopic && !$selectedSubject) {
            $topic = Topic::find($selectedTopic);
            if ($topic) {
                $selectedSubject = $topic->subject_id;
            }
        }
        
        return view('dashboard.mcqs_system.mcqs.create', compact('subjects', 'topics', 'testTypes', 'selectedSubject', 'selectedTopic'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'question_type' => 'required|in:single,multiple',
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'required|exists:topics,id',
            'test_type_ids' => 'nullable|array',
            'test_type_ids.*' => 'exists:test_types,id',
            
            // Options validation
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            
            // Correct answers validation
            'correct_answers' => 'required|array|min:1',
            'correct_answers.*' => 'required|string',
            'explanation' => 'nullable|string',
            'hint' => 'nullable|string',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'time_limit_seconds' => 'nullable|integer|min:10',
            'marks' => 'required|integer|min:1',
            'negative_marks' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'reference_book' => 'nullable|string|max:255',
            'reference_page' => 'nullable|string|max:100',
            'is_premium' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        // Ensure single choice has only one correct answer
        if ($request->question_type == 'single' && count($request->correct_answers) > 1) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Single choice questions can have only one correct answer.');
        }

        // Format options as key-value pairs (1 => "option1", 2 => "option2", ...)
        $formattedOptions = [];
        foreach ($request->options as $index => $option) {
            // Use 1-based indexing for options (1, 2, 3, 4...)
            $key = $index + 1;
            $formattedOptions[$key] = $option;
        }

        // Validate that correct answers are valid option keys
        $validOptionKeys = array_keys($formattedOptions);
        foreach ($request->correct_answers as $answer) {
            if (!in_array($answer, $validOptionKeys)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Invalid correct answer: '{$answer}'. Valid options are: " . implode(', ', $validOptionKeys));
            }
        }

        $mcqData = [
            'uuid' => Str::uuid(),
            'question' => $request->question,
            'question_type' => $request->question_type,
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'options' => json_encode($formattedOptions),
            'correct_answers' => json_encode($request->correct_answers),
            'explanation' => $request->explanation,
            'hint' => $request->hint,
            'difficulty_level' => $request->difficulty_level,
            'time_limit_seconds' => $request->time_limit_seconds,
            'marks' => $request->marks,
            'negative_marks' => $request->negative_marks ?? 0,
            'tags' => $request->tags ? json_encode(explode(',', $request->tags)) : null,
            'reference_book' => $request->reference_book,
            'reference_page' => $request->reference_page,
            'is_premium' => $request->boolean('is_premium'),
            'is_verified' => false, // New MCQs need verification
            'status' => $request->status,
            'created_by' => auth()->id(),
        ];

        $mcq = Mcq::create($mcqData);

        // Sync test types (many-to-many)
        if ($request->has('test_type_ids')) {
            $testTypeData = [];
            foreach ($request->test_type_ids as $index => $testTypeId) {
                $testTypeData[$testTypeId] = ['sort_order' => $index];
            }
            $mcq->testTypes()->sync($testTypeData);
        }

        return redirect()->route('mcqs.index')
            ->with('success', 'MCQ created successfully.');
    }

    public function show(Mcq $mcq)
    {
        $mcq->load(['subject', 'topic', 'testTypes', 'createdBy', 'approvedBy']);
        return view('dashboard.mcqs_system.mcqs.show', compact('mcq'));
    }

    public function edit(Mcq $mcq)
    {
        $subjects = Subject::active()->get();
        $topics = Topic::active()->get();
        $testTypes = TestType::active()->get();
        
        // Load options and correct answers
        $options = json_decode($mcq->options, true) ?? [];
        $correctAnswers = json_decode($mcq->correct_answers, true) ?? [];
        $tags = $mcq->tags ? implode(', ', json_decode($mcq->tags, true)) : '';
        
        // Load test types for this MCQ
        $mcq->load('testTypes');
        $selectedTestTypeIds = $mcq->testTypes->pluck('id')->toArray();
        
        return view('dashboard.mcqs_system.mcqs.edit', 
            compact('mcq', 'subjects', 'topics', 'testTypes', 'options', 'correctAnswers', 'tags', 'selectedTestTypeIds')
        );
    }

    public function update(Request $request, Mcq $mcq)
    {
        $request->validate([
            'question' => 'required|string',
            'question_type' => 'required|in:single,multiple',
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'required|exists:topics,id',
            'test_type_ids' => 'nullable|array',
            'test_type_ids.*' => 'exists:test_types,id',
            
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            
            'correct_answers' => 'required|array|min:1',
            'correct_answers.*' => 'required|string|in:' . implode(',', array_keys($request->options)),
            
            'explanation' => 'nullable|string',
            'hint' => 'nullable|string',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'time_limit_seconds' => 'nullable|integer|min:10',
            'marks' => 'required|integer|min:1',
            'negative_marks' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'reference_book' => 'nullable|string|max:255',
            'reference_page' => 'nullable|string|max:100',
            'is_premium' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        // Ensure single choice has only one correct answer
        if ($request->question_type == 'single' && count($request->correct_answers) > 1) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Single choice questions can have only one correct answer.');
        }

        $mcq->update([
            'question' => $request->question,
            'question_type' => $request->question_type,
            'subject_id' => $request->subject_id,
            'topic_id' => $request->topic_id,
            'options' => json_encode($request->options),
            'correct_answers' => json_encode($request->correct_answers),
            'explanation' => $request->explanation,
            'hint' => $request->hint,
            'difficulty_level' => $request->difficulty_level,
            'time_limit_seconds' => $request->time_limit_seconds,
            'marks' => $request->marks,
            'negative_marks' => $request->negative_marks ?? $mcq->negative_marks,
            'tags' => $request->tags ? json_encode(explode(',', $request->tags)) : $mcq->tags,
            'reference_book' => $request->reference_book,
            'reference_page' => $request->reference_page,
            'is_premium' => $request->boolean('is_premium'),
            'status' => $request->status,
        ]);

        // Sync test types (many-to-many)
        if ($request->has('test_type_ids')) {
            $testTypeData = [];
            foreach ($request->test_type_ids as $index => $testTypeId) {
                $testTypeData[$testTypeId] = ['sort_order' => $index];
            }
            $mcq->testTypes()->sync($testTypeData);
        } else {
            $mcq->testTypes()->detach();
        }

        // Reset verification if question or answers changed
        if ($mcq->is_verified && (
            $mcq->question !== $request->question ||
            $mcq->options !== json_encode($request->options) ||
            $mcq->correct_answers !== json_encode($request->correct_answers)
        )) {
            $mcq->update([
                'is_verified' => false,
                'approved_by' => null,
                'approved_at' => null,
            ]);
        }

        return redirect()->route('mcqs.index')
            ->with('success', 'MCQ updated successfully.');
    }

    public function destroy(Mcq $mcq)
    {
        // Check if MCQ is used in mock tests
        if ($mcq->mockTests()->count() > 0) {
            return redirect()->route('mcqs.index')
                ->with('error', 'Cannot delete MCQ that is used in mock tests.');
        }

        $mcq->delete();

        return redirect()->route('mcqs.index')
            ->with('success', 'MCQ deleted successfully.');
    }

    // Verify MCQ
    public function verify(Mcq $mcq)
    {
        if (!$mcq->is_verified) {
            $mcq->update([
                'is_verified' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
            
            return redirect()->route('mcqs.show', $mcq)
                ->with('success', 'MCQ verified successfully.');
        }
        
        return redirect()->route('mcqs.show', $mcq)
            ->with('info', 'MCQ is already verified.');
    }

    // Unverify MCQ
    public function unverify(Mcq $mcq)
    {
        if ($mcq->is_verified) {
            $mcq->update([
                'is_verified' => false,
                'approved_by' => null,
                'approved_at' => null,
            ]);
            
            return redirect()->route('mcqs.show', $mcq)
                ->with('success', 'MCQ verification removed.');
        }
        
        return redirect()->route('mcqs.show', $mcq)
            ->with('info', 'MCQ is not verified.');
    }

    // Bulk actions
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,draft,archive,verify,unverify,delete',
            'ids' => 'required|array',
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'publish':
                Mcq::whereIn('id', $ids)->update(['status' => 'published']);
                $message = 'Selected MCQs published successfully.';
                break;
                
            case 'draft':
                Mcq::whereIn('id', $ids)->update(['status' => 'draft']);
                $message = 'Selected MCQs moved to draft.';
                break;
                
            case 'archive':
                Mcq::whereIn('id', $ids)->update(['status' => 'archived']);
                $message = 'Selected MCQs archived successfully.';
                break;
                
            case 'verify':
                Mcq::whereIn('id', $ids)->update([
                    'is_verified' => true,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);
                $message = 'Selected MCQs verified successfully.';
                break;
                
            case 'unverify':
                Mcq::whereIn('id', $ids)->update([
                    'is_verified' => false,
                    'approved_by' => null,
                    'approved_at' => null,
                ]);
                $message = 'Selected MCQs unverified successfully.';
                break;
                
            case 'delete':
                // Check for dependencies
                $hasDependencies = Mcq::whereIn('id', $ids)
                    ->whereHas('mockTests')
                    ->exists();
                    
                if ($hasDependencies) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete MCQs that are used in mock tests.'
                    ]);
                }
                
                Mcq::whereIn('id', $ids)->delete();
                $message = 'Selected MCQs deleted successfully.';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    // Get topics by subject (AJAX)
    public function getTopicsBySubject(Request $request)
    {
        $topics = Topic::where('subject_id', $request->subject_id)
            ->active()
            ->get();
            
        return response()->json($topics);
    }

    // Get test types by subject (AJAX)
    public function getTestTypesBySubject(Request $request)
    {
        $subject = Subject::with('testTypes')->find($request->subject_id);
        
        if (!$subject) {
            return response()->json([]);
        }
        
        return response()->json($subject->testTypes);
    }
}