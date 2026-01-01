<?php

namespace App\Http\Controllers;

use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TestTypeController extends Controller
{
    public function index()
    {
        $testTypes = TestType::orderBy('sort_order')->paginate(20);
        return view('dashboard.test-types.index', compact('testTypes'));
    }

    public function create()
    {
        return view('dashboard.test-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:test_types,name',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        TestType::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
        ]);

        return redirect()->route('test-types.index')
            ->with('success', 'Test type created successfully.');
    }

    public function show(TestType $testType)
    {
        return view('dashboard.test-types.show', compact('testType'));
    }

    public function edit(TestType $testType)
    {
        return view('dashboard.test-types.edit', compact('testType'));
    }

    public function update(Request $request, TestType $testType)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('test_types')->ignore($testType->id),
            ],
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $testType->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? $testType->sort_order,
            'status' => $request->status,
        ]);

        return redirect()->route('test-types.index')
            ->with('success', 'Test type updated successfully.');
    }

    public function destroy(TestType $testType)
    {
        // Check if test type has subjects
        if ($testType->subjects()->count() > 0) {
            return redirect()->route('test-types.index')
                ->with('error', 'Cannot delete test type with associated subjects.');
        }

        $testType->delete();

        return redirect()->route('test-types.index')
            ->with('success', 'Test type deleted successfully.');
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
                TestType::whereIn('id', $ids)->update(['status' => 'active']);
                $message = 'Selected test types activated successfully.';
                break;

            case 'deactivate':
                TestType::whereIn('id', $ids)->update(['status' => 'inactive']);
                $message = 'Selected test types deactivated successfully.';
                break;

            case 'delete':
                // Check for dependencies before deleting
                $hasDependencies = TestType::whereIn('id', $ids)
                    ->whereHas('subjects')
                    ->exists();

                if ($hasDependencies) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete test types with associated subjects.'
                    ]);
                }

                TestType::whereIn('id', $ids)->delete();
                $message = 'Selected test types deleted successfully.';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    // Update sort order
    public function updateSort(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:test_types,id',
            'categories.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->categories as $item) {
            TestType::where('id', $item['id'])->update([
                'sort_order' => $item['sort_order']
            ]);
        }

        return response()->json(['success' => true]);
    }
}
