<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    /**
     * Display a listing of the branches for a specific school.
     */
    public function index(School $school)
    {
        try {
            $branches = $school->branches()->latest()->paginate(10);
            return view('dashboard.branches.index', compact('school', 'branches'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load branches. Please try again.');
        }
    }

    /**
     * Show the form for creating a new branch for a specific school.
     */
    public function create(School $school)
    {
        return view('dashboard.branches.create', compact('school'));
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(Request $request, School $school)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'contact_number' => 'nullable|string|max:20',
                'branch_head_name' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'is_main_branch' => 'boolean',
            ]);

            // If setting this as main branch, remove main branch status from others
            if ($request->has('is_main_branch') && $request->is_main_branch) {
                $school->branches()->update(['is_main_branch' => false]);
            }

            $school->branches()->create($validated);

            return redirect()->route('schools.branches.index', $school)
                ->with('success', 'Branch created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create branch. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified branch.
     */
    public function show(School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        $branch->loadCount(['events', 'reviews']);
        return view('dashboard.branches.show', compact('school', 'branch'));
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit(School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        return view('dashboard.branches.edit', compact('school', 'branch'));
    }

    /**
     * Update the specified branch in storage.
     */
    public function update(Request $request, School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'contact_number' => 'nullable|string|max:20',
                'branch_head_name' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'is_main_branch' => 'boolean',
                'status' => 'required|in:active,inactive',
            ]);

            // If setting this as main branch, remove main branch status from others
            if ($request->has('is_main_branch') && $request->is_main_branch) {
                $school->branches()->where('id', '!=', $branch->id)->update(['is_main_branch' => false]);
            }

            $branch->update($validated);

            return redirect()->route('schools.branches.index', $school)
                ->with('success', 'Branch updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update branch. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified branch from storage.
     */
    public function destroy(School $school, Branch $branch)
    {
        // Verify the branch belongs to the school
        if ($branch->school_id !== $school->id) {
            abort(404);
        }

        try {
            // Prevent deletion of the only branch if it's the main branch
            if ($branch->is_main_branch && $school->branches()->count() === 1) {
                return redirect()->back()
                    ->with('error', 'Cannot delete the only branch. Please create another branch first.');
            }

            $branch->delete();

            // If we deleted the main branch, set another branch as main
            if ($branch->is_main_branch) {
                $newMainBranch = $school->branches()->first();
                if ($newMainBranch) {
                    $newMainBranch->update(['is_main_branch' => true]);
                }
            }

            return redirect()->route('schools.branches.index', $school)
                ->with('success', 'Branch deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete branch. Please try again.');
        }
    }
}
