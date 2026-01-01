<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::with(['testType'])
            ->withCount(['topics', 'mcqs']);

        // Filter by test type
        if ($request->filled('test_type_id')) {
            $query->where('test_type_id', $request->test_type_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $subjects = $query->orderBy('sort_order')->paginate(20);
        $testTypes = TestType::active()->get();

        return view('dashboard.subjects.index', compact('subjects', 'testTypes'));
    }

    public function create()
    {
        $testTypes = TestType::active()->get();
        return view('dashboard.subjects.create', compact('testTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            'test_type_id' => 'nullable|exists:test_types,id',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'color_code' => 'nullable|string|size:7',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        Subject::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'test_type_id' => $request->test_type_id,
            'icon' => $request->icon,
            'description' => $request->description,
            'color_code' => $request->color_code ?? '#3B82F6',
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
        ]);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['testType', 'topics' => function ($q) {
            $q->orderBy('sort_order');
        }, 'mcqs' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('dashboard.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $testTypes = TestType::active()->get();
        return view('dashboard.subjects.edit', compact('subject', 'testTypes'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')->ignore($subject->id),
            ],
            'test_type_id' => 'nullable|exists:test_types,id',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'color_code' => 'nullable|string|size:7',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $subject->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'test_type_id' => $request->test_type_id,
            'icon' => $request->icon,
            'description' => $request->description,
            'color_code' => $request->color_code ?? $subject->color_code,
            'sort_order' => $request->sort_order ?? $subject->sort_order,
            'status' => $request->status,
        ]);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        // Check if subject has topics or MCQs
        if ($subject->topics()->count() > 0 || $subject->mcqs()->count() > 0) {
            return redirect()->route('subjects.index')
                ->with('error', 'Cannot delete subject with associated topics or MCQs.');
        }

        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
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
                Subject::whereIn('id', $ids)->update(['status' => 'active']);
                $message = 'Selected subjects activated successfully.';
                break;

            case 'deactivate':
                Subject::whereIn('id', $ids)->update(['status' => 'inactive']);
                $message = 'Selected subjects deactivated successfully.';
                break;

            case 'delete':
                // Check for dependencies before deleting
                $hasDependencies = Subject::whereIn('id', $ids)
                    ->where(function ($q) {
                        $q->whereHas('topics')
                            ->orWhereHas('mcqs');
                    })
                    ->exists();

                if ($hasDependencies) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete subjects with associated topics or MCQs.'
                    ]);
                }

                Subject::whereIn('id', $ids)->delete();
                $message = 'Selected subjects deleted successfully.';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    // Update sort order
    public function updateSort(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:subjects,id',
            'categories.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->categories as $item) {
            Subject::where('id', $item['id'])->update([
                'sort_order' => $item['sort_order']
            ]);
        }

        return response()->json(['success' => true]);
    }
}
