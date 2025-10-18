<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function index()
    {
        try {

            if (auth()->user()->hasRole('super-admin')) {
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
            } elseif (auth()->user()->hasRole('school-admin')) {

                $schoolAdminSchoolId = auth()->user()->school_id;
                $schools = School::where('visibility', 'public')
                    ->select([
                        'id',
                        'name',
                        'email',
                        'contact_number',
                        'city',
                        'address',
                        'school_type',
                        'created_at'
                    ])->where('id', $schoolAdminSchoolId)->latest()->paginate(10);

                return view('dashboard.schooles.index', compact('schools'));
            } else {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }
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

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'address'         => 'required|string|max:255',
            'city'            => 'required|string|max:100',
            'contact_number'  => 'nullable|string|max:20',
            'email'           => 'required|email|max:100|unique:schools,email|unique:users,email',
            'website'         => 'nullable|url|max:255',
            'facilities'      => 'nullable|string',
            'school_type'     => 'required|in:Co-Ed,Boys,Girls',
            'regular_fees'    => 'nullable|numeric|min:0',
            'discounted_fees' => 'nullable|numeric|min:0',
            'admission_fees'  => 'nullable|numeric|min:0',
            'status'          => 'required|in:active,inactive',
            'visibility'      => 'required|in:public,private',
            'publish_date'    => 'nullable|date',
            'password'        => 'required|string|min:8|confirmed',
        ]);

        // Create school
        $school = new School($validated);
        $school->save();
        // ✅ Folder name by school name (slug-safe)
        $folderName = Str::slug($school->name, '-');

        // ✅ Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $path = Storage::disk('website')
                ->putFile("school/{$folderName}/banner", $request->file('banner_image'));
            $school->update(['banner_image' => $path]);
        }

        // ✅ Handle multiple gallery images
        if ($request->hasFile('school_images')) {
            foreach ($request->file('school_images') as $index => $imageFile) {
                if ($imageFile) {
                    $imagePath = Storage::disk('website')
                        ->putFile("school/{$folderName}/gallery", $imageFile);

                    SchoolImage::create([
                        'school_id'  => $school->id,
                        'image_path' => $imagePath,
                        'title'      => $request->image_titles[$index] ?? null,
                    ]);
                }
            }
        }

        $user = new User();
        $user->name = $validated['admin-name'] ?? $validated['name'] . ' Admin';
        $user->email = $validated['admin-email'] ?? $validated['email'];
        $user->password = $validated['password'];
        $user->school_id = $school->id;
        $user->save();

        // Assign role
        $user->assignRole('school-admin');

        return redirect()->route('schools.index')->with('success', 'School and admin created successfully!');
    }

    /**
     * Display the specified school.
     */
    public function show($id)
    {
        try {
            $school = School::with(['reviews', 'events', 'branches'])->findOrFail($id);

            return view('dashboard.schooles.show', compact('school'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('schools')->with('error', 'School not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load school details. Please try again.');
        }
    }

    public function edit($id)
    {
        try {
            $school = School::findOrFail($id);
            $user = user::where('school_id', $school->id)->first();
            return view('dashboard.schooles.edit', compact('school', 'user'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('schools')->with('error', 'School not found.');
        } catch (\Exception $e) {
            Log::error('Error fetching school for edit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load school for editing. Please try again.');
        }
    }

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
                'email'           => [
                    'nullable',
                    'email',
                    'max:100',
                    Rule::unique('schools', 'email')->ignore($school->id),
                    Rule::unique('users', 'email')->ignore($school->user?->id),
                ],
                'website'         => 'nullable|url|max:255',
                'facilities'      => 'nullable|string',
                'school_type'     => 'required|in:Co-Ed,Boys,Girls',
                'regular_fees'    => 'nullable|numeric|min:0',
                'discounted_fees' => 'nullable|numeric|min:0',
                'admission_fees'  => 'nullable|numeric|min:0',
                'status'          => 'required|in:active,inactive',
                'visibility'      => 'required|in:public,private',
                'publish_date'    => 'nullable|date',
                'password'        => 'nullable|string|min:8|confirmed',
            ]);

            // Update school
            $school->update($validated);

            $folderName = Str::slug($school->name, '-');

            if ($request->hasFile('banner_image')) {
                if ($school->banner_image) {
                    Storage::disk('website')->delete($school->banner_image);
                }
                $path = Storage::disk('website')
                    ->putFile("school/{$folderName}/banner", $request->file('banner_image'));
                $school->update(['banner_image' => $path]);
            }

            // ✅ Add new gallery images
            if ($request->hasFile('school_images')) {
                foreach ($request->file('school_images') as $index => $imageFile) {
                    if ($imageFile) {
                        $imagePath = Storage::disk('website')
                            ->putFile("school/{$folderName}/gallery", $imageFile);

                        SchoolImage::create([
                            'school_id'  => $school->id,
                            'image_path' => $imagePath,
                            'title'      => $request->image_titles[$index] ?? null,
                        ]);
                    }
                }
            }

            $user = $school->user;

            if ($user) {
                $user->name = $validated['name'] . ' Admin';

                if (!empty($validated['email'])) {
                    $user->email = $validated['email'];
                }

                if (!empty($validated['password'])) {
                    $user->password = $validated['password'];
                }

                $user->save();
            } else {
                $user = User::create([
                    'name' => $validated['admin-name'] ?? $validated['name'] . ' Admin',
                    'email' => $validated['admin-email'] ?? $validated['email'],
                    'password' => $validated['password'] ?? 'default123',
                    'school_id' => $school->id,
                ]);
                $user->assignRole('school-admin');
            }

            return redirect()->route('schools.show', $school->id)
                ->with('success', 'School updated successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('schools.index')->with('error', 'School not found.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating school: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update school. Please try again.')->withInput();
        }
    }

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
