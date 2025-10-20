<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\School;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        if (auth()->user()->hasRole('super-admin')) {
            $events = Event::with('school')->latest()->paginate(10);
            return view('dashboard.events.index', compact('events'));
        } elseif (auth()->user()->hasRole('school-admin')) {
            $schoolAdminSchoolId = auth()->user()->school_id;
            $events = Event::with('school')->where('school_id', $schoolAdminSchoolId)->latest()->paginate(10);
            return view('dashboard.events.index', compact('events'));
        } else {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
    }

    /**
     * Show the form for creating a new event.
     */
    // app/Http/Controllers/EventController.php
    public function create()
    {
        $schools = School::with('branches')->where('status', 'active')->get();
        return view('dashboard.events.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'branch_id' => 'nullable|exists:branches,id',
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_date' => 'required|date',
            'event_location' => 'required|string|max:255',
        ]);

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        // Load both school and branch relationships
        $event->load(['school', 'branch']);
        return view('dashboard.events.show', compact('event'));
    }

    // Add this method to get branches for a school (for AJAX if needed)
    public function getBranches($schoolId)
    {
        $branches = Branch::where('school_id', $schoolId)
            ->where('status', 'active')
            ->get();

        return response()->json($branches);
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        $schools = School::where('status', 'active')->get();
        return view('dashboard.events.edit', compact('event', 'schools'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'branch_id' => 'nullable|exists:branches,id',
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_date' => 'required|date',
            'event_location' => 'required|string|max:255',
        ]);

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
