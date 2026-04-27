<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $query = Topic::with(['subject'])
            ->withCount(['mcqs']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('difficulty_level')) {
            $level = (string) $request->difficulty_level;
            if (in_array($level, ['beginner', 'intermediate', 'advanced'], true)) {
                $query->where('difficulty_level', $level);
            }
        }

        if ($request->filled('status')) {
            $status = (string) $request->status;
            if (in_array($status, ['active', 'inactive'], true)) {
                $query->where('status', $status);
            }
        }

        $search = $request->string('search')->trim()->toString();
        if ($search !== '') {
            $searchableColumns = ['title', 'description', 'slug', 'uuid'];
            $query->where(function ($q) use ($search, $searchableColumns) {
                $q->where($searchableColumns[0], 'like', '%'.$search.'%');
                foreach (array_slice($searchableColumns, 1) as $column) {
                    $q->orWhere($column, 'like', '%'.$search.'%');
                }
            });
        }

        $sortBy = $request->get('sort_by', 'sort_order');
        $sortDir = strtolower((string) $request->get('sort_dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = [
            'id', 'title', 'slug', 'subject_id', 'difficulty_level', 'estimated_time_minutes',
            'mcqs_count', 'status', 'sort_order', 'created_at', 'subject',
        ];
        if (! in_array($sortBy, $allowedSort, true)) {
            $sortBy = 'sort_order';
            $sortDir = 'asc';
        }

        if ($sortBy === 'subject') {
            $query->select('topics.*')
                ->leftJoin('subjects', 'topics.subject_id', '=', 'subjects.id')
                ->orderBy('subjects.name', $sortDir);
        } elseif ($sortBy === 'mcqs_count') {
            $query->orderBy('mcqs_count', $sortDir);
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $topics = $query->paginate(10)->withQueryString();
        $subjects = Subject::active()->get();

        return view('dashboard.mcqs_system.topics.index', compact('topics', 'subjects'));
    }

    public function create(Request $request)
    {
        $subjects = Subject::active()->get();
        $selectedSubject = $request->get('subject_id');

        return view('dashboard.mcqs_system.topics.create', compact('subjects', 'selectedSubject'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'estimated_time_minutes' => 'nullable|integer|min:1',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        // Generate unique slug for subject
        $slug = Str::slug($request->title);
        $count = Topic::where('subject_id', $request->subject_id)
            ->where('slug', $slug)
            ->count();

        if ($count > 0) {
            $slug = $slug.'-'.($count + 1);
        }

        Topic::create([
            'uuid' => Str::uuid(),
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'content' => $request->content,
            'estimated_time_minutes' => $request->estimated_time_minutes ?? 30,
            'difficulty_level' => $request->difficulty_level,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
        ]);

        return redirect()->route('topics.index')
            ->with('success', 'Topic created successfully.');
    }

    public function show(Topic $topic)
    {
        $topic->load(['subject', 'mcqs' => function ($q) {
            $q->orderBy('difficulty_level')->latest()->take(10);
        }]);

        return view('dashboard.mcqs_system.topics.show', compact('topic'));
    }

    public function edit(Topic $topic)
    {
        $subjects = Subject::active()->get();

        return view('dashboard.mcqs_system.topics.edit', compact('topic', 'subjects'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'estimated_time_minutes' => 'nullable|integer|min:1',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        // Update slug if title changed
        $slug = $topic->slug;
        if ($topic->title !== $request->title) {
            $slug = Str::slug($request->title);
            $count = Topic::where('subject_id', $request->subject_id)
                ->where('slug', $slug)
                ->where('id', '!=', $topic->id)
                ->count();

            if ($count > 0) {
                $slug = $slug.'-'.($count + 1);
            }
        }

        $topic->update([
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'content' => $request->content,
            'estimated_time_minutes' => $request->estimated_time_minutes ?? $topic->estimated_time_minutes,
            'difficulty_level' => $request->difficulty_level,
            'sort_order' => $request->sort_order ?? $topic->sort_order,
            'status' => $request->status,
        ]);

        return redirect()->route('topics.index')
            ->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        // Check if topic has MCQs
        if ($topic->mcqs()->count() > 0) {
            return redirect()->route('topics.index')
                ->with('error', 'Cannot delete topic with associated MCQs.');
        }

        $topic->delete();

        return redirect()->route('topics.index')
            ->with('success', 'Topic deleted successfully.');
    }

    // Bulk actions
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
        ]);

        $ids = $request->ids;

        switch ($request->action) {
            case 'activate':
                Topic::whereIn('id', $ids)->update(['status' => 'active']);
                $message = 'Selected topics activated successfully.';
                break;

            case 'deactivate':
                Topic::whereIn('id', $ids)->update(['status' => 'inactive']);
                $message = 'Selected topics deactivated successfully.';
                break;

            case 'delete':
                // Check for dependencies before deleting
                $hasDependencies = Topic::whereIn('id', $ids)
                    ->whereHas('mcqs')
                    ->exists();

                if ($hasDependencies) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete topics with associated MCQs.',
                    ]);
                }

                Topic::whereIn('id', $ids)->delete();
                $message = 'Selected topics deleted successfully.';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    // Update sort order
    public function updateSort(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:topics,id',
            'categories.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->categories as $item) {
            Topic::where('id', $item['id'])->update([
                'sort_order' => $item['sort_order'],
            ]);
        }

        return response()->json(['success' => true]);
    }
}
