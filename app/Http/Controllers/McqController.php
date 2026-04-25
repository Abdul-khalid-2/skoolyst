<?php

namespace App\Http\Controllers;
use App\Models\Mcq;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv as CsvReader;

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

    /**
     * Live JSON search for the MCQs index (debounced, max 6 results).
     * GET /dashboard/mcqs/search?q=
     */
    public function searchLive(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['results' => []]);
        }
        if (mb_strlen($q) > 100) {
            $q = mb_substr($q, 0, 100);
        }

        $tokens = preg_split('/\s+/u', $q, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if ($tokens === []) {
            return response()->json(['results' => []]);
        }

        $like = static function (string $t): string {
            return '%'.str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $t).'%';
        };

        $strict = Mcq::query()
            ->with(['subject', 'topic'])
            ->where(function ($outer) use ($tokens, $like) {
                foreach ($tokens as $token) {
                    if ($token === '') {
                        continue;
                    }
                    $l = $like($token);
                    $outer->where(function ($w) use ($l) {
                        $w->where('question', 'like', $l)
                            ->orWhereHas('subject', fn ($s) => $s->where('name', 'like', $l))
                            ->orWhereHas('topic', fn ($t) => $t->where('title', 'like', $l));
                    });
                }
            });

        $candidates = $strict->orderBy('updated_at', 'desc')->limit(300)->get();

        if ($candidates->count() < 40) {
            $broad = Mcq::query()
                ->with(['subject', 'topic'])
                ->where(function ($w) use ($tokens, $like) {
                    foreach ($tokens as $token) {
                        if (mb_strlen($token) < 1) {
                            continue;
                        }
                        $l = $like($token);
                        $w->orWhere('question', 'like', $l)
                            ->orWhereHas('subject', fn ($s) => $s->where('name', 'like', $l))
                            ->orWhereHas('topic', fn ($t) => $t->where('title', 'like', $l));
                    }
                })
                ->whereNotIn('id', $candidates->pluck('id')->all() ?: [0])
                ->orderBy('updated_at', 'desc')
                ->limit(200)
                ->get();
            $candidates = $candidates->merge($broad)->unique('id');
        }

        $qLower = mb_strtolower($q);
        $scored = $candidates
            ->map(function (Mcq $mcq) use ($q, $qLower, $tokens) {
                $plain = $this->mcqSearchPlainText($mcq->question);
                if ($plain === '') {
                    return null;
                }
                if (! $this->mcqSearchMatchesAllTokens($plain, $mcq, $tokens, $qLower)) {
                    return null;
                }

                $score = $this->mcqSearchScore($mcq, $plain, $q, $qLower, $tokens);

                return [
                    'mcq' => $mcq,
                    'score' => $score,
                ];
            })
            ->filter()
            ->sortByDesc('score')
            ->take(6)
            ->values();

        $results = $scored->map(function (array $row) use ($q) {
            $m = $row['mcq'];
            $plain = $this->mcqSearchPlainText($m->question);
            $excerpt = Str::limit($plain, 120, '…');

            return [
                'id' => $m->id,
                'title' => $excerpt,
                'subject' => $m->subject?->name,
                'category' => $m->topic?->title,
                'excerpt' => $excerpt,
                'highlight' => $this->mcqSearchHighlightExcerpt($plain, $q, 120),
            ];
        });

        return response()->json(['results' => $results->values()->all()]);
    }

    private function mcqSearchPlainText(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        return trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8')));
    }

    /**
     * After SQL filtering, require all query tokens to match (fuzzy) in question, subject, or topic.
     */
    private function mcqSearchMatchesAllTokens(string $plain, Mcq $mcq, array $tokens, string $qLower): bool
    {
        $p = mb_strtolower($plain);
        $subject = mb_strtolower((string) ($mcq->subject?->name ?? ''));
        $topic = mb_strtolower((string) ($mcq->topic?->title ?? ''));
        $bundle = $p.' '.$subject.' '.$topic;

        foreach ($tokens as $token) {
            if (mb_strlen($token) < 1) {
                continue;
            }
            $t = mb_strtolower($token);
            if (str_contains($bundle, $t)) {
                continue;
            }
            if (str_contains($p, $t) || str_contains($subject, $t) || str_contains($topic, $t)) {
                continue;
            }
            if ($this->mcqFuzzyTokenMatch($p, $t)) {
                continue;
            }
            if (levenshtein(mb_substr($qLower, 0, 255), mb_substr($p, 0, 255)) <= 3) {
                continue;
            }

            return false;
        }

        return true;
    }

    private function mcqFuzzyTokenMatch(string $pLower, string $tLower): bool
    {
        if (mb_strlen($tLower) < 2) {
            return false;
        }
        $words = preg_split('/\s+/u', $pLower, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        foreach ($words as $w) {
            if (mb_strlen($w) < 1 || mb_strlen($tLower) < 1) {
                continue;
            }
            if (mb_stripos($pLower, $tLower) !== false) {
                return true;
            }
            if (mb_strlen($w) < 30 && mb_strlen($tLower) < 30) {
                $d = levenshtein($tLower, $w);
                if ($d >= 0 && $d <= 2) {
                    return true;
                }
            }
        }

        return false;
    }

    private function mcqSearchScore(Mcq $mcq, string $plain, string $q, string $qLower, array $tokens): int
    {
        $p = mb_strtolower($plain);
        $s = 0;
        if ($p === $qLower) {
            $s += 1_000_000;
        } elseif (str_starts_with($p, $qLower)) {
            $s += 500_000;
        } elseif (str_contains($p, $qLower)) {
            $s += 100_000;
        }
        $words = preg_split('/\s+/u', $p, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        foreach ($tokens as $t) {
            if ($t === '') {
                continue;
            }
            $tLower = mb_strtolower($t);
            if (str_contains($p, $tLower)) {
                $s += 20_000;
            }
            foreach ($words as $w) {
                if ($w === '') {
                    continue;
                }
                if (str_starts_with($w, $tLower)) {
                    $s += 8_000;
                }
                if (mb_strlen($tLower) < 32 && mb_strlen($w) < 32) {
                    $d = levenshtein($tLower, $w);
                    if ($d === 0) {
                        $s += 5_000;
                    } elseif ($d === 1) {
                        $s += 2_000;
                    } elseif ($d === 2) {
                        $s += 500;
                    }
                }
            }
        }
        if ($mcq->subject?->name) {
            if (str_contains(mb_strtolower($mcq->subject->name), $qLower)) {
                $s += 2_000;
            }
        }
        if ($mcq->topic?->title) {
            if (str_contains(mb_strtolower($mcq->topic->title), $qLower)) {
                $s += 2_000;
            }
        }

        return $s;
    }

    private function mcqSearchHighlightExcerpt(string $plain, string $query, int $maxLen = 120): string
    {
        $plain = trim($plain);
        if ($plain === '') {
            return '';
        }
        if (mb_strlen($plain) > $maxLen) {
            $plain = mb_substr($plain, 0, $maxLen).'…';
        }
        $tokens = preg_split('/\s+/u', trim($query), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if ($tokens === []) {
            return e($plain);
        }
        $escaped = e($plain);
        foreach ($tokens as $t) {
            if (mb_strlen($t) < 1) {
                continue;
            }
            $escaped = preg_replace(
                '/('.preg_quote($t, '/').')/iu',
                '<mark class="mcq-search-mark fw-bold text-dark">$1</mark>',
                $escaped
            );
        }

        return $escaped;
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

    /**
     * Header columns expected in the bulk-import CSV/XLSX file.
     *
     * IMPORTANT: `question_type` is the question FORMAT (mcq, true_false,
     * multi_select). `test_types` (plural) is a comma-separated list of
     * exam-category names (e.g. "NTS,MDCAT") that map to TestType records
     * via the mcq_test_type pivot. Older files using the legacy column
     * name `test_type` for the question format are still accepted.
     */
    protected const BULK_IMPORT_HEADERS = [
        'subject', 'topic', 'question', 'difficulty',
        'question_type', 'test_types',
        'option_a', 'option_b', 'option_c', 'option_d',
        'correct_option', 'marks', 'negative_marks', 'status',
        'tags', 'is_premium', 'explanation', 'hint',
        'reference_book', 'reference_page',
    ];

    /**
     * Sample rows for the downloadable template. Test types are left
     * blank so a literal import does not fail on category lookup if the
     * sample subjects do not exist in the database.
     */
    protected const BULK_IMPORT_SAMPLE_ROWS = [
        [
            'Mathematics', 'Algebra', 'What is the value of x in the equation 2x + 6 = 14?',
            'easy', 'mcq', '',
            'x = 2', 'x = 4', 'x = 6', 'x = 8',
            'b', '1', '0', 'active',
            'algebra,equations', 'false',
            'Subtract 6 from both sides: 2x = 8, then divide by 2: x = 4',
            'Isolate x by moving constants to the right side',
            'NCERT Mathematics Class 9', '45',
        ],
        [
            'Physics', 'Optics', 'True or False: A convex lens always forms a real image.',
            'medium', 'true_false', '',
            'True', 'False', '', '',
            'b', '1', '0', 'active',
            'optics,lens', 'false',
            'A convex lens forms a virtual image when the object is placed inside its focal length.',
            'Think about the position of the object relative to the focal length.',
            'NCERT Physics Class 10', '168',
        ],
        [
            'Biology', 'Cell Biology', 'Which of the following are organelles found in animal cells?',
            'medium', 'multi_select', '',
            'Mitochondria', 'Chloroplast', 'Ribosome', 'Cell wall',
            'a,c', '2', '0', 'draft',
            'cell,organelles', 'true',
            'Animal cells contain mitochondria and ribosomes; chloroplasts and cell walls are found in plant cells.',
            'Eliminate plant-only structures.',
            'NCERT Biology Class 9', '67',
        ],
    ];

    /**
     * Stream the sample CSV template for bulk MCQ import.
     *
     * Returns Illuminate\Http\Response (not Symfony StreamedResponse) so
     * downstream middleware that calls ->withCookie() (e.g. the locale
     * middleware) keeps working.
     */
    public function downloadBulkImportTemplate()
    {
        $body = $this->buildCsvBody(self::BULK_IMPORT_HEADERS, self::BULK_IMPORT_SAMPLE_ROWS);

        return $this->csvDownloadResponse($body, 'mcqs_import_template.csv');
    }

    /**
     * Build a smart CSV template pre-filled with the user-selected
     * subject, topic and (optional) test types. Other columns are
     * pre-populated with editable placeholder values.
     */
    public function exportTemplate(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'required|exists:topics,id',
            'test_type_ids' => 'nullable|array',
            'test_type_ids.*' => 'exists:test_types,id',
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);
        $topic = Topic::findOrFail($validated['topic_id']);

        $testTypeNames = '';
        if (!empty($validated['test_type_ids'])) {
            $testTypeNames = TestType::whereIn('id', $validated['test_type_ids'])
                ->pluck('name')
                ->implode(',');
        }

        $headers = self::BULK_IMPORT_HEADERS;

        $sampleRows = [
            [
                'subject' => $subject->name,
                'topic' => $topic->title,
                'question' => 'Enter your question here',
                'difficulty' => 'easy',
                'question_type' => 'mcq',
                'test_types' => $testTypeNames,
                'option_a' => 'Option A',
                'option_b' => 'Option B',
                'option_c' => 'Option C',
                'option_d' => 'Option D',
                'correct_option' => 'a',
                'marks' => '1',
                'negative_marks' => '0',
                'status' => 'active',
                'tags' => 'tag1,tag2',
                'is_premium' => 'false',
                'explanation' => 'Explain why the correct answer is correct',
                'hint' => 'A helpful hint for the student',
                'reference_book' => 'Book Name',
                'reference_page' => '1',
            ],
            [
                'subject' => $subject->name,
                'topic' => $topic->title,
                'question' => 'Enter your second question here',
                'difficulty' => 'easy',
                'question_type' => 'mcq',
                'test_types' => $testTypeNames,
                'option_a' => 'Option A',
                'option_b' => 'Option B',
                'option_c' => 'Option C',
                'option_d' => 'Option D',
                'correct_option' => 'b',
                'marks' => '1',
                'negative_marks' => '0',
                'status' => 'active',
                'tags' => 'tag1,tag2',
                'is_premium' => 'false',
                'explanation' => 'Explain why the correct answer is correct',
                'hint' => 'A helpful hint for the student',
                'reference_book' => 'Book Name',
                'reference_page' => '1',
            ],
        ];

        $orderedRows = array_map(function ($row) use ($headers) {
            $ordered = [];
            foreach ($headers as $col) {
                $ordered[] = $row[$col] ?? '';
            }
            return $ordered;
        }, $sampleRows);

        $filename = sprintf(
            'mcqs_template_%s_%s.csv',
            Str::slug($subject->name) ?: 'subject',
            Str::slug($topic->title) ?: 'topic'
        );

        $body = $this->buildCsvBody($headers, $orderedRows);

        return $this->csvDownloadResponse($body, $filename);
    }

    /**
     * Build a CSV body string with a UTF-8 BOM so Excel reads it
     * correctly. Each row is expected to be a numerically-indexed array
     * of cell values.
     */
    protected function buildCsvBody(array $headers, array $rows): string
    {
        $handle = fopen('php://temp', 'w+');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, $headers);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $body = stream_get_contents($handle);
        fclose($handle);

        return $body !== false ? $body : '';
    }

    /**
     * Wrap a CSV body string in an Illuminate\Http\Response with proper
     * download headers. Returning an Illuminate response (rather than a
     * Symfony StreamedResponse) keeps middleware like the locale
     * middleware happy because it can still call ->withCookie() on it.
     */
    protected function csvDownloadResponse(string $body, string $filename)
    {
        $safeFilename = str_replace('"', '', $filename);

        return response($body, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $safeFilename . '"',
            'Content-Length' => (string) strlen($body),
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    /**
     * Parse an uploaded CSV/XLSX, validate each row, and return a JSON
     * preview without persisting anything.
     */
    public function previewBulkImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ]);

        try {
            $rows = $this->readUploadedSheet($request->file('file'));
        } catch (\Throwable $e) {
            Log::warning('MCQ bulk import preview: failed to parse file', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Could not read the uploaded file. Please check the format.',
            ], 422);
        }

        $parsed = $this->validateImportRows($rows);

        return response()->json([
            'success' => true,
            'total' => count($parsed['rows']),
            'valid' => $parsed['valid_count'],
            'invalid' => $parsed['invalid_count'],
            'rows' => $parsed['rows'],
        ]);
    }

    /**
     * Persist all valid rows from the uploaded file. Invalid rows are
     * skipped and reported in the response.
     */
    public function storeBulkImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ]);

        try {
            $rows = $this->readUploadedSheet($request->file('file'));
        } catch (\Throwable $e) {
            Log::error('MCQ bulk import: failed to parse file', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Could not read the uploaded file. Please check the format.',
            ], 422);
        }

        $parsed = $this->validateImportRows($rows);

        $successCount = 0;
        $failedCount = $parsed['invalid_count'];
        $errors = $parsed['errors'];
        $createdIds = [];

        foreach ($parsed['rows'] as $row) {
            if (! $row['valid']) {
                continue;
            }

            try {
                $mcq = DB::transaction(function () use ($row) {
                    $created = Mcq::create($row['payload']);

                    // Attach exam categories (NTS, MDCAT, …) selected
                    // for this row, preserving the order they appeared
                    // in the CSV via the pivot's sort_order column.
                    $ttIds = $row['test_type_ids'] ?? [];
                    if (! empty($ttIds)) {
                        $sync = [];
                        foreach (array_values($ttIds) as $idx => $id) {
                            $sync[$id] = ['sort_order' => $idx];
                        }
                        $created->testTypes()->sync($sync);
                    }

                    return $created;
                });

                $createdIds[] = $mcq->id;
                $successCount++;
            } catch (\Throwable $e) {
                $failedCount++;
                $errors[] = [
                    'row' => $row['row_number'],
                    'field' => null,
                    'message' => 'Database insert failed: ' . $e->getMessage(),
                ];

                Log::error('MCQ bulk import: failed to insert row', [
                    'row' => $row['row_number'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'imported' => $successCount,
            'failed' => $failedCount,
            'errors' => $errors,
            'created_ids' => $createdIds,
        ]);
    }

    /**
     * Read an uploaded CSV/XLSX file into an associative array of rows
     * keyed by header name.
     *
     * @return array<int, array<string, string>>
     */
    protected function readUploadedSheet($uploadedFile): array
    {
        $path = $uploadedFile->getRealPath();
        $extension = strtolower($uploadedFile->getClientOriginalExtension());

        if (in_array($extension, ['csv', 'txt'], true)) {
            $reader = new CsvReader();
            $reader->setInputEncoding(CsvReader::GUESS_ENCODING);
            $reader->setDelimiter(',');
            $reader->setEnclosure('"');
            $reader->setSheetIndex(0);
            $spreadsheet = $reader->load($path);
        } else {
            $spreadsheet = IOFactory::load($path);
        }

        $sheet = $spreadsheet->getActiveSheet();
        $raw = $sheet->toArray(null, true, true, false);

        $rawRows = array_values(array_filter($raw, function ($row) {
            foreach ($row as $cell) {
                if ($cell !== null && trim((string) $cell) !== '') {
                    return true;
                }
            }
            return false;
        }));

        if (empty($rawRows)) {
            return [];
        }

        $header = array_map(function ($value) {
            return strtolower(trim((string) $value));
        }, array_shift($rawRows));

        $rows = [];
        foreach ($rawRows as $rawRow) {
            $assoc = [];
            foreach ($header as $idx => $key) {
                if ($key === '') {
                    continue;
                }
                $assoc[$key] = isset($rawRow[$idx]) ? trim((string) $rawRow[$idx]) : '';
            }
            $rows[] = $assoc;
        }

        return $rows;
    }

    /**
     * Validate the parsed rows and build payloads ready for insertion.
     * Returns a structure with per-row status used for both preview and
     * the actual import.
     */
    protected function validateImportRows(array $rows): array
    {
        $statusMap = [
            'active' => 'published',
            'inactive' => 'archived',
            'draft' => 'draft',
            'published' => 'published',
            'archived' => 'archived',
        ];

        $testTypeMap = [
            'mcq' => 'single',
            'true_false' => 'single',
            'truefalse' => 'single',
            'true-false' => 'single',
            'multi_select' => 'multiple',
            'multiselect' => 'multiple',
            'multi-select' => 'multiple',
            'multiple' => 'multiple',
            'single' => 'single',
        ];

        $optionLetterMap = [
            'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4,
            '1' => 1, '2' => 2, '3' => 3, '4' => 4,
        ];

        $subjectsByName = Subject::active()
            ->get(['id', 'name'])
            ->keyBy(fn ($s) => Str::lower(trim($s->name)));

        $topicsByName = Topic::active()
            ->get(['id', 'subject_id', 'title'])
            ->groupBy('subject_id');

        $testTypesByName = TestType::query()
            ->get(['id', 'name'])
            ->keyBy(fn ($t) => Str::lower(trim($t->name)));

        $userId = auth()->id();
        $errors = [];
        $processed = [];
        $validCount = 0;
        $invalidCount = 0;

        foreach ($rows as $idx => $row) {
            $rowNumber = $idx + 2; // header is row 1, data starts at row 2
            $rowErrors = [];

            $subjectName = trim((string) ($row['subject'] ?? ''));
            $topicName = trim((string) ($row['topic'] ?? ''));
            $question = (string) ($row['question'] ?? '');
            $difficulty = strtolower(trim((string) ($row['difficulty'] ?? '')));

            $explicitQuestionType = strtolower(trim((string) ($row['question_type'] ?? '')));
            $testTypesRaw = trim((string) ($row['test_types'] ?? ''));
            $legacyTestTypeCol = trim((string) ($row['test_type'] ?? ''));

            // Resolve question format vs exam categories. Early "smart"
            // exports wrongly put TestType *names* in the `test_type`
            // column (19-col schema). Correct legacy files put mcq /
            // true_false / multi_select there instead.
            if ($explicitQuestionType !== '') {
                $questionTypeRaw = $explicitQuestionType;
            } elseif ($legacyTestTypeCol !== '') {
                $legacyKey = strtolower($legacyTestTypeCol);
                if (isset($testTypeMap[$legacyKey])) {
                    $questionTypeRaw = $legacyKey;
                } else {
                    $questionTypeRaw = 'mcq';
                    if ($testTypesRaw === '') {
                        $testTypesRaw = $legacyTestTypeCol;
                    }
                }
            } else {
                $questionTypeRaw = '';
            }

            $statusRaw = strtolower(trim((string) ($row['status'] ?? '')));
            $correctRaw = strtolower(trim((string) ($row['correct_option'] ?? '')));

            $subject = $subjectsByName->get(Str::lower($subjectName));
            if (! $subject) {
                $rowErrors[] = ['field' => 'subject', 'message' => "Subject '{$subjectName}' not found."];
            }

            $topic = null;
            if ($subject) {
                $topicCollection = $topicsByName->get($subject->id, collect());
                $topic = $topicCollection->first(function ($t) use ($topicName) {
                    return Str::lower(trim($t->title)) === Str::lower(trim($topicName));
                });
                if (! $topic) {
                    $rowErrors[] = ['field' => 'topic', 'message' => "Topic '{$topicName}' not found in subject '{$subject->name}'."];
                }
            } elseif ($topicName === '') {
                $rowErrors[] = ['field' => 'topic', 'message' => 'Topic is required.'];
            }

            if (trim(strip_tags($question)) === '') {
                $rowErrors[] = ['field' => 'question', 'message' => 'Question is required.'];
            }

            if (! in_array($difficulty, ['easy', 'medium', 'hard'], true)) {
                $rowErrors[] = ['field' => 'difficulty', 'message' => "Difficulty must be one of: easy, medium, hard."];
            }

            $questionType = $testTypeMap[$questionTypeRaw] ?? null;
            if ($questionType === null) {
                $rowErrors[] = ['field' => 'question_type', 'message' => "question_type must be one of: mcq, true_false, multi_select."];
            }

            // Resolve the optional `test_types` (plural) column into a
            // list of TestType IDs. Empty is fine; unknown names are
            // reported per row so users can spot typos.
            $testTypeIds = [];
            if ($testTypesRaw !== '') {
                $names = array_values(array_filter(array_map('trim', explode(',', $testTypesRaw)), fn ($n) => $n !== ''));
                $missing = [];
                foreach ($names as $name) {
                    $key = Str::lower($name);
                    $tt = $testTypesByName->get($key);
                    if (! $tt) {
                        $missing[] = $name;
                        continue;
                    }
                    $testTypeIds[] = $tt->id;
                }
                $testTypeIds = array_values(array_unique($testTypeIds));
                if (! empty($missing)) {
                    $rowErrors[] = [
                        'field' => 'test_types',
                        'message' => 'Unknown test type(s): ' . implode(', ', $missing) . '.',
                    ];
                }
            }

            $optionsByLetter = [
                'a' => trim((string) ($row['option_a'] ?? '')),
                'b' => trim((string) ($row['option_b'] ?? '')),
                'c' => trim((string) ($row['option_c'] ?? '')),
                'd' => trim((string) ($row['option_d'] ?? '')),
            ];

            if ($optionsByLetter['a'] === '') {
                $rowErrors[] = ['field' => 'option_a', 'message' => 'option_a is required.'];
            }
            if ($optionsByLetter['b'] === '') {
                $rowErrors[] = ['field' => 'option_b', 'message' => 'option_b is required.'];
            }

            $formattedOptions = [];
            $key = 1;
            $letterToKey = [];
            foreach (['a', 'b', 'c', 'd'] as $letter) {
                if ($optionsByLetter[$letter] !== '') {
                    $formattedOptions[$key] = $optionsByLetter[$letter];
                    $letterToKey[$letter] = $key;
                    $key++;
                }
            }

            $correctAnswers = [];
            if ($correctRaw === '') {
                $rowErrors[] = ['field' => 'correct_option', 'message' => 'correct_option is required.'];
            } else {
                $tokens = array_filter(array_map('trim', explode(',', $correctRaw)));
                foreach ($tokens as $token) {
                    if (! isset($optionLetterMap[$token])) {
                        $rowErrors[] = ['field' => 'correct_option', 'message' => "Invalid correct_option value '{$token}'. Use a, b, c, or d."];
                        continue;
                    }
                    $letter = is_numeric($token) ? chr(96 + (int) $token) : $token;
                    if (! isset($letterToKey[$letter])) {
                        $rowErrors[] = ['field' => 'correct_option', 'message' => "correct_option '{$token}' refers to an empty option."];
                        continue;
                    }
                    $correctAnswers[] = (string) $letterToKey[$letter];
                }
                $correctAnswers = array_values(array_unique($correctAnswers));
            }

            if ($questionType === 'single' && count($correctAnswers) > 1) {
                $rowErrors[] = ['field' => 'correct_option', 'message' => 'Single-choice (mcq / true_false) questions can have only one correct option.'];
            }
            if ($questionType === 'multiple' && count($correctAnswers) < 2) {
                $rowErrors[] = ['field' => 'correct_option', 'message' => 'multi_select questions must have at least two correct options (comma-separated).'];
            }

            $marks = trim((string) ($row['marks'] ?? ''));
            if ($marks === '' || ! is_numeric($marks) || (float) $marks <= 0) {
                $rowErrors[] = ['field' => 'marks', 'message' => 'marks must be a positive number.'];
            }
            $marksInt = (int) round((float) $marks);

            $negativeMarksRaw = trim((string) ($row['negative_marks'] ?? ''));
            if ($negativeMarksRaw === '') {
                $negativeMarksInt = 0;
            } elseif (! is_numeric($negativeMarksRaw) || (float) $negativeMarksRaw < 0) {
                $rowErrors[] = ['field' => 'negative_marks', 'message' => 'negative_marks must be greater than or equal to 0.'];
                $negativeMarksInt = 0;
            } else {
                $negativeMarksInt = (int) round((float) $negativeMarksRaw);
            }

            $status = $statusMap[$statusRaw] ?? null;
            if ($status === null) {
                $rowErrors[] = ['field' => 'status', 'message' => 'status must be one of: active, inactive, draft.'];
            }

            $isPremiumRaw = strtolower(trim((string) ($row['is_premium'] ?? '')));
            $premiumTrue = ['1', 'true', 'yes', 'y'];
            $premiumFalse = ['', '0', 'false', 'no', 'n'];
            if (in_array($isPremiumRaw, $premiumTrue, true)) {
                $isPremium = true;
            } elseif (in_array($isPremiumRaw, $premiumFalse, true)) {
                $isPremium = false;
            } else {
                $rowErrors[] = ['field' => 'is_premium', 'message' => 'is_premium must be true or false.'];
                $isPremium = false;
            }

            $tagsRaw = trim((string) ($row['tags'] ?? ''));
            $tagsArray = $tagsRaw === ''
                ? null
                : array_values(array_filter(array_map('trim', explode(',', $tagsRaw)), fn ($t) => $t !== ''));

            $explanation = trim((string) ($row['explanation'] ?? ''));
            $hint = trim((string) ($row['hint'] ?? ''));
            $referenceBook = trim((string) ($row['reference_book'] ?? ''));
            $referencePage = trim((string) ($row['reference_page'] ?? ''));

            $isValid = empty($rowErrors);

            $payload = null;
            if ($isValid) {
                $payload = [
                    'uuid' => Str::uuid(),
                    'question' => $question,
                    'question_type' => $questionType,
                    'subject_id' => $subject->id,
                    'topic_id' => $topic->id,
                    'options' => json_encode($formattedOptions),
                    'correct_answers' => json_encode($correctAnswers),
                    'explanation' => $explanation !== '' ? $explanation : null,
                    'hint' => $hint !== '' ? $hint : null,
                    'difficulty_level' => $difficulty,
                    'time_limit_seconds' => null,
                    'marks' => $marksInt,
                    'negative_marks' => $negativeMarksInt,
                    'tags' => $tagsArray ? json_encode($tagsArray) : null,
                    'reference_book' => $referenceBook !== '' ? $referenceBook : null,
                    'reference_page' => $referencePage !== '' ? $referencePage : null,
                    'is_premium' => $isPremium,
                    'is_verified' => false,
                    'status' => $status ?? 'draft',
                    'created_by' => $userId,
                ];
                $validCount++;
            } else {
                $invalidCount++;
                foreach ($rowErrors as $err) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'field' => $err['field'],
                        'message' => $err['message'],
                    ];
                }
            }

            $processed[] = [
                'row_number' => $rowNumber,
                'valid' => $isValid,
                'errors' => $rowErrors,
                'preview' => [
                    'subject' => $subjectName,
                    'topic' => $topicName,
                    'question' => Str::limit(strip_tags($question), 120),
                    'difficulty' => $difficulty,
                    // `test_type` here is the question FORMAT (mcq /
                    // true_false / multi_select); kept under the legacy
                    // key name so the existing preview UI keeps working.
                    'test_type' => $questionTypeRaw,
                    'test_types' => $testTypesRaw,
                    'correct_option' => $correctRaw,
                    'marks' => $marks,
                    'status' => $statusRaw,
                ],
                'payload' => $payload,
                'test_type_ids' => $testTypeIds,
            ];
        }

        return [
            'rows' => $processed,
            'valid_count' => $validCount,
            'invalid_count' => $invalidCount,
            'errors' => $errors,
        ];
    }
}