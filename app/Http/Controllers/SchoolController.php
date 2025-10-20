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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
                    'status',
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
            'email'           => 'required|email|max:100|unique:schools,email',
            'admin-name'      => 'required|string|max:255',
            'admin-email'     => 'required|email|max:100|email|unique:users,email',
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
            $school = School::with('images', 'user')->findOrFail($id);

            // Count current non-removed images
            $currentImageCount = $school->images->count();
            $imagesToRemove = $request->input('remove_images', []);
            $imagesToKeepCount = $currentImageCount - count($imagesToRemove);

            // Count new uploads
            $newImageCount = $request->hasFile('school_images') ? count($request->file('school_images')) : 0;

            // Enforce max 10 total images
            if (($imagesToKeepCount + $newImageCount) > 10) {
                return redirect()->back()->withErrors([
                    'school_images' => 'Total number of school images cannot exceed 10.'
                ])->withInput();
            }

            // ✅ Dynamic rule for admin email (only apply unique if changed)
            $adminEmailRule = ['nullable', 'email', 'max:100'];
            $existingAdminEmail = optional($school->user)->email;

            if ($request->input('admin-email') !== $existingAdminEmail) {
                $adminEmailRule[] = Rule::unique('users', 'email');
            }

            // ✅ Dynamic rule for school email (only apply unique if changed)
            $schoolEmailRule = ['nullable', 'email', 'max:100'];
            if ($request->input('email') !== $school->email) {
                $schoolEmailRule[] = Rule::unique('schools', 'email')->ignore($school->id);
            }

            $validated = $request->validate([
                'name'            => 'required|string|max:255',
                'description'     => 'nullable|string',
                'address'         => 'required|string|max:255',
                'city'            => 'required|string|max:100',
                'contact_number'  => 'nullable|string|max:20',

                // ✅ Apply dynamic email rules
                'email'           => $schoolEmailRule,
                'admin-email'     => $adminEmailRule,

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
                'admin-name'      => 'required|string|max:255',
                'banner_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'school_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'image_titles'    => 'nullable|array',
                'image_titles.*'  => 'nullable|string|max:255',
                'remove_images'   => 'nullable|array',
                'remove_images.*' => 'exists:school_images,id,school_id,' . $school->id,
            ]);

            // ✅ Update school info
            $school->update([
                'name'            => $validated['name'],
                'description'     => $validated['description'] ?? null,
                'address'         => $validated['address'],
                'city'            => $validated['city'],
                'contact_number'  => $validated['contact_number'] ?? null,
                'email'           => $validated['email'] ?? null,
                'website'         => $validated['website'] ?? null,
                'facilities'      => $validated['facilities'] ?? null,
                'school_type'     => $validated['school_type'],
                'regular_fees'    => $validated['regular_fees'] ?? null,
                'discounted_fees' => $validated['discounted_fees'] ?? null,
                'admission_fees'  => $validated['admission_fees'] ?? null,
                'status'          => $validated['status'],
                'visibility'      => $validated['visibility'],
                'publish_date'    => $validated['publish_date'] ?? null,
            ]);

            $folderName = Str::slug($school->name, '-');

            // ✅ Handle banner image
            $shouldRemoveBanner = $request->filled('remove_banner');
            $hasNewBanner = $request->hasFile('banner_image');

            if ($shouldRemoveBanner && $school->banner_image) {
                Storage::disk('website')->delete($school->banner_image);
                $school->banner_image = null;
            }

            if ($hasNewBanner) {
                if ($school->banner_image) {
                    Storage::disk('website')->delete($school->banner_image);
                }
                $bannerPath = Storage::disk('website')
                    ->putFile("school/{$folderName}/banner", $request->file('banner_image'));
                $school->banner_image = $bannerPath;
            }

            $school->save();

            // ✅ Banner title and tagline
            $school->update([
                'banner_title'   => $request->input('banner_title'),
                'banner_tagline' => $request->input('banner_tagline'),
            ]);

            // ✅ Remove selected images
            if (!empty($imagesToRemove)) {
                $imagesToDelete = SchoolImage::where('school_id', $school->id)
                    ->whereIn('id', $imagesToRemove)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    Storage::disk('website')->delete($image->image_path);
                    $image->delete();
                }
            }

            // ✅ Add new images
            if ($request->hasFile('school_images')) {
                foreach ($request->file('school_images') as $index => $imageFile) {
                    $imagePath = Storage::disk('website')
                        ->putFile("school/{$folderName}/gallery", $imageFile);

                    SchoolImage::create([
                        'school_id'  => $school->id,
                        'image_path' => $imagePath,
                        'title'      => $request->input("image_titles.{$index}"),
                    ]);
                }
            }

            // ✅ Update or create admin user
            $user = $school->user;
            if ($user) {
                $user->name = $validated['admin-name'];
                if (!empty($validated['admin-email'])) {
                    $user->email = $validated['admin-email'];
                }
                if (!empty($validated['password'])) {
                    $user->password = bcrypt($validated['password']);
                }
                $user->save();
            } else {
                $user = User::create([
                    'name'      => $validated['admin-name'],
                    'email'     => $validated['admin-email'] ?? $validated['email'],
                    'password'  => bcrypt($validated['password'] ?? 'default123'),
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
            Log::error('Error updating school: ' . $e->getMessage(), ['school_id' => $id]);
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



    public function register(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_email' => 'required|email|unique:schools,email',
            'school_address' => 'required|string',
            'school_city' => 'required|string',
            'school_contact' => 'nullable|string',
            'school_description' => 'nullable|string',
            'school_facilities' => 'nullable|string',
            'school_type' => 'required|in:Co-Ed,Boys,Girls',
            'school_website' => 'nullable|url',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
            'regular_fees' => 'nullable|numeric',
            'discounted_fees' => 'nullable|numeric',
            'admission_fees' => 'nullable|numeric',
            'school_terms' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            // Create the school
            $school = School::create([
                'uuid' => Str::uuid(),
                'name' => $validated['school_name'],
                'email' => $validated['school_email'],
                'address' => $validated['school_address'],
                'city' => $validated['school_city'],
                'contact_number' => $validated['school_contact'],
                'website' => $validated['school_website'],
                'description' => $validated['school_description'],
                'facilities' => $validated['school_facilities'],
                'school_type' => $validated['school_type'],
                'regular_fees' => $validated['regular_fees'],
                'discounted_fees' => $validated['discounted_fees'],
                'admission_fees' => $validated['admission_fees'],
                'status' => 'inactive',
                'visibility' => 'public',
                'publish_date' => now(),
            ]);

            // Create the admin user
            $user = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'school_id' => $school->id,
                'password' => $validated['admin_password'],
            ]);

            $user->assignRole('school-admin');

            DB::commit();

            Auth::login($user);

            return redirect()->route('school.dashboard')->with('success', 'School registered successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Registration failed: ' . $e->getMessage())->withInput();
        }
    }
}
