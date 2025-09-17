<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::get();
        return view('dashboard.schooles.index', compact('schools'));
    }

    public function create()
    {
        return view('dashboard.schooles.create');
    }

    public function store(Request $request)
    {
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

        // Create and save the school
        $school = School::create($validated);

        // Redirect back or to a success page
        return redirect()->route('schools')->with('success', 'School created successfully!');
    }
}
