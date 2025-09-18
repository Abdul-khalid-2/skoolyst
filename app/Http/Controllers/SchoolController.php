<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SchoolController extends Controller
{
    public function index()
    {
        try {
            // Optimized query with eager loading if you have relationships
            $schools = School::select([
                'id',
                'name',
                'email',
                'contact_number',
                'city',
                'address',
                'school_type',
                'created_at'
            ])->latest()->paginate(10);

            return view('dashboard.schooles.index', compact('schools'));
        } catch (\Exception $e) {
            Log::error('Error fetching schools: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load schools. Please try again.');
        }
    }

    public function create()
    {
        return view('dashboard.schooles.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'description'     => 'nullable|string',
                'address'         => 'required|string|max:255',
                'city'            => 'required|string|max:100',
                'contact_number'  => 'nullable|string|max:20',
                'email'           => 'nullable|email|max:100',
                'website'         => 'nullable|url|max:255',
                'facilities'      => 'nullable|string',
                'school_type'     => 'required|in:Co-Ed,Boys,Girls',
            ]);

            School::create($validated);

            return redirect()->route('schools')->with('success', 'School created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to create school. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified school.
     */
    public function show($id)
    {
        try {
            // Eager load relationships including branches
            $school = School::with(['reviews', 'events', 'branches'])->findOrFail($id);

            return view('dashboard.schooles.show', compact('school'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('schools')->with('error', 'School not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load school details. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified school.
     */
    public function edit($id)
    {
        try {
            $school = School::findOrFail($id);
            return view('dashboard.schooles.edit', compact('school'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('schools')->with('error', 'School not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching school for edit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load school for editing. Please try again.');
        }
    }

    /**
     * Update the specified school in storage.
     */
    public function update(Request $request, $id)
    {

        try {
            $school = School::findOrFail($id);

            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'description'     => 'nullable|string',
                'address'         => 'required|string|max:255',
                'city'            => 'required|string|max:100',
                'contact_number'  => 'nullable|string|max:20',
                'email'           => 'nullable|email|max:100',
                'website'         => 'nullable|url|max:255',
                'facilities'      => 'nullable|string',
                'school_type'     => 'required|in:Co-Ed,Boys,Girls',
            ]);

            $school->update($validated);

            return redirect()->route('schools')->with('success', 'School updated successfully!');
        } catch (ModelNotFoundException $e) {

            return redirect()->route('schools')->with('error', 'School not found.');
        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to update school. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified school from storage.
     */
    public function destroy($id)
    {
        try {
            $school = School::findOrFail($id);
            $school->delete();

            return redirect()->route('schools')
                ->with('success', 'School deleted successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('schools')
                ->with('error', 'School not found.');
        } catch (\Exception $e) {
            Log::error('Error deleting school: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete school. Please try again.');
        }
    }
}
